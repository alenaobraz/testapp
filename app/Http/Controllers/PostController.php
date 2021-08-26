<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    //
    public function add(Request $request)
    {
        $post = new Post;
        $user_id = Auth::id();

        $post->subject = $request->subject;
        $post->message = $request->message;
        $post->user_id = $user_id;

        $post->save();

        return redirect('dashboard');
    }
}
