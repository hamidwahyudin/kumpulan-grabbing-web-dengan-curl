<?php
// grabbing Instagram
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com


function fungsiCurl($username_ig){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL            => "https://www.instagram.com/" . $username_ig . "/",
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
$url = fungsiCurl("jorgelorenzo99"); //USERNAME INSTAGRAM
$pecah_1 = explode('<script type="text/javascript">', $url);
$pecah_2 = explode('</script>', $pecah_1[4]);
$clean = str_replace('window._sharedData = ', '', $pecah_2[0]);
$clean2 = str_replace(';', '', $clean);
$dt = json_decode($clean2, true);

echo "<pre>";
print_r($dt);
die();
?>