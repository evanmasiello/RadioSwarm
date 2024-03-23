<?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["rid"]) and isset($_POST["name"]) and isset($_POST["desc"]) and isset($_POST["uname"]) and isset($_POST["pword"]) and isset($_POST["shuff"]) and isset($_POST["songN1"]) and isset($_POST["songU1"]) and isset($_POST["songM1"]) and isset($_POST["songS1"]) and isset($_POST["oldID"]) and isset($_POST["songCount"])) {
        
        echo "in the if";
        
        $statusFile = "status.txt";
    
        $dataStatus = file_get_contents($statusFile);
    
        while ($dataStatus == "OPEN") {
            sleep(2);
        }
    
        file_put_contents($statusFile, "OPEN");
          
        echo "<h1>It worked</h1>";
          
        $canProceed = true;
          
        $nameStations = "stations";
        $file_nameStations = $nameStations . '.json';
       
        $locationStations = $file_nameStations;

        $nameUsers = "users";
        $file_nameUsers = $nameUsers . '.json';
       
        $locationUsers = $file_nameUsers;
   
        $rid = htmlspecialchars($_POST['rid']);
        $name = htmlspecialchars($_POST['name']);
        $desc = htmlspecialchars($_POST['desc']);
        $uname = htmlspecialchars($_POST['uname']);
        $pword = hash("sha256", $_POST['pword'], false);
        $shuff = htmlspecialchars($_POST['shuff']);
        $oldID = htmlspecialchars($_POST["oldID"]);
        $songCount = isset($_POST["songCount"]);
        
        //check that value types are correct
            // to do
        //

        $time = strval(round(microtime(true)));
        
        if (file_exists($locationStations)) {
            
            $jsonData = file_get_contents($locationStations);
            
            $jsonArray = json_decode($jsonData);
            
            $stations = $jsonArray;
        
        }
        
        if (file_exists($locationUsers)) {
            
            $jsonDataUsers = file_get_contents($locationUsers);
            
            $jsonArrayUsers = json_decode($jsonDataUsers);
            
            $users = $jsonArrayUsers; //$stations
            
            for ($y = 0; $y < count($users); $y++) {
                if (strtolower($uname) == strtolower($users[$y]->user)) $user = $users[$y];
            }
            
            for ($z = 0; $z < count($stations); $z++) { //$user
             
                if ($stations[$z]->id == $oldID) $station = $stations[$z];
                
            }
            
            if ($station->userID == $user->id && $user->pass == $pword) {
                $canProceed = true;
            } else {
                $canProceed = false;
                
                echo "Password or user is not right";
            }
            
        }
    
        if (file_exists($locationStations)) {
            
            $stationNotExists = true;
            
            for ($i = 0; $i < count($stations); $i++) {
                
              if ($stations[$i]->id == $oldID) {
                  
                  $replaceId = $i;
                  
                  continue;
              }    
            
              if ($stations[$i]->id == $rid or $stations[$i]->name == $name) {
                  
                echo "ID is already used";
                  
                $canProceed = false;
              }
            }
            
            $newStat = Array (
                "id" => $rid,
                "name" => $name,
                "desc" => $desc,
                "userID" => $user->id,
                "shuff" => $shuff,
                "time" => $time,
            );
            
            $stations[$replaceId] = $newStat;
        } else {
            $stations = Array (
                "0" => Array (
                "id" => $rid,
                "name" => $name,
                "desc" => $desc,
                "userID" => $user->id,
                "shuff" => $shuff,
                "time" => $time,
                )
            );
        }
    
        $json = json_encode($stations);
    
        if($canProceed and file_put_contents($locationStations, $json)) {
                echo $file_nameStations .' file created';
        } else {
            echo 'There is some error';
        }
        
        $songN1 = $_POST["songN1"];
        $songA1 = $_POST["songA1"];
        $songU1 = $_POST["songU1"];
        
        $length1 = (60 * intval($_POST["songM1"])) + intval($_POST["songS1"]);
        
        $songsFile = Array (
            "0" => Array (
                "name" => $songN1,
                "artist" => $songA1,
                "url" => $songU1,
                "length" => $length1,
            )
        );
        
        echo "song count " . $songCount;
        
        for ($j = 2; isset($_POST["songN" . $j]); $j++) {
            if (isset($_POST["songN" . $j]) and isset($_POST["songU" . $j]) and isset($_POST["songM" . $j]) and isset($_POST["songA" . $j]) and isset($_POST["songS" . $j]) ) {
                
                $length = (60 * intval($_POST["songM" . $j])) + intval($_POST["songS" . $j]);
                
                $newSong = Array (
                    "name" => $_POST["songN" . $j],
                    "artist" => $_POST["songA" . $j],
                    "url" => $_POST["songU" . $j],
                    "length" => $length,
                );
                array_push($songsFile, $newSong);
            }
        }
        
        $songsJson = json_encode($songsFile);
        
        if($canProceed) {
            
            echo 'Can proceed for songs file';
            
            if( file_put_contents("./songs/songs" . $rid . ".json", $songsJson)) {
                echo 'Created the songs file';
            } else {
                echo 'Songs file failed';
            }
        }
        
    }
    
    echo "can proceed status" . $canProceed;
    
    file_put_contents($statusFile, "CLOSED");
    
    echo "<h1>Hello User, </h1> <p>Welcome to Radio Swarm</p>";
    
?>