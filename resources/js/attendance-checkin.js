/**
 * Modul geolocation untuk flow check-in/check-out.
 * Tanggung jawab: minta izin lokasi, tangani semua state error,
 * dan serahkan {latitude, longitude, accuracy} ke pemanggil.
 * TIDAK melakukan validasi radius/accuracy di sini — itu tugas backend.
 */

export function getCurrentLocation() {
    return new Promise((resolve, reject) => {
        if (!('geolocation' in navigator)) {
            reject({
                code: 'UNSUPPORTED',
                message: 'Perangkat/browser Anda tidak mendukung fitur lokasi.',
            });
            return;
        }

        if (!window.isSecureContext) {
            reject({
                code: 'INSECURE_CONTEXT',
                message: 'Fitur lokasi hanya dapat digunakan pada koneksi HTTPS.',
            });
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                });
            },
            (error) => {
                reject(mapGeolocationError(error));
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            }
        );
    });
}

function mapGeolocationError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            return {
                code: 'PERMISSION_DENIED',
                message: 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            };
        case error.POSITION_UNAVAILABLE:
            return {
                code: 'POSITION_UNAVAILABLE',
                message: 'Lokasi tidak dapat ditemukan. Pastikan GPS aktif.',
            };
        case error.TIMEOUT:
            return {
                code: 'TIMEOUT',
                message: 'Waktu mendeteksi lokasi habis. Silakan coba lagi.',
            };
        default:
            return {
                code: 'UNKNOWN',
                message: 'Terjadi kesalahan saat mendeteksi lokasi. Silakan coba lagi.',
            };
    }
}