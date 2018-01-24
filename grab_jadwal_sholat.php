<?php
// grabbing Jadwal Sholat
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com


function fungsiCurl($id){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL            => "https://jadwalsholat.org/adzan/monthly.php?id=".$id,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING       => "",
		CURLOPT_MAXREDIRS      => 10,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST  => "GET",
		CURLOPT_POSTFIELDS     => "",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	return $response;
}
$DOM = new DOMDocument();

$id  = ('' != $_GET['id']) ? $_GET['id'] : 1 ;
$url = fungsiCurl($id);

/*GET JUDUL*/

$pecah_judul1 = explode('<td colspan="9" align="center">', $url);
$pecah_judul2 = explode('</td>', $pecah_judul1[1]);
$pecah_judul3 = explode('<h1 class="h1_edit">', $pecah_judul2[0]);
$pecah_judul4 = explode('</h1>', $pecah_judul3[1]);
$pecah_judul5 = explode('<h2 class="h2_edit">', $pecah_judul4[1]);

$judul   = trim($pecah_judul4[0]);
$periode = trim(str_replace('</h2>', '', $pecah_judul5[1]));
/*--*/

/* GET DATA*/
$pecah_last_update_1 = explode('<tr class="table_header" align="center">', $url);

$ddd0 = str_replace('class="table_dark" align="center"', '', $pecah_last_update_1[1]);
$ddd1 = str_replace('class="table_light" align="center"', '', $ddd0);
$ddd2 = str_replace('class="table_light" align="center"', '', $ddd1);
$ddd3 = str_replace('class="table_highlight" align="center"', '', $ddd2);
$ddd4 = '<tr>'.str_replace(' ', '', $ddd3);

$pecah_last_update_2 = explode('<trclass="table_block_title"><tdcolspan="9"><b>&nbsp;::Parameter</b></td></tr>', $ddd4);

$ddd5 = str_replace('<b>', '', $pecah_last_update_2[0]);
$ddd6 = str_replace('</b>', '', $ddd5);
$ddd7 = str_replace("\n", '', $ddd6);
$ddd8 = str_replace("</td>", ';</td>', $ddd7);


$DOM->loadHTML($ddd8);

$Detail = $DOM->getElementsByTagName('tr');

//#Get header name of the table
foreach($Detail as $NodeDetail) 
{
	$rr                     = array();
	$explode                = explode(';', trim($NodeDetail->textContent));
	$aDataTableDetailHTML[] = array_filter($explode);
}
/*----*/
$array_result = array(
		'Grab_Create_By' => 'Hamid Wahyudin',
		'Grab_From'      => 'www.jadwalsholat.org',
		'Title'          => $judul,
		'Period'         => $periode,
		'Data'           => $aDataTableDetailHTML
	);
header("Content-type:application/json");
echo json_encode($array_result);
?>