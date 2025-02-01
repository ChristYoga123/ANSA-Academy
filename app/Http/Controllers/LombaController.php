<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use Illuminate\Http\Request;

class LombaController extends Controller
{
    public $title = 'Lomba';
    public function index()
    {
        return view('pages.lomba.index', [
            'title' => $this->title,
            'lombas' => Lomba::with(['media'])->latest()->paginate(6)
        ]);
    }

    public function show($slug)
    {
        $lomba = Lomba::where('slug', $slug)->first();
        return view('pages.lomba.show', [
            'title' => $this->title,
            'lomba' => $lomba
        ]);
    }
}
