<?php
// API Key dan URL BPS
$apiKey = "77e79689c8590886c805c6d476d6d707";
$apiUrl = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/3200/var/397/th/118/key/" . $apiKey;

// Fungsi untuk mengambil data menggunakan cURL
function getBpsData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Lewati verifikasi SSL jika di localhost
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Eksekusi pengambilan data
$dataBps = getBpsData($apiUrl);

// Menyiapkan variabel yang akan dipanggil di dashboard
$judulData = isset($dataBps['var'][0]['label']) ? $dataBps['var'][0]['label'] : "Statistik Jawa Barat";
?>