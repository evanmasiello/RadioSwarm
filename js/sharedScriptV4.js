navigator.geolocation.getCurrentPosition(successInit, error);
var long = null;
var lat = null;
var oldLong;
var oldLat;

//var showingAbout = false;

function showAbout() {
    if (document.getElementById("aboutDiv").style.display == "block") {
        document.getElementById("aboutDiv").style.display = "none";
        //showingAbout = false;
    } else {
        hideWindows();
        document.getElementById("aboutDiv").style.display = "block";
        //showingAbout = true;
    }
}

function hideWindows() {
    
    var windows = document.getElementsByClassName("popup");
    
    for (var i=0; i < windows.length; i++) {
        windows[i].style.display = "none";
    }
}

//var showingFilters = false;

function showFilters() {
    
    if (document.getElementById("filtersDiv").style.display == "block") {
        document.getElementById("filtersDiv").style.display = "none";
        //showingFilters = false;
    } else {
        hideWindows();
        document.getElementById("filtersDiv").style.display = "block";
        //showingFilters = true;
    }
    
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

function successInit(pos) {
    console.log("Location found!");
    var crd = pos.coords;
    long = crd.longitude;
    lat = crd.latitude;
    oldLong = long;
    oldLat = lat;
    showPosts();
    setTimeout(navigator.geolocation.getCurrentPosition(success), 60000);
}

function success(pos) {
    console.log("Updating Location");
    var crd = pos.coords;
    long = crd.longitude;
    lat = crd.latitude;
    if (distance(lat, oldLat, long, oldLong) > 0.5) {
        showPosts();
        oldLong = long;
        oldLat = lat;
    }
    setTimeout(navigator.geolocation.getCurrentPosition(success), 60000);
}

function handleError(error) {
    console.log("Error: ", error);
}