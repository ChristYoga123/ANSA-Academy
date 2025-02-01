<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukDigital;

class ProdukDigitalController extends Controller
{
    public $title = 'Produk Digital';

    public function index()
    {
        return view('pages.produk-digital.index', [
            'title' => $this->title,
            'produkDigitals' => ProdukDigital::latest()->paginate(6)
        ]);
    }

    public function show($slug)
    {
        $produkDigital = ProdukDigital::where('slug', $slug)->firstOrFail();

        return view('pages.produk-digital.show', [
            'title' => $this->title,
            'produkDigital' => $produkDigital
        ]);
    }

    public function search(Request $request)
    {
        $produkDigitals = ProdukDigital::where('judul', 'like', '%' . $request->search . '%')->latest()->paginate(6);

        return view('pages.produk-digital.index', [
            'title' => $this->title,
            'produkDigitals' => $produkDigitals
        ]);
    }
}
