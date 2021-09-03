<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    /**
     * Сохранить новую заявку клиента, не чаще 1 раза в сутки.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPost(Request $request)
    {
        if(self::postOnceDay()) {

            $rules = [
                'subject' => 'required|max:255',
                'message' => 'required',
                'file' => 'max:1024',
            ];
            $messages = [
                'subject.required' => ':attribute обязательное поле',
                'subject.max' => 'Поле :attribute должно содержать не бошльше 255 знаков',
                'message.required' => ':attribute обязательное поле',
                'file.max' => 'Размер файла не более :max КБ',
                'file.uploaded' => 'Ошибка загрузки файла',
            ];
            $attributes = [
                'subject' => 'Тема',
                'message' => 'Сообщение',
            ];
            $validated = $this->validate($request, $rules, $messages, $attributes);


            $post = new Post;

            $post->subject = $request->subject;
            $post->message = $request->message;
            $post->answer = "";

            $post->user_id = Auth::id();

            if ($request->file('file')) {
                $post->file = self::fileUpload($request->file('file'));
                $post->file_name = $request->file('file')->getClientOriginalName();
            } else {
                $post->file = "";
                $post->file_name = "";
            }

            try {
                $post->save();
            } catch (\Exception $e) {
                return back()->withError($e->getMessage())->withInput();
            }

            return redirect()->route('dashboard');
        }
        else
        {

            return back()->withError("Вы можете отправлять не более 1 сообщения в сутки. Следующее не ранее ".Post::where('user_id', Auth::id())->orderBy('created_at','desc')->first()->created_at->addDays(1)->toDateTimeString());
        }
    }

    /**
     * Показывает страницу конкретной заявки менеджеру.
     * Если не менеджер, то перенаправляет на основную страницу личного кабинета
     *
     * @param  integer  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function getPost (int $id)
    {
        if (auth()->user()->hasRole(Config::get('constants.roles.manager')))
        {
            $post = Post::find($id);
            return view('post')->with('post', $post);
        }
        else
        {
            return redirect()->route('dashboard');;
        }
    }

    /**
     * Сохранить ответ менеджера на заявку клиента.
     * @param  integer  $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addAnswer(Request $request, int $id)
    {
        $validated = $request->validate([
            'answer' => 'required',
        ]);

        Post::where('id', $id)->update(array('answer'=>$request->answer, 'updated_at'=>now()));
        return redirect()->route('dashboard');;
    }

    /**
     * Загрузка файла на сервер.
     *
     * @param  \Illuminate\Http\File|\Illuminate\Http\UploadedFile $file
     * @return string
     */
    public static function fileUpload($file)
    {
       return basename(Storage::putFile(Config::get('constants.upload_folder'), $file));
    }

    /**
     * Проверяет, оставлял ли клиент запрос за послдение сутки.
     *
     * @return bool
     */
    public static function postOnceDay()
    {
        if(Post::where('user_id', Auth::id())->where('created_at', '>=', Carbon::now()->subDay(1))->count() > 0)
        {
            return false;
        }
        else {
            return true;
        }
    }

}
