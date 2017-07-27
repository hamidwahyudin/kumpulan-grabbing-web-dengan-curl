<?php
// grabbing kurs bank indonesia
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
$url = fungsiCurl('http://www.bi.go.id/id/moneter/informasi-kurs/transaksi-bi/Default.aspx');
$pecah_last_update_1 = explode('<span id="ctl00_PlaceHolderMain_biWebKursTransaksiBI_lblUpdate">', $url);
$pecah_last_update_2 = explode('</span>', $pecah_last_update_1[1]);

$pecah_1 = explode('<table class="table1" cellspacing="0" rules="all" border="1" id="ctl00_PlaceHolderMain_biWebKursTransaksiBI_GridView1" style="border-collapse:collapse;">', $url);
$pecah_2 = explode('</table>', $pecah_1[1]);

$pecah_3 = str_replace('</tr>', '', explode('<tr>', $pecah_2[0]));
$pecah_4 = array();
for ($i=2; $i < count($pecah_3); $i++) { 
	$pecah_4[] =  str_replace(' ', '', str_replace('</td>', '', explode('<td>', str_replace('<td style="text-align:right;">', '<td>', str_replace('<td class="alignRight">', '<td>', $pecah_3[$i])))));

}

$array = array();
$count = count($pecah_4);
for ($i=0; $i < $count ; $i++) { 
	$array[] = array(
		'mata_uang' => $pecah_4[$i][1],
		'nilai'     => $pecah_4[$i][2],
		'kurs_jual' => $pecah_4[$i][3],
		'kurs_beli' => $pecah_4[$i][4]
	);

}

$array_result = array(
		'Grab_Create_By'   => 'Hamid Wahyudin',
		'Last_Update_Date' => $pecah_last_update_2[0],
		'Bank_Rate'        => 'Bank Indonesia',
		'Data_Rate'        => $array
	);

header("Content-type:application/json");
echo json_encode($array_result);
?>