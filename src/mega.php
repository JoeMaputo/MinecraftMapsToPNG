<?php

$params = getopt('c:');

$config_dir = $params['c'] ?? null;

if (is_null($config_dir)) {
    exit('Не задан параметр -c <файл конфигурации>' . PHP_EOL);
}
[
    'x0' => $x0,
    'z0' => $z0,
    'x_num' => $x_num,
    'z_num' => $z_num,
    'source_dir' => $input_dir,
    'public_dir' => $output_dir,
    'maps' => $maps
] = include $config_dir;

require_once __DIR__ . '/MegaMap.php';

$converter = new MegaMap($input_dir, $output_dir);
$banners =  $converter->generate_tiles($maps, $x0, $z0, $x_num, $z_num);

file_put_contents($output_dir . '/banners.js', 'const banners_list = ' . json_encode($banners, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
