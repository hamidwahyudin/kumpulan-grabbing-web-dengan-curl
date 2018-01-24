<?php
// grabbing Jadwal Sholat
// Created By Hamid Wahyudin
// hamidwahyudin1987@gmail.com


function fungsiCurl($id){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL            => "https://jadwalsholat.org/adzan/monthly.php",
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

$url = fungsiCurl(0);

/*GET DATA*/
$pecah_1 = explode('<select name=kota onChange="change_page()" class="inputcity">', $url);
$pecah_2 = explode('</select>', $pecah_1[1]);
$clean_option = str_replace('selected', '', $pecah_2[0]);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Jadwal Sholat</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<form action="grab_jadwal_sholat.php" method="get">
		Pilih Kota/ Daerah : 
		<select name="id">
			<?php echo $clean_option; ?>
		</select>
		<input type="submit" name="Submit">
	</form>
</body>
</html>