<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;
use App\Models\Genres;

class GenresTest extends TestCase
{
    private $genre;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->genre = new Genres();
    }

    public function testFillableAttribute() 
    {
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testIfUseTraitsAttribute()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $genreTraits = array_keys(class_uses(Genres::class));

        $this->assertEquals($traits, $genreTraits);
    }

    public function testKeyTypeAttribute()
    {
        $keyType = 'string';
        $this->assertEquals($keyType, $this->genre->getKeyType());
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse($this->genre->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        foreach($dates as $date) {
            $this->assertContains($date, $this->genre->getDates());
        }

        $this->assertCount(count($dates), $this->genre->getDates());
    }
}
