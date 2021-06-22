<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // トップ画面を表示
    public function index() {
        $posts = Post::all();

        return view('post.index', compact('posts'));
    }

    // 画像保存・表示
    public function image() {
        return view('post.image');
    }

    // 保存
    public function store(Request $request) {
        $post = new Post;
        $post->image = $request->image;
        $post->message = $request->message;
        $post->save();

        return redirect()->route('top');
    }
}
