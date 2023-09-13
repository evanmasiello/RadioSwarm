var imageDataUrl = null;
var jsonArray;
var imageHTMl = "";
var imageArray = [];
var likedPosts = [];
var imagesToDisplay = [];
var timeMin = 0;
// JavaScript program to calculate Distance Between
// Two Points on Earth
function error() {
    console.log("Can't get location!");
    document.getElementById("feed").innerHTML = "<h2 class='contentText'>Uh oh! We can't tell where you are. Please allow us to access your location and reload.</h2>";
}

function removeNull(array) {
    return array.filter(x => x !== null);
}

var slider = document.getElementById("mileRange");
var output = document.getElementById("milesDisplay");
output.innerHTML = slider.value/5280 + " mile";

slider.oninput = function() {
  var input = this.value/5280;
  
  /*
  var arrayOfPosts = [];
  
  for (var i=0; i < imageArray.length; i++) {
      if (imageArray[i].distance < input) {
          //console.log("Pushing Post #" + imageArray[i].id);
          arrayOfPosts.push(imageArray[i]);
      }
  }
  */
  
  if (input < 0.5) {
    input = input * 5280;
    if (input == 1) {
      output.innerHTML = Math.round(input) + " foot";
    } else {
      output.innerHTML = Math.round(input) + " feet";
    }
  } else {
    if (input == 1) {
      output.innerHTML = input.toFixed(2) + " mile";
    } else {
      output.innerHTML = input.toFixed(2) + " miles";   
    }
  }
  
  //display(arrayOfPosts);
  filterPosts();
}

function filterPosts() {
  var arrayOfPosts = [];
  
  var dateObjMax = document.getElementById("dateEnd");
  var dateObjMin = document.getElementById("dateStart");
  var distanceSlider = document.getElementById("mileRange");
  
  var distanceInput = distanceSlider.value / 5280;
  var timeMax = (dateObjMax.valueAsDate.getTime() / 1000) + 86400;
  var timeMin = dateObjMin.valueAsDate.getTime() / 1000;
    
  //console.log("distance input: " + distanceInput);
  //console.log("time max: " + timeMax);
  //console.log("time max: " + timeMin);
    
  for (var i=0; i < imageArray.length; i++) {
      
    //console.log("post distance: " + imageArray[i].distance);
    //console.log("post time: " + imageArray[i].time);
    
    if (imageArray[i].distance < distanceInput && imageArray[i].time < timeMax && imageArray[i].time > timeMin) {
      //console.log("Pushing Post #" + imageArray[i].id);
      arrayOfPosts.push(imageArray[i]);
    }
    
  }
  
  display(arrayOfPosts);
}

function distance(lat1, lat2, lon1, lon2) {
    //console.log("lat1: " + lat1 + ", lat2: " + lat2 + ", lon1: " + lon1 + ", lon2: " + lon2);
    // The math module contains a function
    // named toRadians which converts from
    // degrees to radians.
    lon1 = lon1 * Math.PI / 180;
    lon2 = lon2 * Math.PI / 180;
    lat1 = lat1 * Math.PI / 180;
    lat2 = lat2 * Math.PI / 180;

    // Haversine formula
    let dlon = lon2 - lon1;
    let dlat = lat2 - lat1;
    let a = Math.pow(Math.sin(dlat / 2), 2) +
        Math.cos(lat1) * Math.cos(lat2) *
        Math.pow(Math.sin(dlon / 2), 2);

    let c = 2 * Math.asin(Math.sqrt(a));

    // 6371 = Radius of earth in kilometers. Use 3956
    // for miles
    let r = 3956;

    //console.log("c: " + c);
    //console.log("r: " + r);
    // calculate the result
    return (c * r);
}

function sortImages() {
    var selectForm = document.getElementById("sort");
    //console.log("select value: " + selectForm.value);
    if (selectForm.value == 'proximity') {
        //  alert("Proximity sorting is coming soon!");
        imageArray.sort((a, b) => (a.distance > b.distance) ? 1 : -1);
    } else if (selectForm.value == 'age-new') {
        //console.log("sorting newest first");
        imageArray.sort((a, b) => (a.time < b.time) ? 1 : -1);
    } else if (selectForm.value == 'age-old') {
        //console.log("sorting oldest first");
        imageArray.sort((a, b) => (a.time > b.time) ? 1 : -1);
    } else if (selectForm.value == 'recommended') {
        imageArray.sort((a, b) => ((a.zTime + a.zDistance) > (b.zTime + b.zDistance)) ? 1 : -1);
    }

    filterPosts();
}

function showPosts() {
    document.getElementById("feed").innerHTML = "<br><div class='loader'></div>";
    //navigator.geolocation.getCurrentPosition(success);

    //console.log("user lat: " + lat + ", user long: " + long);

    var formDataFeed = new FormData();
    formDataFeed.append('lat', lat);
    formDataFeed.append('long', long);

    var xmlhttp = new XMLHttpRequest();

    var getFile = './php/newGetPost.php?_=' + Date.now();

    xmlhttp.open('POST', getFile, true);

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                //alert("chenging feed");
                //console.log("changing feed");

                if (xmlhttp.responseText == "noLocation") {
                    document.getElementById("feed").innerHTML = "<h2 class='contentText'>Uh oh! We can't tell where you are. Please allow us to access your location and reload.</h2>";
                } else if (xmlhttp.responseText === "[]") {
                    document.getElementById("feed").innerHTML = "<h2 class='contentText'>Uh oh! It doesn't look like there are any posts near you, try making one.</h2>";
                } else if (xmlhttp.responseText === "[null]") {
                    document.getElementById("feed").innerHTML = "<h2 class='contentText'>Uh oh! It doesn't look like there are any posts near you, try making one.</h2>";
                } else {

                    imageArray = eval(xmlhttp.responseText);

                    //console.log("server response: " + xmlhttp.responseText);
                    
                    /*for (var i = 0; i < imageArray.length; i++) {
                        console.log("object " + i + ": " + imageArray[i]);
                    }*/
                    
                    imageArray = removeNull(imageArray);

                    var timeMean = 0;
                    var distanceMean = 0;

                    var timeTotal = 0;
                    var distanceTotal = 0;
                    
                    timeMin = imageArray[0].time;

                    for (var i = 0; i < imageArray.length; i++) {
                        latObj = imageArray[i].lat;
                        lonObj = imageArray[i].long;
                        imageArray[i].distance = distance(latObj, lat, lonObj, long);
                        //console.log("obj distance: " + imageArray[i].distance);
                        timeTotal += imageArray[i].time / 10000;
                        distanceTotal += imageArray[i].distance;
                        
                        if (imageArray[i].time < timeMin) timeMin = imageArray[i].time;
                    }

                    document.getElementById("dateStart").min = timeMin;

                    timeMean = timeTotal / imageArray.length;
                    distanceMean = distanceTotal / imageArray.length;

                    var timeDevTotal = 0;
                    var distDevTotal = 0;

                    for (i = 0; i < imageArray.length; i++) {
                        timeDevTotal += Math.abs((imageArray[i].time / 10000) - timeMean);
                        distDevTotal += Math.abs(imageArray[i].distance - distanceMean);
                    }

                    var timeStDev = timeDevTotal / imageArray.length;
                    var distStDev = distDevTotal / imageArray.length;

                    for (i = 0; i < imageArray.length; i++) {
                        //console.log("post time: " + imageArray[i].time + ", timeMean: " + timeMean + ", timeStDev: " + timeStDev);
                        imageArray[i].zTime = -((imageArray[i].time / 10000 - timeMean) / timeStDev);
                        imageArray[i].zDistance = (imageArray[i].distance - distanceMean) / distStDev;
                        //console.log("z score for time: " + imageArray[i].zTime);
                        //console.log("z score for distance: " + imageArray[i].zDistance);
                    }

                    document.getElementById("feed").innerHTML = "";

                    sortImages();
                    initDateFilters();
                    
                    window.addEventListener('scroll', () => {
                      //console.log("scrolled", window.scrollY) //scrolled from top
                      //console.log(window.innerHeight) //visible part of screen
                      if (window.scrollY + window.innerHeight + (0.2 * window.innerHeight) >= document.documentElement.scrollHeight) {
                        fillPics(imagesToDisplay);
                      }
                      
                      //scrollHeader();
                    })
                }
            }
        }
    };
    
    xmlhttp.send(formDataFeed);

}

var counter = 0;

function display(jsonInfo) {
    //console.log("json info: " + jsonInfo);
    //console.log("json info length: " + jsonInfo.length);
    
    if (jsonInfo.length == 0) {
        document.getElementById("feed").innerHTML = "<h2 class='contentText'>Uh oh! It doesn't look like there are any posts near you, try making one.</h2>";
    } else {
        document.getElementById("feed").innerHTML = "";
    }
    
    counter = 0;
    
    imagesToDisplay = jsonInfo;
    
    //console.log("inner height: " + window.innerHeight);
    //console.log("document height: " + document.body.clientHeight);
    fillPics(jsonInfo);
    
    var x = 1;
    
    //while (window.innerHeight > document.body.clientHeight) {
    while (window.innerHeight > (window.innerWidth * 3 * x)) {
      fillPics(jsonInfo);
      x++;
    }
}

window.onresize = function() { while (window.innerHeight > document.body.clientHeight) {
      fillPics(imagesToDisplay);
    } };

function fillPics(jsonInfo) {
        var i = 0;
    
        while (i < 3 && counter < jsonInfo.length) {
        /*
        imageArray[i] = jsonInfo[i];
        latObj = jsonInfo[i].lat;
        lonObj = jsonInfo[i].long;
        imageArray[i].distance = distance(latObj, lat, lonObj, long);
        console.log("obj distance: " + imageArray[i].distance);
        */

        var object = jsonInfo[counter];
        //console.log("object image: " + object.image);
        //console.log("object lat: " + object.lat);
        //console.log("object long: " + object.long);
        var distanceNum = object.distance;
        var distanceString = "";

        if (distanceNum < 0.5) {
            //console.log("Converting to feet");
            distanceNum = Math.round(distanceNum * 5280);
            if (distanceNum == 1) {
                distanceString += distanceNum + " foot";
            } else {
                distanceString += distanceNum + " feet";
            }
        } else {
            if (distanceNum == 1) {
                distanceString += distanceNum.toFixed(4) + " mile";
            } else {
                distanceString += distanceNum.toFixed(4) + " miles";   
            }
        }

        var timeServer = object.time;

        var milliseconds = timeServer * 1000;

        var dateObj = new Date(milliseconds);
        var month = dateObj.getUTCMonth() + 1; //months from 1-12
        var day = dateObj.getUTCDate();
        var year = dateObj.getUTCFullYear();

        var newdate = month + "/" + day + "/" + year;

        document.getElementById("feed").innerHTML += "<img src='./posts/" + object.image + "' width='100%'> <div class='caption'><div class='distanceCap'><p> <i class='fa-solid fa-location-dot'></i> " + distanceString + "</div><div class='dateCap'><i class='fa-solid fa-calendar'></i> " + newdate + " </p></div>" ;// + ", <strong>" + object.likes + " likes</strong></p> <button id='like" + object.id + "'>Like</button><br>";

        //var likeString = "like" + object.id;

        //document.getElementById(likeString).addEventListener("click", likePost(object.id));
        counter++;
        i++;
    }
}

function likePost(postID) {

    if (likedPosts.includes(postID) == false) {

        //console.log("Liking Post!");
        var formData = new FormData();
        formData.append("id", postID);

        var xhttp = new XMLHttpRequest();

        // Set POST method and ajax file path
        xhttp.open("POST", "./php/likePost.php", true);

        // call on request changes state
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var response = this.responseText;
                if (response == 1) {
                    console.log("Post Liked");
                } else {
                    console.log("Like Failed");
                }
            }
        };

        likedPosts.push(postID);
    }
}
//Temporar

//window.onscroll = function() {myFunction()};
function scrollHeader() {
  var header = document.getElementById("header");
  var footer = document.getElementById("footer");
  var sticky = header.offsetTop;
  
  //console.log("sticky: " + sticky);
  //console.log("window.pageYOffset: " + window.pageYOffset);
    
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
  
  //if (Math.round(window.innerHeight + window.pageYOffset + 150) < Math.round(document.body.offsetHeight) {
  //  footer.classList.add("stickyFoot");
  //} else {
  //footer.classList.remove("stickyFoot");
  //}
}

const inputElement = document.querySelector('[type="range"]');
let isChanging = false;

const setCSSProperty = () => {
  const percent =
    ((inputElement.value - inputElement.min) /
    (inputElement.max - inputElement.min)) *
    100;
  // Here comes the magic
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

// Init input
setCSSProperty();

function initDateFilters() {
    var dateObjMax = document.getElementById("dateEnd");
    var dateObjMin = document.getElementById("dateStart");
    
    var minDate = new Date(timeMin * 1000);
    var dd = minDate.getDate();
    var mm = minDate.getMonth()+1; //January is 0 so need to add 1 to make it 1!
    var yyyy = minDate.getFullYear();
    if(dd<10){
      dd='0'+dd;
    } 
    if(mm<10){
      mm='0'+mm;
    } 

    minDate = yyyy+'-'+mm+'-'+dd;
    
    //console.log("min date: " + minDate);
    
    dateObjMin.min = minDate;
    dateObjMax.valueAsDate = new Date();
    dateObjMin.value = minDate;
    dateObjMax.max = dateObjMax.value;
    dateObjMax.min = dateObjMin.value;
    dateObjMin.max = dateObjMax.value;
}

initDateFilters();

function updateDates() {
    var dateObjMax = document.getElementById("dateEnd");
    var dateObjMin = document.getElementById("dateStart");
    var minDate = new Date(timeMin*1000);
    
    var dd = minDate.getDate();
    var mm = minDate.getMonth()+1; //January is 0 so need to add 1 to make it 1!
    var yyyy = minDate.getFullYear();
    if(dd<10){
      dd='0'+dd;
    } 
    if(mm<10){
      mm='0'+mm;
    } 

    minDate = yyyy+'-'+mm+'-'+dd;
    //console.log("min date: " + minDate);
    
    dateObjMin.min = minDate;
    dateObjMax.min = dateObjMin.value;
    dateObjMin.max = dateObjMax.value;
    
    filterPosts();
}