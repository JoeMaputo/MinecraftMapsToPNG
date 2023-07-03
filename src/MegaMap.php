<?php

require_once __DIR__ . '/MinecraftMap.php';
require_once __DIR__ . '/MapPainter.php';
require_once __DIR__ . '/colors.php';


class MegaMap
{
    private const MAP_NAME_TEMPLATE = 'map_{num}.dat';
    private const PNG_NAME_TEMPLATE = '/{x}-{z}.png';

    private const TILE_SIZE = 2048;
    private const CENTER_X = -64;
    private const CENTER_Z = -64;

    private $input_dir = '';
    private $output_dir = '';
    
    private $maps = [];
    private $banners = [];

    public function __construct($in, $out)
    {
        if (!$this->check_dir($in)) {
            exit('Не удалось получить доступ к ' . $in . PHP_EOL);
        }
        if (!$this->check_dir($out, true)) {
            exit('Не удалось получить доступ к ' . $out . PHP_EOL);
        }
        $this->input_dir = rtrim($in, '/');
        $this->output_dir = rtrim($out, '/');
    }
    
    public function generate_tiles(array $map_numbers, $x0_num, $z0_num, $x_tiles_count, $z_tiles_count)
    {
        $x0 = self::CENTER_X + $x0_num * self::TILE_SIZE;
        $z0 = self::CENTER_Z + $z0_num * self::TILE_SIZE;
        $this->read_maps($map_numbers);
        for ($i = 0; $i < $x_tiles_count; $i++) {
            $tile_x0 = $x0 + $i * self::TILE_SIZE;
            for ($j = 0; $j < $z_tiles_count; $j++) {
                $tile_z0 = $z0 + $j * self::TILE_SIZE;
                $img = $this->create_tile(self::TILE_SIZE, self::TILE_SIZE);
                $this->draw_maps_in_tile($img, $tile_x0, $tile_z0, self::TILE_SIZE, self::TILE_SIZE, $this->create_colors($img, CUSTOM_COLORS));
                imagepng($img, $this->output_dir . str_replace(['{x}', '{z}'], [$i, $j], self::PNG_NAME_TEMPLATE));
                imagedestroy($img);
            }
        }
        return $this->banners;
    }
    
    private function create_tile($width, $height)
    {
        $img = imagecreatetruecolor($width, $height);
        $alpha = imagecolorallocate($img, 1, 1, 1);
        imagefill($img, 0, 0, $alpha);
        imagecolortransparent($img, $alpha);
        imagesetinterpolation($img, IMG_TRIANGLE);
        return $img;
    }
    
    private function draw_maps_in_tile($img, $x0, $z0, $width, $height, $map_colors)
    {
        foreach ($this->maps as $map) {
            if (!$map->is_bounding($x0, $z0, $width, $height)) {
                continue;
            }
            $map->draw($img, $x0, $z0, $width, $height, $map_colors);
        }
    }
    
    private function read_maps(array $map_numbers_array)
    {
        foreach ($map_numbers_array as $map_number) {
            $data = $this->get_map_data($map_number);
            if (!strlen($data)) {
                continue;
            }
            $map = new MapPainter();
            $this->banners = array_merge($this->banners, $map->get_banners($data));
            $this->maps[] = $map;
        }
    }

    private function get_map_data($map_number)
    {
        $file_name = 
            $this->input_dir 
            . (strlen($this->input_dir) ? '/' : '') 
            . str_replace('{num}', $map_number, self::MAP_NAME_TEMPLATE);
        if (!is_readable($file_name)) {
            return '';
        }
        ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE);
        readgzfile($file_name);
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }

    private function create_colors($img, array $colors_array_RGB)
    {
        return array_map(function($rgb) use ($img) {
            [$r, $g, $b] = $rgb;
            return imagecolorallocate($img, $r, $g, $b);
        }, $colors_array_RGB);
    }

    private function check_dir($dirname, $try_to_create_if_not_exists = false)
    {
        if (is_dir($dirname)) {
            return true;
        }
        if (!$try_to_create_if_not_exists) {
            return false;
        }
        mkdir($dirname, 0776, true);
        if (!is_dir($dirname)) {
            return false;
        }
        return true;
    }
}