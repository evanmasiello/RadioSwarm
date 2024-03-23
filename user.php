<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Users - Radio Swarm</title>
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
<h2>Radio Swarm User</h2>
<br>
<form id="viewUser" method="get" action="user.php">
<input id="inputUser" name="user" type="text" placeholder="Username"> <input type="submit" value="View">
</form>
<h3><a href="./edit-user.php">Edit Account</a></h3>
<br>
<h2>Name: <span id="uName"></span></h2>
<h4>Date Joined: <span id="uDate"></span></h4>
<br>
<h2>Here are all of their stations:</h2>
<table id="stationsTable">
</table>
<div>
<script>

var queryString = window.location.search;

var urlParams = new URLSearchParams(queryString);

var user = "Big Mas";

if (queryString.length > 0) {
    user = urlParams.get('user');
} else {
    user = "Big Mas";
}

document.getElementById("inputUser").value = user;

console.log("User is: " + user);

var users = [];

var stations = [];

var songs = [];

getStations();

function getSongs() {
    var xmlhttp = new XMLHttpRequest();

    var getFile = 'getStationsSongs.php?_=' + Date.now();
    
    var formData = new FormData();
    
    formData.append("station", user);

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

var userObj;

function populatTable() {
    
for (var i=0; i < users.length; i++) {
    if (users[i].user.toLowerCase() == user.toLowerCase()) userObj = users[i];
}

var userStations = [];

for (var i=0; i < stations.length; i++) {
    if (stations[i].userID == userObj.id) {
        userStations.push(stations[i]);
        
        console.log("In if");
    }
}


  var dateObject = new Date(userObj.time * 1000);
  
  var dateString = dateObject.toLocaleString();

document.getElementById("uName").innerHTML = userObj.user;
document.getElementById("uDate").innerHTML = dateString.substring(0, dateString.indexOf(","));
    
 var html = "";
 html += '<tr>';
  html += '<th>' + "ID" + '</th>';
  html += '<th>' + "Title" + '</th>';
  html += '<th>' + "Description" + '</th>';
  html += '<th>' + "Connect Link" + '</th>';
  html += '<th>' + "Edit Link" + '</th>';
 html += '</tr>';
//console.log("length " + stations.length);
 for( var i = 0; i < userStations.length; i++) {
  html += '<tr>';
 html += '<td>';
 html += "<a href='station.php?station=" + userStations[i].id + "'>" + userStations[i].id + "</a>";
 html += '</td>';
 html += '<td>';
  //console.log("title " + stations[i].title)
  html += userStations[i].name;
 html += '</td>';
 html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += userStations[i].desc;
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += "<a href='./listen.php?station=" + userStations[i].id + "'>Connect";
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
  html += "<a href='./edit-station.php?station=" + userStations[i].id + "'>Edit";
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