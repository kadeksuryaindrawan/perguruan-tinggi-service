@extends('layouts.app')

@section('content')

        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="mb-sm-4 d-flex flex-wrap align-items-center text-head">
					<h2 class="font-w600 mb-2 me-auto">Program Bantuan</h2>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body row align-items-center">
                                <div class="col-lg-6">
                                    <div class="icon me-3">
                                        <img src="{{ asset('assets/images/search.svg') }}" alt="" width="80%">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <h2 class="invoice-num">Rekomendasi Program Bantuan</h2>
                                        <p>Dapatkan rekomendasi program bantuan dari kami dengan input potensi ataupun permasalahan dari desa.</p>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal"><button class="btn btn-sm btn-primary">Dapatkan Rekomendasi Program Bantuan</button></a>
                                    </div>
                                </div>
							</div>
						</div>
					</div>

                    <div class="col-lg-12">
                        <!-- Modal untuk Mendapatkan Rekomendasi -->
                        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="searchModalLabel">Dapatkan Rekomendasi Program Bantuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="rekomendasiForm" action="{{ url('rekomendasi-bantuan?id='.$userId) }}" method="POST">
                                            @csrf
                                            <div class="row mb-5">
                                                <div class="col-lg-8">
                                                    <label for="searchInput" class="form-label">Input Potensi atau Permasalahan</label>
                                                    <input type="text" name="inputTeks" class="form-control" id="searchInput" placeholder="Potensi atau permasalahan desa" required>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for=""></label>
                                                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top: 35px;">Dapatkan Rekomendasi</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Daftar Bantuan -->
                                        <h5>Daftar Desa Yang Memerlukan Bantuan</h5>
                                        <ul class="list-group">
                                            @foreach ($permasalahans as $permasalahan)
                                                <li class="list-group-item">
                                                    <span>Desa : {{ ucwords($permasalahan->desa->desa) }}</span><br>
                                                    <span>Potensi : {{ ucfirst($permasalahan->potensi) }}</span><br>
                                                    <span>Permasalahan : {{ ucfirst($permasalahan->permasalahan) }}</span><br><br>
                                                    <form class="rekomendasi-form" action="{{ url('rekomendasi-bantuan?id='.$userId) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="permasalahan_id" value="{{ $permasalahan->id }}">
                                                        <input type="hidden" name="potensi" value="{{ $permasalahan->potensi }}">
                                                        <input type="hidden" name="permasalahan" value="{{ $permasalahan->permasalahan }}">
                                                        <button type="submit" class="btn btn-sm btn-secondary">Dapatkan Rekomendasi</button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal untuk Menampilkan Hasil Rekomendasi -->
                        <div class="modal fade" id="rekomendasiModal" tabindex="-1" aria-labelledby="rekomendasiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rekomendasiModalLabel">Rekomendasi Bantuan Yang Diberikan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4>Punya Program Bantuan Sendiri?</h4>
                                        <a id="inputProgramBantuanLink"><button class="btn btn-warning btn-sm">Input Program Bantuan</button></a>
                                        <h4 class="mt-5">Daftar Rekomendasi</h4>
                                        <!-- Data Input -->
                                        <div id="dataInput"></div>
                                        <!-- Rekomendasi will be inserted here -->
                                        <div id="rekomendasiContent"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const forms = document.querySelectorAll('.rekomendasi-form, #rekomendasiForm');
                forms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        const formData = new FormData(this);

                        fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            displayRekomendasi(data.inputTeksGabungan, data.permasalahanId,data.rekomendasiArray);
                        })
                        .catch(error => console.error('Error:', error));
                    });
                });

                function displayRekomendasi(inputTeksGabungan, permasalahanId, rekomendasiArray) {
                    let dataInputContent = `<p>Data Input: ${inputTeksGabungan}</p>`;
                    document.getElementById('dataInput').innerHTML = dataInputContent;
                    var userId = '{{ $userId }}';
                    let content = '';

                    if (rekomendasiArray.length > 0 && rekomendasiArray[0].cosine_similarity != 0) {
                        rekomendasiArray.forEach(rekomendasi => {
                            content += `<p>Id histori: ${rekomendasi.id}</p>`;
                            content += `<p>Rekomendasi Potensi: ${rekomendasi.potensi}</p>`;
                            content += `<p>Rekomendasi Permasalahan: ${rekomendasi.permasalahan}</p>`;
                            content += `<p>Rekomendasi Program Bantuan: ${rekomendasi.bantuan}</p>`;
                            content += `<p>Nilai Cosine Similarity: ${rekomendasi.cosine_similarity}</p>`;
                            content += `<p>Persentase Kesamaan: ${cosineSimilarityToPercentage(rekomendasi.cosine_similarity)}%</p>`;
                            content += `<a href="{{ url('pilih-rekomendasi?id=') }}${userId}&id_history=${rekomendasi.id}&permasalahan_id=${permasalahanId}" class="btn btn-sm btn-primary">Pilih Rekomendasi</a>`;
                            content += `<hr>`;
                        });
                    } else {
                        content = '<p>Tidak ada data tersebut!</p>';
                    }

                    document.getElementById('rekomendasiContent').innerHTML = content;

                    // Update href for "Input Program Bantuan" link
                    document.getElementById('inputProgramBantuanLink').href = `{{ url('pilih-rekomendasi?id=') }}${userId}&permasalahan_id=${permasalahanId}`;

                    const rekomendasiModal = new bootstrap.Modal(document.getElementById('rekomendasiModal'));
                    rekomendasiModal.show();

                    // Tambahkan event listener untuk tombol "Pilih Rekomendasi"
                    document.querySelectorAll('.pilih-rekomendasi').forEach(button => {
                        button.addEventListener('click', function() {
                            const idHistori = this.getAttribute('data-id');
                            pilihRekomendasi(idHistori);
                        });
                    });
                }

                function cosineSimilarityToPercentage(similarity) {
                    return (similarity * 100).toFixed(2);
                }
            });
        </script>

@endsection
