<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //
   static $upload_folder = 'public/downloads';

    // add new post
    public function add(Request $request)
    {
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

    // file upload function
    static function file_upload($file)
    {
       return basename(Storage::putFile(Config::get('constants.upload_folder'), $file));
    }

}
