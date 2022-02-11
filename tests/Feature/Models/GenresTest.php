<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Genres;
use Tests\TestCase;

class GenresTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Genres::class, 1)->create();
        $genres = Genres::all();
        $genreKey = array_keys($genres->first()->getAttributes());

        $this->assertCount(1, $genres);
        $this->assertEquals(
            [
                "id", 
                "name", 
                "is_active", 
                "deleted_at", 
                "created_at", 
                "updated_at"
            ], 
            $genreKey
        );
    }

    public function testCreate()
    {
        $genre = Genres::create([
            'name' => 'test1'
        ]);
        $genre->refresh();
        $this->assertEquals('test1', $genre->name);
        $this->assertTrue((bool)$genre->is_active);

        $genre = Genres::create([
            'name' => 'test1',
            'is_active' => false
        ]);
        $this->assertFalse((bool)$genre->is_active);

        $genre = Genres::create([
            'name' => 'test1',
            'is_active' => true
        ]);
        $this->assertTrue((bool)$genre->is_active);
    }

    public function testUpdate()
    {
        /** @var Genres $genre */
        $genre = factory(Genres::class)->create([
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'test_name_updated',
            'is_active' => true
        ];
        $genre->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value, $genre->{$key});
        }
    }
}
