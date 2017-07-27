<?php
// grabbing provinsi wikipedia
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com

function fungsiCurl($url){
     $data = curl_init();
     curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($data, CURLOPT_URL, $url);
	 curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	 curl_setopt( $data, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
     $hasil = curl_exec($data);
     curl_close($data);
     return $hasil;
}
$url = fungsiCurl('https://id.wikipedia.org/wiki/Daftar_provinsi_di_Indonesia');
$pecah = explode('<table class="wikitable sortable">', $url);
$pecah2 = explode('</table>', $pecah[1]);
$tabel_provinsi = '<table border="1">' . $pecah2[0] . '</table>';

$bersih_tr1 = str_replace('<th scope="col" width="10%" data-sort-type="date">', '<th>', $pecah2[0]);
$bersih_tr2 = str_replace('<th scope="col" width="10%" data-sort-type="number">', '<th>', $bersih_tr1);
$bersih_tr3 = str_replace('<th scope="col" width="10%">', '<th>', $bersih_tr2);
$bersih_tr4 = str_replace('<th scope="row">', '<td>', $bersih_tr3);

$pecah_baris = explode('<tr>', str_replace('<th>', '<td>', $bersih_tr4));

foreach ($pecah_baris as $key) {
	$bersih1 = explode('<td>', str_replace('</tr>', '', $key));
	$bersih2 = str_replace('</td>', '', $bersih1);
	$bersih3 = str_replace('</th>', '', $bersih2);
	$bersih_x = array();
	foreach ($bersih3 as $key2) {
		preg_match('/src="([^"]+)/i',$key2, $image);
		$bersih_x[] = strip_tags($key2);
	}
	$pecah_baris_lagi[] = array_filter($bersih_x);
}

$array = array();
$count = count($pecah_baris_lagi);
for ($i=2; $i < $count ; $i++) { 
	$array[] = array(
		'pulau' => $pecah_baris_lagi[$i][1], 
		'provinsi' => str_replace('&#160;', '', $pecah_baris_lagi[$i][2]),
		'singkatan_iso' => $pecah_baris_lagi[$i][3],
		'ibukota' => $pecah_baris_lagi[$i][4],
		'diresmikan' => str_replace('&#160;', ' ', $pecah_baris_lagi[$i][5]),
		'populasi_bps_2014' => str_replace('&#160;', '', $pecah_baris_lagi[$i][6]),
		'luas_km' => $pecah_baris_lagi[$i][7],
		'kepadatan_jiwa_perkm' => $pecah_baris_lagi[$i][8],
		'apbd_2014_miliar' => $pecah_baris_lagi[$i][9],
		'pdrb_2014_triliun' => $pecah_baris_lagi[$i][10],
		'pdrb_2014_perkapita_juta' => $pecah_baris_lagi[$i][11],
		'ipm_2014' => $pecah_baris_lagi[$i][12]
	);
}

$array_result = array(
		'Grab_Create_By' => 'Hamid Wahyudin',
		'Source'         => 'wikipedia',
		'Data'           => $array
	);

header("Content-type:application/json");
echo str_replace('\n', '', json_encode($array_result));
?>