<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class ProgramBantuanController extends Controller
{
    public function index()
    {
        return view('programbantuan.index');
    }

    public function rekomendasi_bantuan(Request $request)
    {
        $inputTeksGabungan = $request->inputTeks;

        $dataHistori = $this->data_histori();

        echo "Data Input : " . $inputTeksGabungan;
        echo "<br/>";
        echo "<br/>";

        $jumlahRekomendasi = 5;
        $rekomendasiArray = $this->getMultipleRekomendasiBantuanDanPermasalahan($inputTeksGabungan, $dataHistori, $jumlahRekomendasi);

        //fungsi untuk mengurutkan dari persentase tertinggi
        usort($rekomendasiArray, [$this, 'compareCosineSimilarity']);

        if ($rekomendasiArray[0]['cosine_similarity'] == 0) {
            echo "Tidak ada data tersebut!";
        }
        // Cetak rekomendasi yang didapatkan
        foreach ($rekomendasiArray as $rekomendasi) {

            // Cek apakah nilai cosine similarity adalah 0 sebelum mencetaknya
            if ($rekomendasi["cosine_similarity"] != 0) {
                echo "Id histori: " . $rekomendasi["id"] . "<br>";
                echo "Rekomendasi Potensi: " . $rekomendasi["potensi"] . "<br>";
                echo "Rekomendasi Permasalahan: " . $rekomendasi["permasalahan"] . "<br>";
                echo "Rekomendasi Program Bantuan: " . $rekomendasi["bantuan"] . "<br>";
                echo "Nilai Cosine Similarity: " . $rekomendasi["cosine_similarity"] . "<br>";
                echo "Persentase Kesamaan: " . $this->cosineSimilarityToPercentage($rekomendasi["cosine_similarity"]) . "%<br>";
            }

            echo "<br>";
        }

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

        // Tahap 2: Tokenisasi (opsional, jika $tokens sudah dalam bentuk kata-kata individual)
        // ...

        // Tahap 3: Penghapusan tanda baca dan karakter khusus menggunakan regex
        $tokens = preg_replace("/[^a-zA-Z0-9\s]/", "", $tokens);

        // Tahap 4: Penghapusan stop words
        $stopWords = array("dan", "atau", "dari", "yang"); // Daftar stop words yang ingin dihapus
        $tokens = array_diff($tokens, $stopWords);

        // Tahap 5: Stemming atau Lemmatization (opsional, tergantung pada kebutuhan)
        $stemmedTokens = array_map([$this, 'simpleStem'], $tokens);
        $tokens = $stemmedTokens;

        // Tahap 6: Pembersihan teks lainnya (opsional, tergantung pada kebutuhan)
        // ...

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
