import { getCurrentLocation } from "../attendance-checkin";

export default function officeLocationPicker() {
    return {
        latitude: null,
        longitude: null,
        detecting: false,
        errorMessage: "",

        async detect() {
            this.detecting = true;
            this.errorMessage = "";

            try {
                const location = await getCurrentLocation();
                this.latitude = location.latitude.toFixed(7);
                this.longitude = location.longitude.toFixed(7);
            } catch (error) {
                this.errorMessage = error.message;
            } finally {
                this.detecting = false;
            }
        },
    };
}
