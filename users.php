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
<h2>Radio Swarm Users</h2>
<br>
<a href="edit-user.php"><h4>Edit Account</h4></a>
<br>
<h2>Here are all of our users:</h2>
<table id="usersTable">
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

function makeStationsString(id) {
    
}

function populatTable() {
 var html = "";
 html += '<tr>';
  html += '<th>' + "Name" + '</th>';
  html += '<th>' + "Stations" + '</th>';
  html += '<th>' + "Date Joined" + '</th>';
 html += '</tr>';
//console.log("length " + stations.length);
 for (var i = 0; i < users.length; i++) {
  html += '<tr>';
 html += '<td>';
  //console.log("title " + stations[i].title)
  html += "<a href='./user.php?user=" + users[i].user + "'> " + users[i].user + "</a>";
 html += '</td>';
 html += '<td>';
  //console.log("artist " + stations[i].artist)
  
  var stationsString = "";
  //var stationsArr = [];
  
  for (var x=0; x < stations.length; x++) {
      if (stations[x].userID == users[i].id) {
        //stationsArr.push(stations[x]);
        stationsString += " - <a href='./station.php?station=" + stations[x].id + "'>" + stations[x].name + "</a>";
      }
  }
  
  html += stationsString.substring(3);
 html += '</td>';
  html += '<td>';
  //console.log("artist " + stations[i].artist)
  
  var dateObject = new Date(users[i].time * 1000);
  
  var dateString = dateObject.toLocaleString();
  
  html += dateString.substring(0, dateString.indexOf(","));
 html += '</td>';
  html += '</tr>';
 }
console.log("html " + html);
document.getElementById('usersTable').innerHTML = html;
}
</script>
<?php include "upArrow.html" ?>
</body>
</html>