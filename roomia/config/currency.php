<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tỷ giá VNĐ → USD dùng cho Stripe
    |--------------------------------------------------------------------------
    |
    | Đây là tỷ giá tạm để convert VNĐ sang USD khi thanh toán qua Stripe.
    | Sau này bạn có thể đổi ở đây hoặc lấy từ ENV.
    |
    */

    'vnd_to_usd_rate' => env('VND_TO_USD_RATE', 25000), // 1 USD = 25,000 VND (ví dụ)
];
