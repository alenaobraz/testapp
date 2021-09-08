<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Http\Requests\PostRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Вывод начальной страницы личного кабинета.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        if (auth()->user()->hasRole(Config::get('constants.roles.manager')))
        {
            $post = Post::whereNull('answer')->orderBy('created_at')->get();
        }
        else
        {
            $post = Post::where('user_id', Auth::id())->orderBy('created_at')->get();
        }
        return view('dashboard')->with('posts', $post);
    }


    /**
     * Сохранить новую заявку клиента, не чаще 1 раза в сутки.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPost(PostRequest $request)
    {
        if(self::postOnceDay()) {
            try {
                $post = Post::create($request->validated());
            } catch (\Exception $e) {
                return back()->withError($e->getMessage())->withInput();
            }

            if ($request->file('file')) {
                try {
                    $post->update([
                        'file' => self::fileUpload($request->file('file')),
                        'file_name' => $request->file('file')->getClientOriginalName(),
                    ]);
                } catch (\Exception $e) {
                    return back()->withError($e->getMessage())->withInput();
                }
            }
        }

        return redirect()->route('dashboard');
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
     * @param  \App\Http\Requests\AnswerRequest   $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addAnswer(AnswerRequest $request, int $id)
    {
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
        try {
            return basename(Storage::putFile(Config::get('constants.upload_folder'), $file));
        }
        catch (\Exception $e) {
            return back()->withError("Ошибка загрузки файла");
        }
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

    /**
     * Возвращает дату, после которой можно оставлять новую заявку.
     *
     * @return bool
     */
    public static function postOnceDayDate()
    {
        return Post::where('user_id', Auth::id())->orderBy('created_at','desc')->first()->created_at->addDays(1)->toDateTimeString();
    }

}
