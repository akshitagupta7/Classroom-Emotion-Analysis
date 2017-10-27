<?php
include('connect.php');
$conn    = Connect();

$sql = "SELECT
CASE greatest(sum(anger) ,sum(contempt) ,sum(disgust) ,sum(fear) ,sum(happiness) ,sum(neutral) ,sum(sadness) ,sum(surprise) )
          WHEN sum(anger)       THEN 'anger'
          WHEN sum(contempt)       THEN 'contempt'
          WHEN sum(disgust)       THEN 'disgust'
          WHEN sum(fear)       THEN 'fear'
          WHEN sum(happiness)       THEN 'happiness'
          WHEN sum(neutral )      THEN 'neutral'
          WHEN sum(sadness )      THEN 'sadness'
          WHEN sum(surprise)       THEN 'surprise'

       END AS emotion
FROM images group by datetime order by datetime desc limit 1";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$emotion_json = json_encode($row);

$sql = "SELECT faceid,
CASE greatest(anger ,contempt ,disgust ,fear ,happiness ,neutral ,sadness ,surprise)
          WHEN anger       THEN 'anger'
          WHEN contempt       THEN 'contempt'
          WHEN disgust       THEN 'disgust'
          WHEN fear       THEN 'fear'
          WHEN happiness       THEN 'happiness'
          WHEN neutral       THEN 'neutral'
          WHEN sadness       THEN 'sadness'
          WHEN surprise       THEN 'surprise'

       END AS emotion FROM images WHERE datetime = (SELECT datetime from images order by datetime desc limit 1)";
$result = $conn->query($sql);
$n = $result->num_rows;
$percentArray = array(
    "anger" => 0.0,
    "contempt" => 0.0,
    "disgust" => 0.0,
    "fear" => 0.0,
    "happiness" => 0.0,
    "neutral" => 0.0,
    "sadness" => 0.0,
    "surprise" => 0.0
);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
  $emotion = $row["emotion"];
  if(strcmp($emotion,"anger")==0){
      $percentArray["anger"]+=1;
  };
  if(strcmp($emotion,"contempt")==0){
      $percentArray["contempt"]+=1;
  };
  if(strcmp($emotion,"disgust")==0){
      $percentArray["disgust"]+=1;
  };
  if(strcmp($emotion,"fear")==0){
      $percentArray["fear"]+=1;
  };
  if(strcmp($emotion,"happiness")==0){
      $percentArray["happiness"]+=1;
  };
  if(strcmp($emotion,"neutral")==0){
      $percentArray["neutral"]+=1;
  };
  if(strcmp($emotion,"sadness")==0){
      $percentArray["sadness"]+=1;
  };
  if(strcmp($emotion,"surprise")==0){
      $percentArray["surprise"]+=1;
  };
  }
}
foreach ($percentArray as $key => $value) {
  $percentArray[$key] /= $n;
  $percentArray[$key] *= 100;
}
$percent_json = json_encode($percentArray);

echo json_encode(array_merge(json_decode($emotion_json, true),json_decode($percent_json, true)))
?>
