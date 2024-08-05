<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\HistoryBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProgramBantuanController extends Controller
{
    public function index()
    {
        $response = Http::get('http://127.0.0.1:8002/api/getAllDataPotensiPermasalahanDesa/');
        $permasalahans = $response->object();
        return view('programbantuan.index',compact('permasalahans'));
    }

    public function pilih_rekomendasi(Request $request)
    {
        $user_id = $request->id;
        $permasalahan_id = $request->permasalahan_id;
        $history_id = $request->id_history;

        if($permasalahan_id != 'null'){
            if($history_id != null){
                $history = Data::find($history_id);
                $response = Http::get('http://127.0.0.1:8002/api/getDataPotensiPermasalahanDesa/' . $permasalahan_id);
                $permasalahan = $response->object();
                return view('programbantuan.pilihrekomendasi', compact('history', 'permasalahan'));
            }else{
                $history = null;
                $response = Http::get('http://127.0.0.1:8002/api/getDataPotensiPermasalahanDesa/' . $permasalahan_id);
                $permasalahan = $response->object();
                return view('programbantuan.pilihrekomendasi', compact('history', 'permasalahan'));
            }

        }else{
            if ($history_id != null) {
                $history = Data::find($history_id);
                $permasalahan = null;
                return view('programbantuan.pilihrekomendasi', compact('history', 'permasalahan'));
            } else {
                $history = null;
                $permasalahan = null;
                return view('programbantuan.pilihrekomendasi', compact('history', 'permasalahan'));
            }

        }
    }

    public function pilih_process(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'desa' => ['required', 'string', 'max:255'],
            'potensi' => ['required'],
            'permasalahan' => ['required'],
            'bantuan' => ['required'],
            'perguruan_tinggi' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $history = HistoryBantuan::create([
                'id_desa' => $request->id_desa,
                'id_permasalahan' => $request->id_permasalahan,
                'user_id' => $id,
                'desa' => $request->desa,
                'potensi' => $request->potensi,
                'permasalahan' => $request->permasalahan,
                'bantuan' => $request->bantuan,
                'perguruan_tinggi' => $request->perguruan_tinggi,
            ]);

            if($history->id_desa != null){
                Http::post('http://127.0.0.1:8002/api/editStatusPermasalahanSudah/' . $request->id_permasalahan);
            }
            return redirect('/history?id=' . $id)->with('success', 'Berhasil simpan data bantuan!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function rekomendasi_bantuan(Request $request)
    {
        if ($request->inputTeks == null) {
            $inputTeksGabungan = $request->potensi . ' ' . $request->permasalahan;
            $permasalahan_id = $request->permasalahan_id;
        } else {
            $inputTeksGabungan = $request->inputTeks;
            $permasalahan_id = null;
        }

        $dataHistori = $this->data_histori();
        $jumlahRekomendasi = 5;
        $rekomendasiArray = $this->getMultipleRekomendasiBantuanDanPermasalahan($inputTeksGabungan, $dataHistori, $jumlahRekomendasi);
        usort($rekomendasiArray, [$this, 'compareCosineSimilarity']);

        // Ambil hanya rekomendasi yang memiliki cosine_similarity lebih dari 0
        $rekomendasiArray = array_filter($rekomendasiArray, function ($rekomendasi) {
            return $rekomendasi['cosine_similarity'] > 0;
        });

        // Batasi jumlah rekomendasi yang ditampilkan
        $rekomendasiArray = array_slice($rekomendasiArray, 0, $jumlahRekomendasi);

        return response()->json([
            'inputTeksGabungan' => $inputTeksGabungan,
            'permasalahanId' => $permasalahan_id,
            'rekomendasiArray' => $rekomendasiArray
        ]);

    }

    public function data_histori()
    {
        $data = Data::all();
        return $data;
    }

    // Fungsi untuk menghitung TF (Term Frequency) dari suatu teks
    function calculateTF($term, $tokens)
    {
        $termFrequency = 0;
        foreach ($tokens as $token) {
            if ($term == $token) {
                $termFrequency++;
            }
        }
        return $termFrequency;
    }

    // Fungsi untuk menghitung DF (Document Frequency) dari suatu term
    function calculateIDF($term, $dataHistori)
    {
        $documentFrequency = 0;
        foreach ($dataHistori as $data) {
            $tokensHistori = array_merge(explode(" ", $data['potensi']), explode(" ", $data['permasalahan']));
            if (in_array($term, $tokensHistori)) {
                $documentFrequency++;
            }
        }
        $totalDataHistori = count($dataHistori);
        $idf = log($totalDataHistori / ($documentFrequency + 1));
        return $idf;
    }

    // Fungsi untuk mendapatkan vektor TF-IDF dari suatu teks
    function getTFIDFVector($tokens, $dataHistori)
    {
        // Preprocessing data: lowercase, tokenisasi, penghapusan tanda baca, dan penghapusan stop words
        $tokens = $this->preprocessText($tokens);
        $tfidfVector = array();
        $totalTerms = count($tokens);
        $uniqueTerms = array_unique($tokens);

        foreach ($uniqueTerms as $term) {
            // Hitung TF dan IDF dari suatu term
            $tf = $this->calculateTF($term, $tokens);
            $idf = $this->calculateIDF($term, $dataHistori);

            // Hitung TF-IDF
            $tfidf = $tf * $idf;

            // Simpan nilai TF-IDF dalam vektor
            $tfidfVector[$term] = $tfidf;
        }

        return $tfidfVector;
    }


    function simpleStem($word)
    {
        // Daftar awalan dan akhiran umum dalam bahasa Indonesia
        $prefixes = array('meng', 'men', 'mem', 'me', 'peng', 'pen', 'pem', 'di', 'ter', 'ke');
        $suffixes = array('kan', 'i', 'an');

        // Proses stemming dengan menghapus awalan dan akhiran yang sesuai
        foreach ($prefixes as $prefix) {
            if (strpos($word, $prefix) === 0) {
                $word = substr($word, strlen($prefix));
                break;
            }
        }

        foreach ($suffixes as $suffix) {
            if (substr($word, -strlen($suffix)) === $suffix) {
                $word = substr($word, 0, -strlen($suffix));
                break;
            }
        }

        return $word;
    }

    function preprocessText($tokens)
    {
        // Tahap 1: Lowercasing
        $tokens = array_map('strtolower', $tokens);

        // Tahap 2: Penghapusan tanda baca dan karakter khusus menggunakan regex
        $tokens = preg_replace("/[^a-zA-Z0-9\s]/", "", $tokens);

        // Tahap 3: Penghapusan stop words
        $stopWords = array("dan", "atau", "dari", "yang"); // Daftar stop words yang ingin dihapus
        $tokens = array_diff($tokens, $stopWords);

        // Tahap 4: Stemming atau Lemmatization (opsional, tergantung pada kebutuhan)
        $stemmedTokens = array_map([$this, 'simpleStem'], $tokens);
        $tokens = $stemmedTokens;

        return $tokens;
    }

    // Fungsi untuk mendapatkan rekomendasi permasalahan dan bantuan berdasarkan data histori
    function getMultipleRekomendasiBantuanDanPermasalahan($inputTeksGabungan, $dataHistori, $jumlahRekomendasi)
    {
        // Tokenisasi input teks gabungan
        $tokensInput = explode(" ", $inputTeksGabungan);

        // Hitung vektor TF-IDF dari input teks gabungan
        $tfidfInputTeks = $this->getTFIDFVector($tokensInput, $dataHistori);

        // Inisialisasi array untuk menyimpan rekomendasi bantuan dan permasalahan terbaik dan nilai cosine similarity terbaik
        $rekomendasiArray = array();

        // Lakukan perhitungan cosine similarity dengan setiap data histori
        foreach ($dataHistori as $data) {
            // Tokenisasi data histori
            $tokensHistori = array_merge(explode(" ", $data['potensi']), explode(" ", $data['permasalahan']));

            // Hitung vektor TF-IDF dari data histori
            $tfidfDataHistori = $this->getTFIDFVector($tokensHistori, $dataHistori);

            // Hitung perkalian dot (inner product) antara vektor data histori dan vektor input teks gabungan
            $dotProduct = 0;
            foreach ($tfidfDataHistori as $term => $tfidfHistori) {
                if (isset($tfidfInputTeks[$term])) {
                    $dotProduct += $tfidfHistori * $tfidfInputTeks[$term];
                }
            }

            // Hitung panjang (norm) vektor data histori dan panjang vektor input teks gabungan
            $normDataHistori = 0;
            $normInputTeks = 0;
            foreach ($tfidfDataHistori as $tfidfHistori) {
                $normDataHistori += pow($tfidfHistori, 2);
            }
            foreach ($tfidfInputTeks as $tfidfInput) {
                $normInputTeks += pow($tfidfInput, 2);
            }
            $normDataHistori = sqrt($normDataHistori);
            $normInputTeks = sqrt($normInputTeks);

            // Hitung nilai cosine similarity
            $cosineSimilarity = $dotProduct / ($normDataHistori * $normInputTeks);

            // Jika array rekomendasi belum mencapai jumlah yang diinginkan, tambahkan rekomendasi baru
            if (count($rekomendasiArray) < $jumlahRekomendasi) {
                $rekomendasiArray[] = array(
                    "id" => $data['id'],
                    "potensi" => $data['potensi'],
                    "bantuan" => $data['bantuan'],
                    "permasalahan" => $data['permasalahan'],
                    "cosine_similarity" => $cosineSimilarity
                );
            } else {
                // Jika jumlah rekomendasi sudah mencapai yang diinginkan, bandingkan dengan rekomendasi yang ada
                foreach ($rekomendasiArray as $key => $rekomendasi) {
                    // Jika cosine similarity lebih tinggi dari rekomendasi yang ada, ganti rekomendasi tersebut
                    if ($cosineSimilarity > $rekomendasi['cosine_similarity']) {
                        $rekomendasiArray[$key] = array(
                            "id" => $data['id'],
                            "potensi" => $data['potensi'],
                            "bantuan" => $data['bantuan'],
                            "permasalahan" => $data['permasalahan'],
                            "cosine_similarity" => $cosineSimilarity
                        );
                        // Urutkan array berdasarkan cosine similarity
                        usort($rekomendasiArray, function ($a, $b) {
                            return $b['cosine_similarity'] <=> $a['cosine_similarity'];
                        });
                        // Hapus rekomendasi dengan nilai cosine similarity terendah jika jumlah melebihi yang diinginkan
                        array_pop($rekomendasiArray);
                        break;
                    }
                }
            }
        }

        // Mengembalikan array berisi rekomendasi bantuan dan permasalahan dalam bentuk array asosiatif
        return $rekomendasiArray;
    }

    function compareCosineSimilarity($a, $b)
    {
        return $b['cosine_similarity'] <=> $a['cosine_similarity'];
    }

    // Fungsi untuk menghitung persentase berdasarkan nilai cosine similarity
    function cosineSimilarityToPercentage($cosineSimilarity)
    {
        return number_format($cosineSimilarity * 100, 2);
    }
}
