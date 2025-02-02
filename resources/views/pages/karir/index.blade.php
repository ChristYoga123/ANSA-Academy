@extends('layouts.app')

@section('content')
    <section class="mentor-positions py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-two__title title-animation">Pilih Bidang Mentor</h2>
                <p class="mt-2">Pilih bidang yang sesuai dengan keahlian dan minat Anda</p>
            </div>

            <div class="row g-4">
                @forelse ($bidangLokerMentor as $bidang)
                    <a href="{{ route('karir.show', $bidang->id) }}" class="col-lg-4 col-md-6 card-bidang">
                        <div class="card h-100 position-card">
                            <div class="card-body">
                                {{-- <span class="badge bg-primary mb-2">{{ $bidang->kategori }}</span> --}}
                                <h3 class="card-title h5 mb-3">{{ $bidang->nama }}</h3>
                                <p class="card-text text-muted mb-3">Mentor</p>

                                {{-- <h6 class="mb-2">Kualifikasi:</h6>
                                <ul class="list-unstyled mb-4">
                                    @foreach ($bidang->lokerMentorBidangKualifikasi as $kualifikasi)
                                        <li class="mb-1">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ $kualifikasi->kualifikasi }}
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        Ditutup: {{ \Carbon\Carbon::parse($bidang->tanggal_tutup)->format('d M Y') }}
                                    </span>
                                    <a href="{{ route('loker-mentor.create', ['bidang' => $bidang->id]) }}"
                                        class="btn btn-primary">
                                        Daftar
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>Maaf!</strong> Belum ada lowongan yang tersedia.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .position-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .position-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .card-bidang:hover {
            /* border color primary and transition */
            border: 1px solid --primary;
            transition: all 0.3s ease;
        }
    </style>
@endsection

@push('scripts')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}', 'Error')
        </script>
    @endif
@endpush
