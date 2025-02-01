<?php

namespace App\Http\Controllers;

use App\Models\LokerMentor;
use Illuminate\Http\Request;
use App\Models\LokerMentorBidang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KarirController extends Controller
{
    public $title = 'Karir';

    public function index()
    {
        $bidangLokerMentor = LokerMentorBidang::with('lokerMentorBidangKualifikasi')->whereIsBuka(true)->get();
        return view('pages.karir.index', [
            'title' => $this->title,
            'bidangLokerMentor' => $bidangLokerMentor
        ]);
    }

    public function show($id)
    {
        $lokerMentorBidang = LokerMentorBidang::with('lokerMentorBidangKualifikasi')->whereId($id)->first();
        return view('pages.karir.show', [
            'title' => $this->title,
            'lokerMentorBidang' => $lokerMentorBidang
        ]);
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:loker_mentors,nama',
            'email' => 'required|email|email:dns|unique:loker_mentors,email',
            'no_hp' => 'required|numeric|unique:loker_mentors,no_hp',
            'universitas' => 'required',
            'semester' => 'required|in:6,7,8,9,Fresh Graduate',
            'mahasiswa_berprestasi' => 'nullable|in:Fakultas,Wilayah,Universitas,Nasional',
            'linkedin' => 'required',
            'instagram' => 'required',
            'alasan_mendaftar' => 'required',
            'pencapaian' => 'required|array',
            'drive_portofolio' => 'required',
            'drive_cv' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nama.unique' => 'Nama sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.required' => 'No HP harus diisi',
            'no_hp.numeric' => 'No HP harus angka',
            'no_hp.unique' => 'No HP sudah terdaftar',
            'universitas.required' => 'Universitas harus diisi',
            'semester.required' => 'Semester harus diisi',
            'semester.in' => 'Semester tidak valid',
            'mahasiswa_berprestasi.required' => 'Mahasiswa berprestasi harus diisi',
            'mahasiswa_berprestasi.in' => 'Mahasiswa berprestasi tidak valid',
            'linkedin.required' => 'Linkedin harus diisi',
            'instagram.required' => 'Instagram harus diisi',
            'alasan_mendaftar.required' => 'Alasan mendaftar harus diisi',
            'pencapaian.required' => 'Pencapaian harus diisi',
            'drive_portofolio.required' => 'Drive Portofolio harus diisi',
            'drive_cv.required' => 'Drive CV harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            LokerMentor::create([
                'loker_mentor_bidang_id' => $id,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'universitas' => $request->universitas,
                'semester' => $request->semester,
                'mahasiswa_berprestasi' => $request->mahasiswa_berprestasi,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'alasan_mendaftar' => $request->alasan_mendaftar,
                'pencapaian' => $request->pencapaian,
                'drive_portofolio' => $request->drive_portofolio,
                'drive_cv' => $request->drive_cv,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'redirect' => route('karir.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan'
            ], 500);
        }
    }
}
