<?php
include('connect.php');
$conn = Connect();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$IMAGE = $_POST['IMAGE'];
$path = "./images/image.jpg";
$actualpath = gethostbyname(gethostname()).$path;
file_put_contents($path,base64_decode($IMAGE));
$url = 'https://westus.api.cognitive.microsoft.com/emotion/v1.0/recognize';

$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\nOcp-Apim-Subscription-Key:c234aec7b26649828f02bce602492e07",
        'method'  => 'POST',
        'content' => '{ "url": "http://139.59.29.24/images/image.jpg" }'
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
while ($result === FALSE) { 
	$result = file_get_contents($url, false, $context);
}

$resultArray = json_decode($result, true);
$n = count($resultArray);
$x = 0;
$date = date_create();
$timestamp = date_timestamp_get($date);
$datetime = date('Y-m-d H:i:s', $timestamp);

while($x < $n) {
	$anger = $resultArray[$x]["scores"]["anger"];
	$contempt = $resultArray[$x]["scores"]["contempt"];
	$disgust = $resultArray[$x]["scores"]["disgust"];
	$fear = $resultArray[$x]["scores"]["fear"];
	$happiness = $resultArray[$x]["scores"]["happiness"];
	$neutral = $resultArray[$x]["scores"]["neutral"];
	$sadness = $resultArray[$x]["scores"]["sadness"];
	$surprise = $resultArray[$x]["scores"]["surprise"];
	$x++;
	$sql = "INSERT INTO images VALUES ('$x','$anger','$contempt','$disgust','$fear',
	'$happiness','$neutral','$sadness','$surprise','$datetime')";

	$stmt = $conn->query($sql);
	if(!($conn->affected_rows == 1)){
  		echo $conn->error;
	};
}
?>
