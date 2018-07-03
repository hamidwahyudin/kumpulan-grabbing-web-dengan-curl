<?php
// grabbing Instagram
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com


function fungsiCurl($url){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL            => $url,
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



if(isset($_POST['url'])){
	$DOM = new DOMDocument();

	$id  = ('' != $_POST['url']) ? $_POST['url'] : 1 ;
	$url = fungsiCurl($id);

	$pecah_1 = explode('<script type="text/javascript">', $url);
	$pecah_2 = explode('</script>', $pecah_1[4]);
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
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Instagram Downloader</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<form action="" method="post">
		URL Instagram : 
		<input type="text" name="url">
		<input type="submit" name="Submit">
	</form>


	<?php
	if('' != @$file){
	?>
	<a href="<?php echo $file; ?>?dl=1" title="">Download</a>
	<?php
	}
	?>
</body>
</html>