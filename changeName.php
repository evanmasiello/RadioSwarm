<?php

    $allGood = false;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["uname"]) and isset($_POST["pword"]) and isset($_POST["unameNew"]) and (strlen($_POST["uname"]) > 0) and (strlen($_POST["pword"]) > 0) and (strlen($_POST["unameNew"]) > 0)) {
        
        $statusFile = "status.txt";
    
        $dataStatus = file_get_contents($statusFile);
    
        while ($dataStatus == "OPEN") {
            sleep(2);
        }
    
        file_put_contents($statusFile, "OPEN");
          
       #echo "<h1>It worked</h1>";
          
        $canProceed = true;

        $nameUsers = "users";
        $file_nameUsers = $nameUsers . '.json';
       
        $locationUsers = $file_nameUsers;
   
        $uname = htmlspecialchars($_POST['uname']);
        $pword = hash("sha256", $_POST['pword'], false);
        $unameNew = htmlspecialchars($_POST['unameNew']);
        
        //check that value types are correct
            // to do
        //
        
        if (file_exists($locationUsers)) {
            
            $jsonDataUsers = file_get_contents($locationUsers);
            
            $jsonArrayUsers = json_decode($jsonDataUsers);
            
            $users = $jsonArrayUsers;
            
            $userNotExists = true;
            
            for ($x = 0; $x < count($jsonArrayUsers); $x++) {
              if (strtolower($users[$x]->user) == strtolower($uname)) {
                  if ($users[$x]->pass == $pword) {
                    $userID = $x;
                  } else {
                    $canProceed = false;
                  }
              }
              
              if (strtolower($users[$x]->user) == strtolower($unameNew)) {
                $canProceed = false;
              }
            }
            
        }
        
        if ($canProceed) {
          $users[$userID]->user = $unameNew;
        }
        
        // encode array to json
        $jsonUsers = json_encode($users);
        
        if($canProceed and file_put_contents($locationUsers, $jsonUsers)) {
           #echo $file_nameUsers .' file created';
           $allGood = true;
        } else {
           #echo 'There is some error';
           $allGood = false;
        }
        
        
    }
    
    file_put_contents($statusFile, "CLOSED");
    
   #echo "<h1>Hello User, </h1> <p>Welcome to Radio Swarm</p>";
   
    if ($allGood) {
        echo '<img src="good.png" style="width: 100%;">';
    } else {
        echo '<img src="bad.png" style="width: 100%;">';
    }
    
?>