<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;

class Post extends Model
{
	protected $dates = ['published_at'];

	public function author()
	{
		return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // accessor image_url
    public function getImageUrlAttribute($value)
    {
    	$imageUrl = "";

    	if (!is_null($this->image)) 
    	{
    		$imagePath = public_path() . "/img/" . $this->image;
    		if (file_exists($imagePath)) $imageUrl = asset("img/" . $this->image);
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

    public function scopePublished($query)
    {
    	return $query->where("published_at", "<=", Carbon::now());
    }
}
