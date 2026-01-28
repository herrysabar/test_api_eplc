<?php

class Test
{
    function mergeSortArray($a, $b) {
        $result = array();
        $count = 0;

        // 1. Proses Merge Manual (Tanpa array_merge/array_push)
        // Masukkan array a
        foreach ($a as $val) {
            $result[$count] = $val;
            $count++;
        }
        // Masukkan array b
        foreach ($b as $val) {
            $result[$count] = $val;
            $count++;
        }

        // 2. Proses Sorting Manual (Bubble Sort - Ascending)
        // Kita loop sebanyak jumlah data
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count - $i - 1; $j++) {
                // Jika angka kiri lebih besar dari angka kanan, tukar posisi
                if ($result[$j] > $result[$j + 1]) {
                    $temp = $result[$j];
                    $result[$j] = $result[$j + 1];
                    $result[$j + 1] = $temp;
                }
            }
        }

        return $result;
    }

    function getMissingData($arr){
        // Menghitung jumlah elemen array secara manual
        $count = 0;
        foreach($arr as $v) $count++;

        // Mencari selisih awal (Pattern dasar)
        // 23 - 11 = 12. Pattern dimulai dari penambahan 12.
        if ($count < 2) return null; 
        
        $diffPattern = $arr[1] - $arr[0]; 

        for ($i = 0; $i < $count - 1; $i++) {
            // Cek selisih angka sekarang dengan angka berikutnya
            $currentDiff = $arr[$i+1] - $arr[$i];
            
            // Jika selisihnya tidak sama dengan pola yang diharapkan
            if ($currentDiff != $diffPattern) {
                // Kembalikan angka yang seharusnya ada
                return $arr[$i] + $diffPattern;
            }
            
            // Sesuai pola data, penambahan selalu naik 1 angka (12, 13, 14, dst)
            $diffPattern++;
        }
        return null;
    }

    function insertMissingData($arr, $missingData){
        $newResult = array();
        $idx = 0;
        $isInserted = false;

        foreach ($arr as $val) {
            // Jika kita belum memasukkan data hilang, DAN
            // Nilai saat ini lebih besar dari data hilang
            if (!$isInserted && $val > $missingData) {
                $newResult[$idx] = $missingData;
                $idx++;
                $isInserted = true;
            }

            // Masukkan nilai asli array
            $newResult[$idx] = $val;
            $idx++;
        }

        // Jika sampai akhir loop data belum masuk (misal data hilang adalah angka terbesar)
        if (!$isInserted) {
            $newResult[$idx] = $missingData;
        }

        return $newResult;
    }

    public function main(){
        $a = array(11, 36, 65, 135, 98);
        $b = array();
        $b[0] = 81;
        $b[1] = 23;
        $b[2] = 50;
        $b[3] = 155;

        // 1. Merge dan Sort
        $c = $this->mergeSortArray($a, $b);
        
        echo "Hasil Merge & Sort: ";
        print_r($c);
        echo "\n";

        // 2. Cari Data Hilang
        $i = $this->getMissingData($c);
        echo "Angka yang hilang: " . $i . "\n";

        // 3. Masukkan Data Hilang
        $d = $this->insertMissingData($c, $i);
        
        echo "Hasil Akhir Lengkap: ";
        print_r($d);
    }
}

$t = new Test();
$t->main();