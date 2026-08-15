<?php

return [

    /*
    |--------------------------------------------------------------------
    | Wajibkan Lokasi & Selfie saat Check-in/out
    |--------------------------------------------------------------------
    | Saat false (default), check-in/out tetap berfungsi seperti semula
    | tanpa lokasi/foto — tidak mengubah perilaku existing.
    | Saat true, CheckInRequest/CheckOutRequest akan mewajibkan
    | latitude, longitude, accuracy, dan foto.
    */
    'require_location' => env('ATTENDANCE_REQUIRE_LOCATION', false),

    /*
    |--------------------------------------------------------------------
    | Batas Akurasi GPS Maksimum (meter)
    |--------------------------------------------------------------------
    | Jika accuracy yang dilaporkan browser lebih buruk (angka lebih
    | besar) dari nilai ini, lokasi dianggap tidak cukup akurat.
    */
    'max_accuracy_meters' => env('ATTENDANCE_MAX_ACCURACY', 100),

];