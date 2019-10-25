@extends('layouts.app')


@section('content')
	<div class="flex items-center mb-4">
		<h1 class="mr-auto">BIRDBOARD</h1>
		<a href="/projects/create">New Project</a>
	</div>
	<!-- Just so to add the exact comment for lesson 4 -->
	
	<ul>
		@forelse ($projects as $project)
			<li><a href=" {{ $project->path() }} ">{{ $project->title }}</a>  </li>
		@empty
			<li>No Projects yet.</li>
		@endforelse
	</ul>
@endsection