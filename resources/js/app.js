import "./bootstrap";

import Alpine from "alpinejs";

import { getCurrentLocation } from "./attendance-checkin";

import attendanceLocation from "./components/attendance-location";

import attendanceFlow from "./components/attendance-flow";

import cameraCapture from "./components/camera-capture";

import officeLocationPicker from "./components/office-location-picker";

window.getCurrentLocation = getCurrentLocation;

window.Alpine = Alpine;
Alpine.data("attendanceFlow", attendanceFlow);
Alpine.data("attendanceLocation", attendanceLocation);
Alpine.data("cameraCapture", cameraCapture);
Alpine.data("officeLocationPicker", officeLocationPicker);
Alpine.start();
