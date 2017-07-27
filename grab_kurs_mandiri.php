<?php
// grabbing kurs bank mandiri
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com


function fungsiCurl($url){
     $data = curl_init();
     curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($data, CURLOPT_URL, $url);
	 curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
     $hasil = curl_exec($data);
     curl_close($data);
     return $hasil;
}
$url = fungsiCurl('http://www.bankmandiri.co.id/resource/kurs.asp');
$pecah_last_update_1 = explode('<p class="catatan">', $url);
$pecah_last_update_2 = explode('<br>', $pecah_last_update_1[1]);

$pecah_1 = explode('<table class="tbl-view" cellpadding="0" cellspacing="0" border="0" width="100%">', $url);
$pecah_2 = explode('</table>', $pecah_1[1]);
$clean = str_replace('&nbsp;', '', str_replace('<tr class="zebra">', '<tr>', str_replace('<td bgcolor="#ffffff">', '<td>', str_replace('<td align="right">', '<td>', str_replace('<td bgcolor="#eeeeee">', '<td>', $pecah_2[0])))));


$pecah_baris = str_replace('</tr>', '', str_replace('</td>', '', explode('<tr>', $clean)));

$pecah_kolom = array();
for ($i=2; $i < count($pecah_baris); $i++) { 
	$pecah_kolom[] = explode('<td>', str_replace('</td>', '', $pecah_baris[$i]));
}


$array = array();
$count = count($pecah_kolom);
for ($i=0; $i < $count ; $i++) { 
	$array[] = array(
		'mata_uang' => trim(strip_tags($pecah_kolom[$i][1])),
		'symbol'    => trim($pecah_kolom[$i][2]),
		'kurs_beli' => trim($pecah_kolom[$i][3]),
		'kurs_jual' => trim($pecah_kolom[$i][5])
	);

}

$array_result = array(
		'Grab_Create_By'   => 'Hamid Wahyudin',
		'Last_Update_Date' => substr($pecah_last_update_2[0], 14),
		'Bank_Rate'        => 'Bank Indonesia',
		'Data_Rate'        => $array
	);

header("Content-type:application/json");
echo str_replace('\r', '', str_replace('\n', '', json_encode($array_result)));
?>