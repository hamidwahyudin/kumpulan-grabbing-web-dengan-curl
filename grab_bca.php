<?php
// grabbing kurs bank bca
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
$url = fungsiCurl('https://www.bca.co.id/id/Individu/Sarana/Kurs-dan-Suku-Bunga/Kurs-dan-Kalkulator');
$pecah_1 = explode('<table class="table table-bordered">', $url);
$pecah_2 = explode('</table>', $pecah_1[1]);

$pecah_jenis_rate = explode('<th colspan="2">', $url);



$pecah_jenis_rate_1 = str_replace('</th>', '',explode('</tr>', $pecah_jenis_rate[1]));
$pecah_jenis_rate_2 = str_replace('</th>', '',explode('</tr>', $pecah_jenis_rate[2]));
$pecah_jenis_rate_3 = str_replace('</th>', '',explode('</tr>', $pecah_jenis_rate[3]));

$pecah_jenis_rate_1_ = explode('<br />', $pecah_jenis_rate_1[0]);
$pecah_jenis_rate_2_ = explode('<br />', $pecah_jenis_rate_2[0]);
$pecah_jenis_rate_3_ = explode('<br />', $pecah_jenis_rate_3[0]);


$pecah_tbody_1 = explode('<tbody class="text-right">', $pecah_2[0]);
$pecah_tbody_2 = explode('</tbody>', $pecah_tbody_1[1]);

$remove_td_atribute = str_replace('<td style="background-color: white">', '<td>', str_replace('<td align="center" style="background-color: white">', '<td>', $pecah_tbody_2[0]));

$pecah_baris = explode('<tr>', str_replace('</tr>', '', $remove_td_atribute));
$pecah_kolom = array();
for ($i=1; $i < count($pecah_baris); $i++) { 
	$pecah_kolom[] = explode('<td>', str_replace('</td>', '', $pecah_baris[$i]));
}

$array = array();
$count = count($pecah_kolom);
for ($i=0; $i < $count ; $i++) { 
	$array[] = array(
		'mata_uang'  => str_replace(' ', '', $pecah_kolom[$i][1]),
		'nilai'      => '1.00',
		'e_rate'     => array(
							'last_update' => trim($pecah_jenis_rate_1_[1]),
							'kurs'        => array(
												'jual' => str_replace(' ', '', $pecah_kolom[$i][2]),
												'beli' => str_replace(' ', '', $pecah_kolom[$i][3])
											)
						),
		'tt_counter' => array(
							'last_update' => trim($pecah_jenis_rate_2_[1]),
							'kurs'        => array(
												'jual' => str_replace(' ', '', $pecah_kolom[$i][4]),
												'beli' => str_replace(' ', '', $pecah_kolom[$i][5])
											)
						),
		'bank_notes' => array(
							'last_update' => trim($pecah_jenis_rate_3_[1]),
							'kurs'        => array(
												'jual' => str_replace(' ', '', $pecah_kolom[$i][6]),
												'beli' => str_replace(' ', '', $pecah_kolom[$i][7])
											)
						)
	);

}

// print_r($array);

$array_result = array(
		'Grab_Create_By'   => 'Hamid Wahyudin',
		'Bank_Rate'        => 'Bank BCA',
		'Data_Rate'        => $array
	);

header("Content-type:application/json");
echo str_replace('\r', '', str_replace('\n', '', json_encode($array_result)));
?>