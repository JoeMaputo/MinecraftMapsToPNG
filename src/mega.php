<?php

$params = getopt('i:o:', ['end']);

$input_dir = $params['i'] ?? null;
$output_dir = $params['o'] ?? null;
$end = isset($params['end']);

if (is_null($input_dir) || is_null($output_dir)) {
    exit('Не задан, как минимум, один из параметров -i <директория данных> или -o <директория сохранения PNG>' . PHP_EOL);
}

define('OVERWORLD_MAPS', [
    // 4 zoom
    441, 313, 446,
    266, 235, 302, 307,
    261, 144, 149, 183, 178, 193, 198,
         75,  11,  6,   131, 105, 115,
         70,  61,  16,  95,  120, 188,
    284, 85,  80,  90,  136, 209, 204,
         250, 240, 255, 323,
    
    372, 378, 383, 390, 395, 402, 407,
    
    // 3 zoom
    74, 187, 182, 260,
    177, 197, 203, 208,
    
    // 2 zoom
    186, 181, 233, 253, 259, 264,
    176, 196, 202, 207, 282,
    300, 305, 311, 321, 393,
    
    // 1 zoom
    44, 48, 72, 
    185, 180, 252, 258, 263,
    107, 140, 175, 195, 206,
    271, 273, 278, 281, 286, 288,
    299, 304, 310, 320, 397,
    425, 427, 430,
    
    449, 450, 452, 454,
    
    // 0 zoom
    1, 7, 12, 18, 24, 64, 71, 81, 96,
    101, 106, 111, 116, 122, 127, 137, 145, 184, 179, 
    174, 194, 200, 205, 231, 236, 241,
    246, 251, 257, 262,
    270, 272, 275, 276, 277, 279, 280, 285, 287,
    230, 289, 290, 291, 292, 293, 295, 296, 297, 
    298, 303, 309, 314, 315, 319,
    367, 368, 374, 379, 386, 391, 398, 403,
    76, 274, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423,
    424, 426, 428, 429, 431,
    437, 442,
    
    447, 448, 451, 453, 455, 456,
]);
define('END_MAPS', [
    // 4 zoom
    155, 160, 165, 170,
    214, 219, 224, 229,
    362,
    
    // 3 zoom
    213, 218, 223, 228,
    // 2 zoom
    212, 217, 222, 227,
    360,
    // 1 zoom
    211, 216, 221, 226,
    359,
    // 0 zoom
    151, 156, 161, 166,
    210, 215, 220, 225,
    357, 358,
    ]);

require_once __DIR__ . '/MegaMap.php';

$converter = new MegaMap($input_dir, $output_dir);
$banners =  [];
if ($end) {
    $banners = $converter->generate_tiles(END_MAPS, -2112, -4160, 3, 3);
} else {
    $banners = $converter->generate_tiles(OVERWORLD_MAPS, -6208, -8256, 9, 7);
}

file_put_contents($output_dir . '/banners.js', 'const banners_list = ' . json_encode($banners, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
