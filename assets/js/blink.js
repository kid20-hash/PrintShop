let blinkState = "open";
let blinkCooldown = false;

function detectBlink(landmarks) {

    const leftEye = [
        landmarks[33],
        landmarks[160],
        landmarks[158],
        landmarks[133],
        landmarks[153],
        landmarks[144]
    ];

    const ear = eyeAspectRatio(leftEye);

    const OPEN_THRESHOLD = 0.30;
const CLOSED_THRESHOLD = 0.26;

    if (ear > OPEN_THRESHOLD) {

        status.innerHTML = "👁 Eyes Open";
        status.className = "text-success";

        blinkState = "open";
        blinkCooldown = false;

    } 
    else if (ear < CLOSED_THRESHOLD) {

        status.innerHTML = "👁 Eyes Closed";
        status.className = "text-warning";

        // detect only on transition: open → closed → open
        if (blinkState === "open" && !blinkCooldown) {

            blinkDetected = true;
            blinkCooldown = true;

            status.innerHTML = "✅ Blink Detected";
            status.className = "text-primary";

            captureFace?.(); // safe call
        }

        blinkState = "closed";
    }
}