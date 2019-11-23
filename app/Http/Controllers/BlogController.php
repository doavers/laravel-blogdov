<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Category;
use App\Tag;
use App\User;

class BlogController extends Controller
{
	protected $limit = 3;

	public function index()
	{
		// \DB::enableQueryLog();

		/*$posts = Post::all();
		$posts = Post::with('author')->get();
		$posts = Post::with('author')->orderBy('created_at', 'desc')->get();*/
		// Check if any term entered
		/* if($term = request('term')) {
			$posts->where(function($q) use ($term) {
				$q->orWhere('title', 'LIKE', "%{$term}%");
				$q->orWhere('excerpt', 'LIKE', "%{$term}%");
			});
		} */

		$posts = Post::with('author', 'tags', 'category')
					->latestFirst()
					->published()
					->filter(request('term'))
					->simplePaginate($this->limit);
		
		return view("blog.index", compact('posts'));
		/* view("blog.index", compact('posts'))->render();
		dd(\DB::getQueryLog()); */

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
					->with('author', 'tags')
					->latestFirst()
					->published()
					->simplePaginate($this->limit);

		// view("blog.index", compact('posts'))->render();
		return view("blog.index", compact('posts', 'categoryName'));

		//  dd(\DB::getQueryLog());
	}

	public function tag(Tag $tag)
	{
		$tagName = $tag->name;

		/* $posts = Post::with('author')
					->latestFirst()
					->published()
					->where('category_id', $id)
					->simplePaginate($this->limit); */
		
		// \DB::enableQueryLog();
		$posts = $tag->posts()
					->with('author', 'category')
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
					->with('category', 'tags')
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
