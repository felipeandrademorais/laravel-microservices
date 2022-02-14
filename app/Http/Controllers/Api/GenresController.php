<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genres;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    public function index(Request $request)
    {
       if($request->has('only_trashed')){
            return Genres::onlyTrashed()->get();
        }
        return Genres::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $category = Genres::create($request->all());
        $category->refresh();
        return $category;
    }

    public function show(Genres $genre)
    {
        return $genre;
    }

    public function update(Request $request, Genres $genre)
    {
        $this->validate($request, $this->rules);
        $genre->update($request->all());
        return $genre;
    }

    public function destroy(Genres $genre)
    {
        $genre->delete();
        return response()->noContent();
    }
}
