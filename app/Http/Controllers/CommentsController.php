<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Requests\CommentStoreRequest;
use App\Post;

class CommentsController extends Controller
{
    public function store(Post $post, CommentStoreRequest $request)
    {
        /* $data = $request->all();
        $data['post_id'] = $post->id;

        Comment::create($data); */
        $post->createComment($request->all());

        return redirect()->back()->with('message', 'Your comment successfully send.');
    }
}
