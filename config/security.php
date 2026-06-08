<?php

return [
    'upload_max_kilobytes' => (int) env('SECURITY_UPLOAD_MAX_KB', 10240),
    'signed_url_minutes' => (int) env('SECURITY_SIGNED_URL_MINUTES', 10),
];
