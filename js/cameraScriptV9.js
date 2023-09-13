var invertCamera = true;

// JavaScript program to calculate Distance Between
// Two Points on Earth
function saveImage() {
  image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
  var link = document.createElement('a');
  link.download = "myGeoGramPost.png";
  link.href = image;
  link.click();
}

//var showingGuidlines = false;

function showGuidelines() {
    if (document.getElementById("guidelinesDiv").style.display == "block") {
        document.getElementById("guidelinesDiv").style.display = "none";
        //showingGuidlines = false;
    } else {
        hideWindows();
        document.getElementById("guidelinesDiv").style.display = "block";
        //showingGuidlines = true;
    }
}

function invertCam() {
    if (invertCamera) {
        invertCamera = false;
        player.style = "";
    } else {
        invertCamera = true;
        player.style += "-moz-transform: scale(-1, 1); \
-webkit-transform: scale(-1, 1); -o-transform: scale(-1, 1); \
transform: scale(-1, 1); filter: FlipH;";
    }
}

function flip_image(canvas) {
    var context = canvas.getContext('2d');
    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    var imageFlip = new ImageData(canvas.width, canvas.height);
    var Npel = imageData.data.length / 4;

    for (var kPel = 0; kPel < Npel; kPel++) {
        var kFlip = flip_index(kPel, canvas.width, canvas.height);
        var offset = 4 * kPel;
        var offsetFlip = 4 * kFlip;
        imageFlip.data[offsetFlip + 0] = imageData.data[offset + 0];
        imageFlip.data[offsetFlip + 1] = imageData.data[offset + 1];
        imageFlip.data[offsetFlip + 2] = imageData.data[offset + 2];
        imageFlip.data[offsetFlip + 3] = imageData.data[offset + 3];
    }

    var canvasFlip = document.createElement('canvas');
    canvasFlip.setAttribute('width', canvas.width);
    canvasFlip.setAttribute('height', canvas.width);

    canvasFlip.getContext('2d').putImageData(imageFlip, 0, 0);
    return canvasFlip;
}

function flip_index(kPel, width, height) {
    var i = Math.floor(kPel / width);
    var j = kPel % width;
    var jFlip = width - j - 1;
    var kFlip = i * width + jFlip;
    return kFlip;
}

function resetCam() {
    if (videoDevices.length == 1) {
        navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
            // Attach the video stream to the video element and autoplay.
            document.getElementById('player').srcObject = stream;
        });
    } else {
        useCamera(videoDevices[currIndex]);
    }

    //if (!invertCamera) {
    //invertCam();
    //}

    document.getElementById('takeButtons').style.display = "block";
    document.getElementById('uploadButtons').style.display = "none";

    document.getElementById('canvas').style.display = "none";
    document.getElementById('player').style.display = "block";
}

function uploadFile() {
    //console.log("in upload");

    document.getElementById("loadingOverlay").style.display = "block";

    if (long === null && lat === null) {
        alert("Oh no! We can't find where you took this picture, please allow us to access your location, reload the page, and try again");
        navigator.geolocation.getCurrentPosition(success);
        document.getElementById("loadingOverlay").style.display = "none";
    } else if (imageDataUrl !== null) {
        //console.log("picture data is here!");

        var formData = new FormData();
        formData.append("image", imageDataUrl);
        if (lat !== undefined && lat !== null) formData.append("lat", lat);
        if (lat !== undefined && lat !== null) formData.append("long", long);

        var xhttp = new XMLHttpRequest();

        // Set POST method and ajax file path
        xhttp.open("POST", "./php/createJsonPost.php", true);

        // call on request changes state
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var response = this.responseText;
                if (response == 1) {
                    alert("Congrats! You made a post!");
                    document.getElementById("loadingOverlay").style.display = "none";
                } else {
                    alert("Uh oh, the post couldn't be uploaded");
                    document.getElementById("loadingOverlay").style.display = "none";
                }
            }
        };

        // Send request with data
        xhttp.send(formData);

    } else {
        alert("Please take a picture");
        document.getElementById("loadingOverlay").style.display = "none";
    }

}

//camera
const player = document.getElementById('player');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const captureButton = document.getElementById('capture');

//canvas.width = window.getComputedStyle(player).getPropertyValue('width');
//player.height = screen.width - 10;

var constraints = {
    video: {
        width: {
            min: 240,
            ideal: 1080,
            max: 1920
        },
        height: {
            min: 240,
            ideal: 1080,
            max: 1920
        },
        aspectRatio: {
            min: 1,
            max: 1,
            ideal: 1
        }
    }
};

//Temporary
const videoElement = document.querySelector("video");
const toggleButton = document.querySelector("#toggleCam");

let videoDevices;
let currIndex = -1;

function init() {
    console.log("in init");
    
    if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
        
        console.log("in get devices");
        
        navigator.mediaDevices
            .getUserMedia(constraints)
            .then(() => navigator.mediaDevices.enumerateDevices())
            .then(deviceInfos => {
                videoDevices = Array.from(deviceInfos).filter(item => item.kind == "videoinput");

                if (videoDevices.length === 0 || videoDevices === null || videoDevices === undefined) {
                    console.log("No Camera");
                    noCameraFound();
                } else if (videoDevices.length == 1) {
                    console.log("1 Camera");
                    const label = videoDevices[0].label || "Default Camera";
                    toggleButton.textContent = `Using ${label}`;
                    toggleButton.setAttribute("disabled", "1");
                    useCamera(videoDevices[0]);
                } else if (videoDevices.length >1 ) {
                    console.log("Many Camera");
                    toggleCamera();
                    toggleButton.addEventListener("click", toggleCamera);
                } else {
                    console.log("No Camera");
                    noCameraFound();
                }
            });
    } else {
        noCameraFound();
    }
}

function noCameraFound() {
    alert("Uh oh! We couldn't find any cameras connected to your device.");
    toggleButton.textContent = "No Camera";
    toggleButton.setAttribute("disabled", "1");
}

function useCamera(device) {
    if (videoElement && videoElement.srcObject && videoElement.srcObject.getTracks) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
    }

    const deviceId = device.deviceId;
    
    console.log("camera id: " + deviceId);
    console.log("camera lable: " + device.label);
    console.log("camera facing mode: " + device.facingMode);
    
    if (device.label.includes("back") || device.label.includes("enviroment") || device.facingMode == "enviroment" || device.label.includes("Back") || device.label.includes("Enviroment") || device.facingMode == "Enviroment") {
        invertCamera = false;
        console.log("Cam is facing enviroment");
        
        player.style = "";
        
    } else {
        invertCamera = true;
        console.log("Cam is facing user");
        
        player.style += "-moz-transform: scale(-1, 1); \
-webkit-transform: scale(-1, 1); -o-transform: scale(-1, 1); \
transform: scale(-1, 1); filter: FlipH;";
    }
    
    var constraints = {
        video: {
            deviceId: deviceId ? {
                exact: deviceId
            } : undefined,
            width: {
                min: 240,
                ideal: 1080,
                max: 1920
            },
            height: {
                min: 240,
                ideal: 1080,
                max: 1920
            },
            aspectRatio: {
                min: 1,
                max: 1,
                ideal: 1
            }
        }

    };

    navigator.mediaDevices
        .getUserMedia(constraints)
        .then(stream => {
            videoElement.srcObject = stream;
        })
        .catch();
}

function toggleCamera() {
    currIndex = (currIndex + 1) % videoDevices.length;
    useCamera(videoDevices[currIndex]);

    const nextIndex = (currIndex + 1) % videoDevices.length;
    const label = videoDevices[nextIndex].label || `Camera ${nextIndex}`;
    toggleButton.textContent = `Use ${label}`;
    
    if (videoDevices.length > 1) {
        invertCam();
    }
}

function captureClick() {

    navigator.geolocation.getCurrentPosition(success);

    //canvas.style.height = player.style.height;
    //canvas.style.width = player.style.width;
    
    //context.canvas.width  = window.innerWidth - 10;
    //context.canvas.height = window.innerWidth - 10;

    context.drawImage(player, 0, 0, context.canvas.height, context.canvas.width);
    
    if (invertCamera) {
 
    context.drawImage(flip_image(canvas), 0, 0, context.canvas.height, context.canvas.width);
    
    }

    // Stop all video streams.
    player.srcObject.getVideoTracks().forEach(track => track.stop());
    canvas.style.display = "block";
    player.style.display = "none";
    //document.getElementById('capture').onclick = "";
    document.getElementById('takeButtons').style.display = "none";
    document.getElementById('uploadButtons').style.display = "block";
    //document.getElementById('capture').onclick = "resetCam()";
    //document.getElementById("loadingOverlay").style.display = "block";

    imageDataUrl = canvas.toDataURL();
}

player.setAttribute("playsinline", true);

if (invertCamera) {
 
    player.style += "-moz-transform: scale(-1, 1); \
-webkit-transform: scale(-1, 1); -o-transform: scale(-1, 1); \
transform: scale(-1, 1); filter: FlipH;";
    
}

navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
// Attach the video stream to the video element and autoplay.
            player.srcObject = stream;
});

init();
//window.onscroll = function() {myFunction()};