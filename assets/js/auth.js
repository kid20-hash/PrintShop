import { faceLandmarker } from "./face.js";
import { checkLiveness } from "./liveness.js";

const video = document.getElementById("video");
const status = document.getElementById("status");
const photo = document.getElementById("photo");
const canvas = document.getElementById("canvas");

let verified = false;

function loop() {

    if (!faceLandmarker || video.readyState < 2) {
        requestAnimationFrame(loop);
        return;
    }

    const results = faceLandmarker.detectForVideo(video, performance.now());

    if (results.faceLandmarks.length > 0) {

        status.innerHTML = "Face detected";

        if (!verified) {
            verified = checkLiveness(results);
        }

        if (verified) {
            status.innerHTML = "✅ Liveness Verified";
            status.className = "text-success";

            captureFace();
        }

    } else {
        status.innerHTML = "No face";
    }

    requestAnimationFrame(loop);
}

function captureFace() {

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");

    ctx.drawImage(video, 0, 0);

    const image = canvas.toDataURL("image/jpeg", 0.85);

    photo.value = image;
}

export function startAuth() {
    loop();
}