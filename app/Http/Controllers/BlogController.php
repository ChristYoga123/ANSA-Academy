<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\WebResource;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public $title = 'Blog';

    public function index()
    {
        return view('pages.blog.index', [
            'title' => $this->title,
            'artikels' => Artikel::latest()->paginate(6),
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        return view('pages.blog.index', [
            'title' => $this->title,
            'artikels' => Artikel::where('judul', 'like', '%' . $search . '%')->latest()->paginate(),
            'webResource' => WebResource::with('media')->first()

        ]);
    }

    public function show($slug)
    {
        return view('pages.blog.show', [
            'title' => $this->title,
            'artikel' => Artikel::where('slug', $slug)->first(),
        ]);
    }
}
