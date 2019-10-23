<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_create_projects()
    {
        // $this->WithoutExceptionHandling();

        $attributes = factory('App\Project')->raw();
        
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_a_single_project()
    {
        $project = factory('App\Project')->create();

        $this->get($project->path())->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->actingAs(factory('App\User')->create());

        // $this->WithoutExceptionHandling();

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
