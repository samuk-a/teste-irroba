<?php

namespace App\Models;

class Student extends User
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id'];
}
