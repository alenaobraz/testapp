<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    /**
     * Сохранить новый запрос клиента.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        if(self::post_once_day()) {

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
                $post->file = self::file_upload($request->file('file'));
                $post->file_name = $request->file('file')->getClientOriginalName();
            } else {
                $post->file = "";
                $post->file_name = "";
            }

            try {
                $post->save();
            } catch (Exception $e) {
                return back()->withError($exception->getMessage())->withInput();
            }

            return redirect()->route('dashboard');
        }
        else
        {

            return back()->withError("Вы можете отправлять не более 1 сообщения в сутки. Следующее не ранее ".Post::where('user_id', Auth::id())->orderBy('created_at','desc')->first()->created_at->addDays(1)->toDateTimeString());
        }
    }

    // display post page to a manager
    public function answer_page ($id)
    {
        if (Auth::user()->hasRole(Config::get('constants.roles.manager')))
        {
            $post = Post::find($id);
            return view('post')->with('post', $post);
        }
        else
        {
            return redirect()->route('dashboard');;
        }
    }

    // add post answer
    public function add_answer(Request $request, $id)
    {
        $validated = $request->validate([
            'answer' => 'required',
        ]);

        Post::where('id', $id)->update(array('answer'=>$request->answer, 'updated_at'=>now()));
        return redirect()->route('dashboard');;
    }

    // file upload function
    public static function file_upload($file)
    {
       return basename(Storage::putFile(Config::get('constants.upload_folder'), $file));
    }

    // last post day for a customer + 1 day
    public static function post_once_day()
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
