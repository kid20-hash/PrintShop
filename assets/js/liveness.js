let blinkCount = 0;
let headMoved = false;
let startX = null;
let lastBlink = 0;

export function startLiveness(video, faceLandmarker, status, onSuccess) {

    function loop() {

        // 🔥 SAFETY CHECK (prevents MediaPipe crash)
        if (!video.videoWidth || !video.videoHeight) {
            requestAnimationFrame(loop);
            return;
        }

        if (!faceLandmarker) {
            requestAnimationFrame(loop);
            return;
        }

        const results = faceLandmarker.detectForVideo(video, performance.now());

        const face = results.faceLandmarks?.[0];
        const blend = results.faceBlendshapes?.[0];

        if (face && blend) {

            const left = blend.categories.find(b => b.categoryName === "eyeBlinkLeft")?.score || 0;
            const right = blend.categories.find(b => b.categoryName === "eyeBlinkRight")?.score || 0;

            const blinking = left > 0.6 && right > 0.6;

            if (blinking && Date.now() - lastBlink > 800) {
                blinkCount++;
                lastBlink = Date.now();
            }

            const nose = face[1];

            if (startX === null) startX = nose.x;

            if (Math.abs(nose.x - startX) > 0.12) {
                headMoved = true;
            }

            status.innerHTML = `Blink: ${blinkCount}/2 | Move Head: ${headMoved ? "OK" : "NO"}`;

            if (blinkCount >= 2 && headMoved) {
                onSuccess();
                return;
            }
        }

        requestAnimationFrame(loop);
    }

    loop();
}