import { startCamera } from "./camera.js";
import { initFace } from "./face.js";
import { startLiveness } from "./liveness.js";

const video = document.getElementById("video");
const status = document.getElementById("status");

async function main() {

    try {
        await startCamera(video, status);

        const faceLandmarker = await initFace(status);

        if (!faceLandmarker) {
            status.innerHTML = "Face system failed";
            return;
        }

        startLiveness(video, faceLandmarker, status, captureFace);

    } catch (err) {
        console.error(err);
        status.innerHTML = "System error: " + err.message;
    }
}

function captureFace() {

    const canvas = document.getElementById("canvas");
    const photo = document.getElementById("photo");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0);

    photo.value = canvas.toDataURL("image/jpeg", 0.85);

    status.innerHTML = "✅ Liveness Passed - Face Captured";
    status.className = "text-success";
}

main();