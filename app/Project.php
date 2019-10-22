<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

	//diabling automatic mass assignment functionality
    protected $guarded = [];

    public function path()
    {
    	return "/projects/{$this->id}";
    }
}
