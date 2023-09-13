<?php

    $users = json_decode(file_get_contents("users.json"));

    for ($i=0; $i < count($users); $i++) {
        $users[$i]->pass = "";
    }
    
    echo json_encode($users);
    
    exit;
?>