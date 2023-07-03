<?php

/* 
 * 
 */

return [
    'x0' => -1,
    'z0' => -2,
    'x_num' => 3,
    'z_num' => 4,
    'source_dir' => __DIR__ . '/data',
    'public_dir' => __DIR__ . '/public/end',
    'maps' => [
        // 4 zoom
        155, 160, 165, 170,
        214, 219, 224, 229,
        362, 541, 558, 563,

        // 3 zoom
        213, 218, 223, 228,
        540, 557, 562,
        // 2 zoom
        212, 217, 222, 227,
        360, 539, 556, 561,
        // 1 zoom
        211, 216, 221, 226,
        359, 538, 555, 560,
        // 0 zoom
        151, 156, 161, 166,
        210, 215, 220, 225,
        357, 358,
        537, 554, 559,
    ],

];