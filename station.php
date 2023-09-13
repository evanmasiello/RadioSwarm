<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Stations - Radio Swarm</title>
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
<h2>Radio Swarm Stations</h2>
<br>
<form id="editStation" method="get" action="station">
<input id="inputNum" name="station" type="number" value="0" onchange="changeNum()"> <input type="submit" value="View">
</form>
<br>
<br>
<h2>Name: <span id="sName"></span></h2>
<h4>Desc: <span id="sDesc"></span></h4>
<h3>By: <span id="sUser"></span></h3>
<h3><a id="listenLink">Listen</a></h3>
<h3><a id="editLink">Edit</a></h3>
<br>
<h2>Here are all of the songs:</h2>
<table id="stationsTable">
</table>
</div>
<script>

var queryString = window.location.search;

var urlParams = new URLSearchParams(queryString);

var stationStart = 0;

if (queryString.length > 0) {
    stationStart = parseInt(urlParams.get('station'));   
} else {
    stationStart = 0;
}

document.getElementById("inputNum").value = stationStart;

var users = [];

var stations = [];

var songs = [];

getStations();

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
                
                populatTable();
                
            }
        }
    };
    
    xmlhttp.send(formData);

}

function getUsers() {
    var xmlhttp = new XMLHttpRequest();

    var getFile = 'getUsers.php?_=' + Date.now();

    xmlhttp.open('GET', getFile, true);

    xmlhttp.onreadystatechange = function() {
        
        console.log("state change users");
        
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                
                //console.log("server response users: " + xmlhttp.responseText);
                
                users = eval(xmlhttp.responseText);
                
                getSongs();
                
            }
        }
    };
    
    xmlhttp.send();

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
                
                getUsers();
                
                stations = eval(xmlhttp.responseText);
                
            }
        }
    };
    
    xmlhttp.send();

}

var station;

function populatTable() {
    
for (var i=0; i < stations.length; i++) {
    if (stations[i].id == stationStart) station = stations[i];
}

document.getElementById("sName").innerHTML = station.name;
document.getElementById("sDesc").innerHTML = station.desc;
document.getElementById("sUser").innerHTML = "<a href='./user?user=" + users[station.userID].user + "'> " + users[station.userID].user + "</a>";

document.getElementById("listenLink").href = "listen?station=" + station.id;
document.getElementById("editLink").href = "edit-station?station=" + station.id;
    
 var html = "";
 html += '<tr>';
  html += '<th>' + "Title" + '</th>';
  html += '<th>' + "Artist" + '</th>';
  html += '<th>' + "Link" + '</th>';
  html += '<th>' + "Length" + '</th>';
 html += '</tr>';
//console.log("length " + stations.length);
 for( var i = 0; i < songs.length; i++) {
  html += '<tr>';
 html += '<td>';
 html += songs[i].name;
 html += '</td>';
 html += '<td>';
  //console.log("title " + stations[i].title)
  html += songs[i].artist;
 html += '</td>';
 html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += "<a href='https://www.youtube.com/watch?v=" + songs[i].url + "' target='_blank'>Song</a>";
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
  
  var Mins = Math.floor(songs[i].length / 60);
        
 var Secs = songs[i].length % 60;
 
 if (Secs < 10) Secs = "0" + Secs;
  
  html += Mins + ":" + Secs;
 html += '</td>';
  html += '</tr>';
 }
console.log("html " + html);
document.getElementById('stationsTable').innerHTML = html;
}
</script>
<?php include "upArrow.html" ?>
</body>
</html>