import { getCurrentLocation } from "../attendance-checkin";

export default function attendanceFlow(formAction, requireLocation) {
    return {
        step: "idle", // idle | locating | location-error | camera | ready | submitting
        requireLocation,
        formAction,
        location: { latitude: null, longitude: null, accuracy: null },
        photoFile: null,
        photoPreviewUrl: null,
        photoFieldName: null,
        errorMessage: "",

        async start() {
            this.step = "locating";
            this.errorMessage = "";

            try {
                this.location = await getCurrentLocation();
                this.step = this.requireLocation ? "camera" : "ready";
            } catch (error) {
                this.errorMessage = error.message;
                this.step = "location-error";
            }
        },

        handlePhoto(file) {
            this.photoFile = file;
            this.photoPreviewUrl = URL.createObjectURL(file);
            this.step = "ready";
        },

        retakePhoto() {
            if (this.photoPreviewUrl) {
                URL.revokeObjectURL(this.photoPreviewUrl);
            }
            this.photoFile = null;
            this.photoPreviewUrl = null;
            this.step = "camera";
        },

        async submit() {
            this.step = "submitting";
            this.errorMessage = "";

            const formData = new FormData();
            formData.append("latitude", this.location.latitude ?? "");
            formData.append("longitude", this.location.longitude ?? "");
            formData.append("accuracy", this.location.accuracy ?? "");

            if (this.photoFile) {
                formData.append(
                    this.photoFieldName,
                    this.photoFile,
                    this.photoFile.name,
                );
            }

            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]',
            ).content;

            try {
                const response = await fetch(this.formAction, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                    body: formData,
                });

                if (response.status === 422) {
                    const data = await response.json();
                    const messages = Object.values(data.errors ?? {}).flat();
                    this.errorMessage =
                        messages[0] ?? "Data yang dikirim tidak valid.";
                    this.step = "ready";
                    return;
                }

                if (!response.ok) {
                    this.errorMessage =
                        "Terjadi kesalahan saat menyimpan absensi. Silakan coba lagi.";
                    this.step = "ready";
                    return;
                }

                window.location.href = response.url || window.location.href;
                window.location.reload();
            } catch (error) {
                console.error("Submit error:", error);
                this.errorMessage =
                    "Terjadi kesalahan saat menyimpan absensi. Silakan coba lagi.";
                this.step = "ready";
            }
        },
    };
}
