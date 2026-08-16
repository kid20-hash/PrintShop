export async function startCamera(video, status) {

    const stream = await navigator.mediaDevices.getUserMedia({
        video: { width: 640, height: 480, facingMode: "user" },
        audio: false
    });

    video.srcObject = stream;

    await new Promise((resolve) => {
        video.onloadedmetadata = async () => {
            await video.play();
            resolve();
        };
    });

    // 🔥 EXTRA SAFETY: wait until real dimensions exist
    await new Promise((resolve) => {
        const check = () => {
            if (video.videoWidth > 0 && video.videoHeight > 0) {
                resolve();
            } else {
                requestAnimationFrame(check);
            }
        };
        check();
    });

    status.innerHTML = "📷 Camera Ready";
}