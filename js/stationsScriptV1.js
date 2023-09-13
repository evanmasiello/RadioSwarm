function showPosts() {

    var formDataFeed = new FormData();

    var xmlhttp = new XMLHttpRequest();

    var getFile = 'getStations.php?_=' + Date.now();

    xmlhttp.open('GET', getFile, true);

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