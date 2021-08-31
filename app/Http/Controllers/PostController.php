<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    /**
     * Сохранить новую запрос клиента.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function add(Request $request)
    {

        $validated = $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required',
            'file' => 'max:2000',
        ]);

        $post = new Post;

        $post->subject = $request->subject;
        $post->message = $request->message;
        $post->answer = "";

        $post->user_id = Auth::id();

        if($request->file('file'))
        {
            $post->file = self::file_upload($request->file('file'));
            $post->file_name = $request->file('file')->getClientOriginalName();
        }
        else
        {
            $post->file = "";
            $post->file_name = "";
        }

        $post->save();

        return redirect('dashboard');
    }

    // display post page to a manager
    public function answer_page ($id)
    {
        if (Auth::user()->hasRole(Config::get('constants.roles.manager')))
        {
            $post = Post::all()->where('id', $id)->first();
            return view('post')->with('post', $post);
        }
        else
        {
            return redirect('/dashboard');
        }
    }

    // add post answer
    public function add_answer(Request $request, $id)
    {
        $validated = $request->validate([
            'answer' => 'required',
        ]);

        Post::where('id', $id)->update(array('answer'=>$request->answer, 'updated_at'=>now()));
        return redirect('dashboard');
    }

    // file upload function
    static function file_upload($file)
    {
       return basename(Storage::putFile(Config::get('constants.upload_folder'), $file));
    }

}
