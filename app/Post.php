<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;

class Post extends Model
{
	use SoftDeletes;

	protected $fillable = ['title', 'slug', 'excerpt', 'body', 'published_at', 'category_id', 'image'];
	protected $dates    = ['published_at'];

	public function author()
	{
		return $this->belongsTo(User::class);
	}
	
	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class);
	}

	public function dateFormatted($showTimes = false)
	{
		$format = "d/m/Y";
		if($showTimes) $format = $format . " H:i:s";
		return $this->created_at->format($format);
	}

	public function publicationLabel()
	{
		if(!$this->published_at) 
		{
			return '<span class="label label-warning">Draft</span>';
		}
		elseif($this->published_at && $this->published_at->isFuture())
		{
			return '<span class="label label-info">Scheduled</span>';
		}
		else
		{
			return '<span class="label label-success">Published</span>';
		}
	}

	// set attribute
	public function setPublishedAtAttribute($value)
	{
		$this->attributes['published_at'] = $value ?: NULL;
	}
	
	// accessor image_url
	public function getImageUrlAttribute($value)
	{
		$imageUrl = "";

		if (!is_null($this->image))
		{
			$directory = config('cms.image.directory');
			$imagePath = public_path() . "/{$directory}/" . $this->image;
			if (file_exists($imagePath)) $imageUrl = asset("{$directory}/" . $this->image);
		}

		return $imageUrl;
	}

	// accessor image_thumb_url
	public function getImageThumbUrlAttribute($value)
	{
		$imageUrl = "";
		if (!is_null($this->image))
		{
			$directory = config('cms.image.directory');
			$ext       = substr(strrchr($this->image, '.'), 1);
			$thumbnail = str_replace(".{$ext}", "_thumb.{$ext}", $this->image);
			$imagePath = public_path() . "/{$directory}/" . $thumbnail;
			if (file_exists($imagePath)) $imageUrl = asset("{$directory}/" . $thumbnail);
		}

		return $imageUrl;
	}

	// accessor body_html
	public function getBodyHtmlAttribute($value)
	{
		return $this->body ? Markdown::convertToHtml(e($this->body)) : NULL;
	}

	// accessor excerpt_html
	public function getExcerptHtmlAttribute($value)
	{
		return $this->excerpt ? Markdown::convertToHtml(e($this->excerpt)) : NULL;
	}

	// accessor tags_html
	public function getTagsHtmlAttribute($value)
	{
		$anchor = [];
		foreach($this->tags as $tag) {
			$anchor[] = '<a href="'.route('tag', $tag->slug).'">'.$tag->name.'</a>';
		}

		return implode(", ", $anchor);
	}

	// accessor date
	public function getDateAttribute($value)
	{
		return is_null($this->published_at) ? '' : $this->published_at->diffForHumans();
	}

	// scope latest post first
	public function scopeLatestFirst($query)
	{
		return $query->orderBy('created_at', 'desc');
	}

	public function scopePopular($query)
	{
		return $query->orderBy('view_count', 'desc');
	}

	public function scopePublished($query)
	{
		return $query->where("published_at", "<=", Carbon::now());
	}
	
	public function scopeScheduled($query)
	{
		return $query->where("published_at", ">", Carbon::now());
	}

	public function scopeDraft($query)
	{
		return $query->whereNull("published_at");
	}

	public function scopeFilter($query, $term)
	{
		// Check if any term entered
		if($term) {
			$query->where(function($q) use ($term) {
				$q->whereHas('author', function($qr) use ($term) {
					$qr->where('name', 'LIKE', "%{$term}%");
				});
				$q->orWhereHas('category', function($qr) use ($term) {
					$qr->where('title', 'LIKE', "%{$term}%");
				});
				$q->orWhere('title', 'LIKE', "%{$term}%");
				$q->orWhere('excerpt', 'LIKE', "%{$term}%");
			});
		}
	}
}
