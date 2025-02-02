<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class KelasAnsaController extends Controller
{
    public $title = 'Kelas Ansa';

    public function index()
    {
        return view('pages.kelas-ansa.index', [
            'title' => $this->title,
            'kelas' => Program::with(['media', 'kelasAnsaPakets', 'kelasAnsaDetail'])->withCount(['kelasAnsaPakets', 'mentors'])->whereProgram('Kelas Ansa')->paginate(6)
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $kelas = Program::with(['media', 'kelasAnsaPakets', 'kelasAnsaDetail'])->withCount(['kelasAnsaPakets'])->whereProgram('Kelas Ansa')->where('judul', 'like', '%' . $search . '%')->paginate(6);

        return view('pages.kelas-ansa.index', [
            'title' => $this->title,
            'kelas' => $kelas
        ]);
    }
}
