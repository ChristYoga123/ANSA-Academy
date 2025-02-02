<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProofreadingController extends Controller
{
    public $title = 'Proofreading';

    public function index()
    {
        return view('pages.proofreading.index', [
            'title' => $this->title,
            'proofreadings' => Program::with(['media', 'proofreadingPakets'])->withCount(['proofreadingPakets'])->whereProgram('Proofreading')->latest()->paginate(6)
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $proofreadings = Program::with(['media', 'proofreadingPakets'])->whereProgram('Proofreading')->where('judul', 'like', '%' . $search . '%')->latest()->paginate(6);
        return view('pages.proofreading.index', [
            'title' => $this->title,
            'proofreadings' => $proofreadings
        ]);
    }
}
