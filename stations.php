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
<h2>Radio Swarm Stations</h2>
<br>
<br>
<h2>Here are all of our stations:</h2>
<table id="stationsTable">
</table>
</div>
<script>

var users = [];

var stations = [];

getStations();

function getUsers() {
    var xmlhttp = new XMLHttpRequest();

    var getFile = 'getUsers.php?_=' + Date.now();

    xmlhttp.open('GET', getFile, true);

    xmlhttp.onreadystatechange = function() {
        
        console.log("state change users");
        
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                
                console.log("server response users: " + xmlhttp.responseText);
                
                users = eval(xmlhttp.responseText);
                
                populatTable();
                
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

function populatTable() {
 var html = "";
 html += '<tr>';
  html += '<th>' + "ID" + '</th>';
  html += '<th>' + "Title" + '</th>';
  html += '<th>' + "Description" + '</th>';
  html += '<th>' + "Creator" + '</th>';
  html += '<th>' + "Connect Link" + '</th>';
  html += '<th>' + "Edit Link" + '</th>';
 html += '</tr>';
//console.log("length " + stations.length);
 for( var i = 0; i < stations.length; i++) {
  html += '<tr>';
 html += '<td>';
 html += "<a href='station?station=" + stations[i].id + "'>" + stations[i].id + "</a>";
 html += '</td>';
 html += '<td>';
  //console.log("title " + stations[i].title)
  html += stations[i].name;
 html += '</td>';
 html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += stations[i].desc;
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
    html += "<a href='./user?user=" + users[stations[i].userID].user + "'> " + users[stations[i].userID].user + "</a>";
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += "<a href='./listen?station=" + stations[i].id + "'>Connect";
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += "<a href='./edit-station?station=" + stations[i].id + "'>Edit";
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