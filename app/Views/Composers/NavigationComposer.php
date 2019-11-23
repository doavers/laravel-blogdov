<?php
namespace App\Views\Composers;

use Illuminate\View\View;
use App\Category;
use App\Post;
use App\Tag;

class NavigationComposer
{
	public function compose(View $view)
	{
		$this->composeCategories($view);

		$this->composeTags($view);

		$this->composePopularPosts($view);

		$this->composeArchives($view);
	}

	private function composeCategories(View $view)
	{
		$categories = Category::with(['posts' => function($query) {
			$query->published();
		}])->orderBy('title', 'asc')->get();

		$view->with('categories', $categories);
	}

	private function composeTags(View $view)
	{
		$tags = Tag::has('posts')->get();

		$view->with('tags', $tags);
	}

	private function composeArchives(View $view)
	{
		$archives = Post::selectRaw('count(id) as post_count, year(published_at) as year, monthname(published_at) as month')
			->published()
			->groupBy('year', 'month')
			->orderByRaw('MIN(published_at) DESC')
			->get();

		$view->with('archives', $archives);
	}

	private function composePopularPosts(View $view)
	{
		$popularPosts = Post::published()->popular()->take(3)->get();
		$view->with('popularPosts', $popularPosts);
	}
}
