<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Posts extends Model
{
    use Searchable;

    protected $table = "posts";

	public $timestamps = false;

	protected $fillable = [
		'id','title', 'description','published_at',
	];

    public function isPublished()
    {
        return $this->published_at !== null;
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function searchableAs()
    {
        return 'posts_index';
    }

    public function toSearchableArray()
    {
        $array = $this->only('id','title', 'description','published_at');
        return $array;
    }
}
