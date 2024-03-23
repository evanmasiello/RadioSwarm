<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Radio Swarm - Listen</title>
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
<h2>Listen to a radio station</h2>

<form id="newStation" method="get" action="listen.php">
<input id="inputNum" name="station" type="number" value="0" onchange="changeNum()"> <input type="submit" value="Connect">
</form>

<br><br>

<h3>Connected to: <span id="stationTitle"></span></h3>
<h4>About: <span id="stationDesc"></span></h4>
<h4>By: <span id="stationOwner"></span></h4>

<br><br>
<div id="player">
<iframe id="ytVid" width="560" height="315" src="https://www.youtube.com/embed/r90xDchufXE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
<br>
<div id="volumeBar" style="width: 50vw;">
<input id="volSlider" class="range-slider" type="range" min="0" max="100" value="100" style="" oninput="valueChanged(this)"><i id="volIcon" class="fa fa-volume-up"></i>
</div>
<br>

<h3>Listening to: <span id="title"></span></h3>
<h4>By: <span id="artist"></span></h4>
</div>
<script>

  const inputElement = document.querySelector('[type="range"]');
let isChanging = false;

const setCSSProperty = () => {
  const percent =
    ((inputElement.value - inputElement.min) /
    (inputElement.max - inputElement.min)) *
    100;
  // Here comes the magic 🦄🌈
  inputElement.style.setProperty("--webkitProgressPercent", `${percent}%`);
}

// Set event listeners
const handleMove = () => {
  if (!isChanging) return;
  setCSSProperty();
};
const handleUpAndLeave = () => isChanging = false;
const handleDown = () => isChanging = true;

inputElement.addEventListener("mousemove", handleMove);
inputElement.addEventListener("mousedown", handleDown);
inputElement.addEventListener("mouseup", handleUpAndLeave);
inputElement.addEventListener("mouseleave", handleUpAndLeave);
inputElement.addEventListener("click", setCSSProperty);

var slider = document.getElementById("volSlider");

// Init input
setCSSProperty();
  
 /* document.getElementById("volume").slider({
  	min: 0,
  	max: 100,
  	value: 0,
		range: "min",
  	slide: function(event, ui) {
    	setVolumeSlider(ui.value);
  	}
	}); */
  
function valueChanged(e){
//	let a = e.value;
	//e.style.background = 'linear-gradient(to right, black, grey ${a}%,#eee ${a}%)';
    setVolumeSlider();
    
    //console.log("value is: " +  e.value);
}
  
  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
  
  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  
    function setVolumeSlider() {
    //console.log("new vol " + slider.value)
    player.setVolume(slider.value);
    setCookie('volume', slider.value, 1);
    setCSSProperty();
    
    if (slider.value > 50) {
      //console.log("value is over 50");
      document.getElementById("volIcon").classList.remove('fa-volume-down');
      document.getElementById("volIcon").classList.add('fa-volume-up');
    } else {
      document.getElementById("volIcon").classList.remove('fa-volume-up');
      document.getElementById("volIcon").classList.add('fa-volume-down');
    }
    
  }

var tag = document.createElement('script');

  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // 3. This function creates an <iframe> (and YouTube player)
  //    after the API code downloads.
  //playsinline=1&controls=0&autoplay=1&allowfullscreen=0
  var player;
  
  var isPlaying = false;
  
  var isMobile = false;

  function onYouTubeIframeAPIReady() {
      if (isMobile) {
          player = new YT.Player('player', {
              height: '480',
              width: '720',
              videoId: 'r90xDchufXE',
              playerVars: {
                  'autoplay': 0,
                  'controls': 1,
                  'allowfullscreen': 1,
                  'playsinline': 1,
                  'modestbranding': 1
              },
              events: {
                  'onReady': onPlayerReady,
                  'onStateChange': onPlayerStateChange
              }
          });
        } else {
          player = new YT.Player('player', {
              height: '480',
              width: '720',
              videoId: 'r90xDchufXE',
              playerVars: {
                  'autoplay': 0,
                  'controls': 0,
                  'allowfullscreen': 0,
                  'playsinline': 1,
                  'modestbranding': 1
              },
              events: {
                  'onReady': onPlayerReady,
                  'onStateChange': onPlayerStateChange
              }
          });
      }
  }

  function onPlayerReady(event) {
      
      var volCookie = getCookie('volume');
      slider.value = parseInt(volCookie);
      player.setVolume(slider.value);
      document.getElementById("player").style.border = "inset";
      setCookie('volume', slider.value, 1);
      setCSSProperty();
    
    if (slider.value > 50) {
      //console.log("value is over 50");
      document.getElementById("volIcon").classList.remove('fa-volume-down');
      document.getElementById("volIcon").classList.add('fa-volume-up');
    } else {
      document.getElementById("volIcon").classList.remove('fa-volume-up');
      document.getElementById("volIcon").classList.add('fa-volume-down');
    }
      
      //var volCookie = getCookie('volume');
      //slider.value = parseInt(volCookie);
      //player.setVolume(slider.value);
      //document.getElementById("player").style.border = "inset";
      //setCookie('volume', slider.value, 1);
      //setCSSProperty();
    
    //if (slider.value > 50) {
      //document.getElementById("volIcon").classList.add('fa-volume-up');
      //document.getElementById("volIcon").classList.remove('fa-voulume-down');
    //} else {
    //  document.getElementById("volIcon").classList.remove('fa-volume-up');
      //document.getElementById("volIcon").classList.add('fa-voulume-down');
    //}
    
      connectToRadio(stationStart);
      //event.target.playVideo();
      //document.getElementById("music").display = 'none';
  }

  // 5. The API calls this function when the player's state changes.
  //    The function indicates that when playing a video (state=1),
  //    the player should play for six seconds and then stop.
  var done = false;

  function onPlayerStateChange(event) {
    //console.log("is playing var: " + isPlaying);
    //console.log("is playing: " + event.data == YT.PlayerState.PLAYING);
    //if (event.data != YT.PlayerState.PAUSED && event.data != YT.PlayerState.CUED && event.data != -1 && event.data != YT.PlayerState.BUFFERING && isPlaying == false) {
    //    console.log("in the mud!!!")
    //    isPlaying = true;
    //    getDataOnServer(fileName);
    //}
    if (event.data == YT.PlayerState.PLAYING || event.data == YT.PlayerState.ENDED || event.data == YT.PlayerState.BUFFERING) {
      isPlaying = true;
    } else {
      isPlaying = false;
    }
  }

  function stopVideo() {
      player.stopVideo();
  }
  

var songArray = [];

var urlTimeout;

var users = [];

var stations = [];

getStations();

function changeNum() {
    var stationNum = document.getElementById("inputNum").value;
    stationStart = document.getElementById("inputNum").value;
    
    var urlParams = new URLSearchParams();
    
    urlParams.set('station', stationNum);
    
    //window.location.search = urlParams.toString();
    
    document.getElementById("newStation").action = "listen.php";
    
    console.log("changing num");
}

function reconnect() {
    //clearTimeout(urlTimeout);
    
    var stationNum = document.getElementById("inputNum").value;
    stationStart = document.getElementById("inputNum").value;
    
    updateDisplay(stationStart);
    
    urlParams.set('station', stationNum);
    
    //window.location.search = urlParams.toString();
    
    //urlTimeout = setTimeout(function () {window.location.search = urlParams.toString();}, 1000);
    
    connectToRadio(stationNum);
}

var needToUpdate = true;

function updateDisplay(id) {
    
    console.log("stations length: " + stations.length);
    console.log("users length: " + users.length);
    
    if (stations.length == 0 || users.length == 0) {
        needToUpdate = true;
    } else {
        needToUpdate = false;
    }
    
    var stationCurrent;
    
    for (var i=0; i < stations.length; i++) {
        if (stations[i].id == id) stationCurrent = stations[i];
    }
    
    console.log("userID: " + stationCurrent.userID);
    
    var user = users[stationCurrent.userID];
    
    document.getElementById("stationTitle").innerHTML = stationCurrent.name;
    document.getElementById("stationDesc").innerHTML = stationCurrent.desc;
    document.getElementById("stationOwner").innerHTML = user.user;

}

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
                
                updateDisplay(stationStart);
                
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

function connectToRadio(id) {
    
    var formDataFeed = new FormData();
    formDataFeed.append('stationID', id);

    var xmlhttp = new XMLHttpRequest();

    var getFile = 'listenProgram.php?_=' + Date.now();

    xmlhttp.open('POST', getFile, true);

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                
                console.log("server response: " + xmlhttp.responseText);
                
                songArray = eval(xmlhttp.responseText);
                
                console.log("song array length: " + songArray.length);
                
                var time = Math.floor(Date.now() / 1000);
                
                for (var i=0; i < songArray.length; i++) {
                    console.log("object #" + i + " :" + songArray[i]);
                    console.log("object #" + i + " name:" + songArray[i].name);
                    console.log("object #" + i + " artist:" + songArray[i].artist);
                    console.log("object #" + i + " url:" + songArray[i].url);
                    console.log("object #" + i + " length:" + songArray[i].length);
                    console.log("object #" + i + " playtime:" + songArray[i].playtime);
                    
                    if (songArray[i].playtime < time) songIndex = i;
                }
                
                clearTimeout(songTimeout);
                
                playSongs();
                
            }
        }
    };
    
    xmlhttp.send(formDataFeed);
    
    if (needToUpdate) getStations();
}

var songIndex = 0;
var songTimeout;

function playSongs() {
    
    //if (songIndex == 1) updateDisplay(stationStart);
    
    if (needToUpdate) getStations();
    
    var time = Math.floor(Date.now() / 1000);
    
    if (songIndex >= songArray.length) songIndex = 0;
    
    console.log("song url: " + songArray[songIndex].url);
    
    var delay = time - songArray[songIndex].playtime;
    
    if (isPlaying) {
      player.loadVideoById({videoId: songArray[songIndex].url, startSeconds: delay});
    } else {
      veryFirstRun = false;
      player.cueVideoById({videoId: songArray[songIndex].url, startSeconds: delay});
    }
    
    //document.getElementById("ytVid").src = "https://www.youtube.com/embed/" + songArray[songIndex].url + "?autoplay=1&start=" + String(time - songArray[songIndex].playtime);
    
    document.getElementById("title").innerHTML = songArray[songIndex].name;
    document.getElementById("artist").innerHTML = songArray[songIndex].artist;
    
    //console.log("wait time: " + String(songArray[songIndex].length * 1000));
    
    if (delay < 0) {
        songIndex --;
        
        if (songIndex < 0) songIndex = 0;
        
        connectToRadio(stationStart);
    }
    
    console.log("you are " + String(delay) + " seconds behind");
    
    if (songIndex == (songArray.length - 1)) {
        songTimeout = setTimeout(reconnect, (songArray[songIndex].length * 1000) - (delay * 1000));
        console.log("Switch wait time: " + String((songArray[songIndex].length * 1000) - (delay * 1000)));
    } else {
        songTimeout = setTimeout(playSongs, (songArray[songIndex].length * 1000) - (delay * 1000));
        console.log("wait time: " + String((songArray[songIndex].length * 1000) - (delay * 1000)));
    }
    
    songIndex++;
}

//check for url variable

var queryString = window.location.search;

var urlParams = new URLSearchParams(queryString);

var stationStart = 0;

if (queryString.length > 0) {
    stationStart = parseInt(urlParams.get('station'));   
} else {
    stationStart = 0;
}

document.getElementById("inputNum").value = stationStart;

//setTimeout(function () { connectToRadio(stationStart);}, 1000);

</script>
<?php include "upArrow.html" ?>
</body>
</html>