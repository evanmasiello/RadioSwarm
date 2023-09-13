<?php
    
$stationID = 0;

if (isset($_POST["stationID"])) {
    $stationID = $_POST["stationID"];
}
    
$stationData = file_get_contents("stations.json");
$stations = json_decode($stationData);

for ($i = 0; $i < count($stations); $i++) {
    if ($stations[$i]->id == $stationID) {
        $station = $stations[$i];
    }
}

$shouldUpdate = true;

if (file_exists("./timedsongs/timedSongs" . $stationID . ".json")) {
    $timedData = file_get_contents("./timedsongs/timedSongs" . $stationID . ".json");
    $timed = json_decode($timedData);
    
    $lastElement = $timed[count($timed)-1];
    
    $playlistEndTime = $lastElement->length + $lastElement->playtime;
    
    $currentTime = time();
    
    if ($currentTime < $playlistEndTime) $shouldUpdate = false;
}

if ($shouldUpdate) {
    $songsData = file_get_contents("./songs/songs" . $stationID . ".json");
    $songs = json_decode($songsData);
    
    //echo $songsData;
    
    //echo $songs;
    
    if ($station->shuff == "on") {
        shuffle($songs);
    }
    
    $startTime = time();
    
    for ($x = 0; $x < count($songs); $x++) {
        $songs[$x]->playtime = $startTime;
        $startTime += $songs[$x]->length;
    }
    
    file_put_contents("./timedsongs/timedSongs" . $stationID . ".json", json_encode($songs));
    
    echo json_encode($songs);
    
} else {
    echo file_get_contents("./timedsongs/timedSongs" . $stationID . ".json");
}

?>