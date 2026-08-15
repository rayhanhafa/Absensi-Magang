export default function cameraCapture() {
    return {
        step: "idle", // idle | starting | live | captured | error
        errorMessage: "",
        stream: null,
        photoBlob: null,
        photoUrl: null,
        rootEl: null,

        init(el) {
            this.rootEl = el;
        },

        videoEl() {
            return this.rootEl.querySelector("[data-camera-video]");
        },

        canvasEl() {
            return this.rootEl.querySelector("[data-camera-canvas]");
        },

        async start() {
            this.step = "starting";
            this.errorMessage = "";

            if (
                !("mediaDevices" in navigator) ||
                !navigator.mediaDevices.getUserMedia
            ) {
                this.errorMessage =
                    "Kamera tidak dapat digunakan pada perangkat ini.";
                this.step = "error";
                return;
            }

            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "user" },
                    audio: false,
                });

                this.step = "live";
                await this.$nextTick();

                const video = this.videoEl();
                if (!video) {
                    throw new Error("Elemen video tidak ditemukan di halaman.");
                }

                video.srcObject = this.stream;
                await video.play();
            } catch (error) {
                console.error(
                    "Camera error:",
                    error.name,
                    error.message,
                    error,
                );
                this.errorMessage = this.mapCameraError(error);
                this.step = "error";
                this.stopStream();
            }
        },

        capture() {
            const video = this.videoEl();
            const canvas = this.canvasEl();

            if (!video || !video.videoWidth || !video.videoHeight) {
                this.errorMessage =
                    "Kamera belum siap. Tunggu sebentar lalu coba lagi.";
                this.step = "error";
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            canvas
                .getContext("2d")
                .drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(
                (blob) => {
                    this.photoBlob = blob;
                    this.photoUrl = URL.createObjectURL(blob);
                    this.step = "captured";
                    this.stopStream();
                },
                "image/jpeg",
                0.9,
            );
        },

        retake() {
            this.photoBlob = null;
            if (this.photoUrl) {
                URL.revokeObjectURL(this.photoUrl);
                this.photoUrl = null;
            }
            this.start();
        },

        confirm() {
            const file = new File(
                [this.photoBlob],
                `selfie-${Date.now()}.jpg`,
                { type: "image/jpeg" },
            );
            this.$dispatch("photo-captured", { file });
        },

        stopStream() {
            if (this.stream) {
                this.stream.getTracks().forEach((track) => track.stop());
                this.stream = null;
            }
        },

        mapCameraError(error) {
            if (
                error.name === "NotAllowedError" ||
                error.name === "PermissionDeniedError"
            ) {
                return "Akses kamera diperlukan untuk mengambil selfie.";
            }
            if (
                error.name === "NotFoundError" ||
                error.name === "DevicesNotFoundError"
            ) {
                return "Kamera tidak ditemukan pada perangkat ini.";
            }
            if (error.name === "NotReadableError") {
                return "Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.";
            }
            return "Kamera tidak dapat digunakan pada perangkat ini.";
        },
    };
}
