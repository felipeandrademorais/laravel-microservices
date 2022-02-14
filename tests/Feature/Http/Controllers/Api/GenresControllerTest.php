<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genres;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenresControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testIndex()
    {
        $genres = factory(Genres::class)->create();
        $response = $this->get(route('genres.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$genres->toArray()]);
    }

    public function testShow()
    {
        $genres = factory(Genres::class)->create();
        $response = $this->get(route('genres.show', ['genre' => $genres->id]));
        $response
            ->assertStatus(200)
            ->assertJson($genres->toArray());
    }

    public function testInvalidateionData()
    {
        $response = $this->json('POST', route('genres.store'), []);
        $response
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonValidationErrors(['name']);

        $response = $this->json('POST', route('genres.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'is_active']);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $Genres = Genres::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($Genres->toArray());

        $this->assertTrue((bool)$response->json('is_active'));

        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test',
            'is_active' => false
        ]);

        $response->assertJsonFragment([
            'is_active' => 0,
        ]);
    }

    public function testUpdate()
    {
        $genres = factory(Genres::class)->create([
            'is_active' => false
        ]);

        $response = $this->json(
            'PUT', 
            route('genres.update', ['genre' => $genres->id]),
            [
                'name' => 'test',
                'is_active' => true
            ]
        );

        $id = $response->json('id');
        $genres = Genres::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genres->toArray())
            ->assertJsonFragment([
                'is_active' => true
            ]);
        
        $response = $this->json(
            'PUT', 
            route('genres.update', ['genre' => $genres->id]),
            [
                'name' => 'test',
            ]
        );
    
    }
}
