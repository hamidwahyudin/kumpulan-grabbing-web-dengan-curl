<?php
// grabbing Instagram
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com


function fungsiCurl($id){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL            => "https://www.instagram.com/p/BeUlmGkjBon/?taken-by=seasiaig",
		//CURLOPT_URL            => "https://www.instagram.com/p/BeUF4_ejpqE/?taken-by=seasiaig",
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

$pecah_1 = explode('<script type="text/javascript">', $url);
$pecah_2 = explode('</script>', $pecah_1[3]);
$clean = str_replace('window._sharedData = ', '', $pecah_2[0]);
$clean2 = str_replace(';', '', $clean);

$dt = json_decode($clean2, true);
/*GET DATA*/
$file = '';
if (1 == $dt['entry_data']['PostPage'][0]['graphql']['shortcode_media']['is_video']) {
	$file = $dt['entry_data']['PostPage'][0]['graphql']['shortcode_media']['video_url'];
} else {
	$file = $dt['entry_data']['PostPage'][0]['graphql']['shortcode_media']['display_url'];
}

$array_result = array(
	'Grab_Create_By' => 'Hamid Wahyudin',
	'Grab_Title'     => 'Instagram Grab',
	'Grab_Data'      => array(
							'isvideo' => $dt['entry_data']['PostPage'][0]['graphql']['shortcode_media']['is_video'],
							'file'    => $file
						)
);

header("Content-type:application/json");
echo str_replace('\r', '', str_replace('\n', '', json_encode($array_result)));
?>