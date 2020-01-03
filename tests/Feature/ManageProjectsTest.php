<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_projects()
    {
        // $this->WithoutExceptionHandling();
        $project = factory('App\Project')->create();

        // $attributes = factory('App\Project')->raw();--same as $projects->toArray()
        
        //trying to access the dashboard, you should be redirected
        $this->get('/projects')->assertRedirect('login');

        //code below is re-enphasizing the above
        $this->get('/projects/create')->assertRedirect('login');

        //Trying to access a project specifically, you should be redirected.
        $this->get($project->path())->assertRedirect('login');

        //Trying to persist a project to the database
        $this->post('/projects', $project->toArray())->assertRedirect('login');

        
    }



    /** @test */
    public function a_user_can_create_a_project()
    {
        
        // $this->WithoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */

    public function a_user_can_view_their_project()
    {
        
        $this->be(factory('App\User')->create());

        $this->WithoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
                ->assertSee($project->title)
                ->assertSee($project->description);
    }

    /** @test */

    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->be(factory('App\User')->create());

        // $this->WithoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }

    /** @test */
    public function a_project_requires_a_title()
    {

        // To avoid this error ---TypeError: Argument 2 passed to Illuminate\Foundation\Testing\TestCase::post() must be of the type array, object given, called in /Users/adedamolaogundeinde/desktop/laravel-codes/tests/Feature/ProjectsTest.php----we use "raw" cos
        // create method will build up the attributes and save to the database
        // make method will only build up the attributes especially as object

        // raw on the other hand will build up the attributes and save them as array

        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {

        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', [])->assertSessionHasErrors('description');
    }

    
}
