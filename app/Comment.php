<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = "comment";

	public $timestamps = false;

	protected $fillable = [
		'id_users', 'email',
	];

	public function users()
	{
		return $this->belongsTo('App\User', 'id_users', 'id');
	}
}
