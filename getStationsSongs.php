<?php

    if (isset($_POST["station"])) {
        
      echo file_get_contents("./songs/songs" . $_POST["station"] . ".json");
        
      exit; 
      
    }
    
    echo "in file";
    
?>