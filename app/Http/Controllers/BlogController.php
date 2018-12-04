<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Category;
use App\User;

class BlogController extends Controller
{
	protected $limit = 3;

	public function index()
	{
		/*\DB::enableQueryLog();
		$posts = Post::all();
		$posts = Post::with('author')->get();
		$posts = Post::with('author')->orderBy('created_at', 'desc')->get();
		view("blog.index", compact('posts'))->render();
		dd(\DB::getQueryLog());*/

		$posts = Post::with('author')
					->latestFirst()
					->published()
					->simplePaginate($this->limit);
		return view("blog.index", compact('posts'));
	}
	
	public function category(Category $category)
	{
		$categoryName = $category->title;

		/* $posts = Post::with('author')
					->latestFirst()
					->published()
					->where('category_id', $id)
					->simplePaginate($this->limit); */
		
		// \DB::enableQueryLog();
		$posts = $category->posts()
					->with('author')
					->latestFirst()
					->published()
					->simplePaginate($this->limit);

		// view("blog.index", compact('posts'))->render();
		return view("blog.index", compact('posts', 'categoryName'));

		//  dd(\DB::getQueryLog());
	}

	public function author(User $author)
	{
		$authorName = $author->name;

		$posts = $author->posts()
					->with('category')
					->latestFirst()
					->published()
					->simplePaginate($this->limit);

		return view("blog.index", compact('posts', 'authorName'));
	}

	public function show(Post $post)
	{
		$post->increment('view_count');
		
		return view('blog.show', compact('post'));
	}
}
