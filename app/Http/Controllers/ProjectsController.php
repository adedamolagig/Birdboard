<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;


class ProjectsController extends Controller
{
    public function index()
    {
    	// $projects = Project::all(); all as against single

        $projects = auth()->user()->projects;

		return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        if(auth()->user()->isNot($project->owner)){
            abort(403);
        }
        return view('projects.show', compact('project'));
    }

    public function store()
    {
    	//validate
    	$attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required',
        ]);

        // $attributes['owner_id'] = auth()->id();


        //with the code below, the owner id will be set automatically
        auth()->user()->projects()->create($attributes);

		// //persist to the database
		// Project::create($attributes);

		//redirect

		return redirect('/projects');
    }
}
