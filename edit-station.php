<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Radio Swarm</title>
    <link href="./css/styleV45.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="./assets/Icon.png">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2258919223240321"
     crossorigin="anonymous"></script>
  <style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php include "heading.html" ?>
<div class="contentBox">
<br>
<form id="editStation" method="get" action="edit-station">
<input id="inputNum" name="station" type="number" value="0" onchange="changeNum()"> <input type="submit" value="Edit">
</form>
<br>
<h2>Edit a radio station</h2>

<form id="stationForm" method="post" action="./editStation.php" target="results">
  <input style="display: none;" type="number" id="oldID" name="oldID"><br>
  <label for="rid">Station ID:</label><br>
  <input type="number" id="rid" name="rid" placeholder="0"><br>
  <label for="name">Station name:</label><br>
  <input type="text" id="name" name="name" placeholder="Station"><br>
  <label for="desc">Description:</label><br>
  <input type="text" id="desc" name="desc" placeholder="For music."><br>
  <label for="uname">Username:</label><br>
  <input type="text" id="uname" name="uname" placeholder="Name"><br>
  <label for="pword">Password:</label><br>
  <input type="password" id="pword" name="pword" placeholder="Password"><br>
  <label for="shuff">Shuffle Songs:</label><br>
  <input type="checkbox" id="shuff" name="shuff" checked><br>
  <div id="songsAll">
  <div id="songs1">
    <label for="songN1">Song Name:</label><br><input type="text" id="songN1" name="songN1" placeholder="Title"><br><label for="songA1">Artist:</label><br><input type="text" id="songA1" name="songA1" placeholder="Artist"><br><label for="songU1">Song Code (Displayed at end of YouTube video URL):</label><br><img src="ytVid.png" style="width: 20%;"><br><input type="text" id="songU1" name="songU1" placeholder="PfYnvDL0Qcw"><br><label for="songM1">Song Length:</label><br><input type="number" id="songM1" name="songM1" placeholder="Minutes"> : <input type="number" id="songS1" name="songS1" placeholder="Seconds"><br><p onclick="deleteSong(1)">Remove Song</p>
  </div>
  </div>
  <br><p id="newSong" onclick="newSong();">Add New Song</p><br>
  <input style="display: none;" type="number" id="songCount" name="songCount">
  <input type="submit" value="Submit">
</form> 

<p>Results will be shown here:</p>
<iframe id="results" name="results"></iframe>

<script>

//make new song not clear form
// When input for songs changes save the info in an array, when a new song is added repopulate the values

var songArray = [];

var songCount = 1;

var songs = [];

var stations = [];

var station;

var queryString = window.location.search;

var urlParams = new URLSearchParams(queryString);

var stationStart = 0;

if (queryString.length > 0) {
    stationStart = parseInt(urlParams.get('station'));   
} else {
    stationStart = 0;
}

document.getElementById("inputNum").value = stationStart;

document.getElementById("oldID").value = stationStart;

getStations();

function changeSongCount() {
    document.getElementById("songCount").value = songCount;
}

function populateForm() {
    
    for (var i=0; i < stations.length; i++) {
        if (stations[i].id == stationStart) station = stations[i];
    }
    
    document.getElementById("rid").value = stationStart;
    
    document.getElementById("name").value = station.name;
    
    document.getElementById("desc").value = station.desc;
    
    document.getElementById("shuff").value = station.shuff;
    
    while (songCount < songs.length) {
        
        songCount++;
        
    
        document.getElementById("songsAll").innerHTML += '<div id="songs' + String(songCount) + '" ><br><label for="songN' + songCount + '">Song Name:</label><br><input type="text" id="songN' + songCount + '" name="songN' + songCount + '"><br><label for="songA' + songCount + '">Artist:</label><br><input type="text" id="songA' + songCount + '" name="songA' + songCount + '"><br><label for="songU' + songCount + '">Song Code:</label><br><input type="text" id="songU' + songCount + '" name="songU' + songCount + '"><br><label for="songM' + songCount + '">Song Length:</label><br><input type="number" id="songM' + songCount + '" name="songM' + songCount + '"> : <input type="number" id="songS' + songCount + '" name="songS' + songCount + '"><br><p onclick="deleteSong(' + songCount + ')">Remove Song</p></div>';
    }
    
    for (var x=0; x < songs.length; x++) {
        
        document.getElementById("songN" + String(x + 1)).value = songs[x].name;
        
        document.getElementById("songA" + String(x + 1)).value = songs[x].artist;
        
        document.getElementById("songU" + String(x + 1)).value = songs[x].url;
        
        document.getElementById("songM" + String(x + 1)).value = Math.floor(songs[x].length / 60);
        
        document.getElementById("songS" + String(x + 1)).value = songs[x].length % 60;
        
    }
    
    changeSongCount();
    
}

function deleteSong(id) {
    
    if (songCount > 1) {
    
        var songToSkip = id;
        
        saveSongInput();
        
        var songCounter = 1;
        
        var hitBadSong = false;
        
        while (songCounter <= songCount) {
            
            if (songCounter == (songToSkip + 1)) {
                hitBadSong = true;
            }
            
            if (!hitBadSong) {
            
                songObject = songArray[songCounter - 1];
            
                document.getElementById("songN"+songCounter).value = songObject.name;
                document.getElementById("songA"+songCounter).value = songObject.artist;
                document.getElementById("songU"+songCounter).value = songObject.url;
                document.getElementById("songM"+songCounter).value = songObject.mins;
                document.getElementById("songS"+songCounter).value = songObject.secs;
                
            } else {
                
                songObject = songArray[songCounter - 1];
            
                document.getElementById("songN"+(songCounter-1)).value = songObject.name;
                document.getElementById("songA"+(songCounter-1)).value = songObject.artist;
                document.getElementById("songU"+(songCounter-1)).value = songObject.url;
                document.getElementById("songM"+(songCounter-1)).value = songObject.mins;
                document.getElementById("songS"+(songCounter-1)).value = songObject.secs;
                
            }
            
            songCounter ++;
        }
        
        removeSong();
    }
}

function getSongs() {
    var xmlhttp = new XMLHttpRequest();

    var getFile = 'getStationsSongs.php?_=' + Date.now();
    
    var formData = new FormData();
    
    formData.append("station", stationStart);

    xmlhttp.open('POST', getFile, true);

    xmlhttp.onreadystatechange = function() {
        
        console.log("state change users");
        
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                
                //console.log("server response users: " + xmlhttp.responseText);
                
                songs = eval(xmlhttp.responseText);
                
                populateForm();
                
            }
        }
    };
    
    xmlhttp.send(formData);

}

function getStations() {
    var xmlhttp = new XMLHttpRequest();

    var getFile = 'getStations.php?_=' + Date.now();

    xmlhttp.open('GET', getFile, true);

    xmlhttp.onreadystatechange = function() {
        
        console.log("state change stations");
        
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                
                console.log("server response stations: " + xmlhttp.responseText);
                
                getSongs();
                
                stations = eval(xmlhttp.responseText);
                
            }
        }
    };
    
    xmlhttp.send();

}

function saveSongInput() {
    
    console.log("saving song input");
    
    var songCounter = 1;
    
    while (songCounter <= songCount) {
        
        songArray[songCounter - 1] = {name:document.getElementById("songN"+songCounter).value, artist:document.getElementById("songA"+songCounter).value, url:document.getElementById("songU"+songCounter).value, mins:document.getElementById("songM"+songCounter).value, secs:document.getElementById("songS"+songCounter).value};
        
        songCounter ++;
    }
    
    console.log("song input length: " + songArray.length);
}

function newSong() {
    
    saveSongInput();
    
    songCount++;
    
    changeSongCount();
    
    document.getElementById("songsAll").innerHTML += '<div id="songs' + String(songCount) + '" ><br><label for="songN' + songCount + '">Song Name:</label><br><input type="text" id="songN' + songCount + '" name="songN' + songCount + '"><br><label for="songA' + songCount + '">Artist:</label><br><input type="text" id="songA' + songCount + '" name="songA' + songCount + '"><br><label for="songU' + songCount + '">Song Code:</label><br><input type="text" id="songU' + songCount + '" name="songU' + songCount + '"><br><label for="songM' + songCount + '">Song Length:</label><br><input type="number" id="songM' + songCount + '" name="songM' + songCount + '"> : <input type="number" id="songS' + songCount + '" name="songS' + songCount + '"><br><p onclick="deleteSong(' + songCount + ')">Remove Song</p></div>';
    
    updateSongValues();
}

function updateSongValues() {
    
    var songCounter = 1;
    
    while (songCounter <= songCount) {
        
        songObject = songArray[songCounter - 1];
        
        document.getElementById("songN"+songCounter).value = songObject.name;
        document.getElementById("songA"+songCounter).value = songObject.artist;
        document.getElementById("songU"+songCounter).value = songObject.url;
        document.getElementById("songM"+songCounter).value = songObject.mins;
        document.getElementById("songS"+songCounter).value = songObject.secs;
        
        songCounter ++;
    }
}

function removeSong() {
    
    if (songCount > 1) {
    
        //var string = '<br><label for="songN' + songCount + '">Song Name:</label><br><input type="text" id="songN' + songCount + '" name="songN' + songCount + '"><br><label for="songA' + songCount + '">Artist:</label><br><input type="text" id="songA' + songCount + '" name="songA' + songCount + '"><br><label for="songU' + songCount + '">Song URL:</label><br><input type="text" id="songU' + songCount + '" name="songU' + songCount + '"><br><label for="songM' + songCount + '">Song Length:</label><br><input type="number" id="songM' + songCount + '" name="songM' + songCount + '"> : <input type="number" id="songS' + songCount + '" name="songS' + songCount + '"><br><div id="songs' + String(songCount + 1) + '" ></div>';
    
        //var htmlString = document.getElementById("songs").innerHTML;
        
        //document.getElementById("songs").innerHTML = htmlString.replace(string,'');
        
        document.getElementById("songs" + String(songCount)).remove();
    
        songCount --;
        
        changeSongCount();
        
    }
    
}
</script>
</div>
<?php include "upArrow.html" ?>
</body>
</html>