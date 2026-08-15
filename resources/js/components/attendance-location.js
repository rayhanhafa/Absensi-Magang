export default function attendanceLocation(formAction) {
    return {
        step: "idle", // idle | locating | ready | error
        location: { latitude: null, longitude: null, accuracy: null },
        errorMessage: "",
        submitting: false,
        formAction,

        async start() {
            this.step = "locating";
            this.errorMessage = "";

            try {
                this.location = await window.getCurrentLocation();
                this.step = "ready";
            } catch (error) {
                this.errorMessage = error.message;
                this.step = "error";
            }
        },
    };
}
