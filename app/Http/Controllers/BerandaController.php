<?php

namespace App\Http\Controllers;

use App\Models\WebAd;
use App\Models\Program;
use App\Models\Testimoni;
use App\Models\WebResource;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public $title = 'Selamat Datang';

    public function index()
    {
        // Satu query dengan eager loading, latest, dan grouping
        $programs = Program::with(['media', 'mentoringPakets', 'kelasAnsaPakets', 'proofreadingPakets', 'mentors'])
            ->withCount(['mentors', 'proofreadingPakets', 'mentoringPakets'])
            ->latest()
            ->get()
            ->groupBy('program')
            ->map(function ($items) {
                return $items->take(6);
            });

        return view('pages.beranda.index', [
            'title' => $this->title,
            'mentoringPrograms' => $programs->get('Mentoring', collect()),
            'kelasAnsaPrograms' => $programs->get('Kelas ANSA', collect()),
            'proofreadingPrograms' => $programs->get('Proofreading', collect()),
            'webAds' => WebAd::latest()->limit(5)->get(),
            'webResource' => WebResource::first(),
            'testimonies' => Testimoni::with(['mentee.media'])->whereTestimoniableType(Program::class)->whereRating(5)->latest()->limit(10)->get(), 
        ]);
    }
}
