<?php

/**
 * Description of MapPainter
 *
 */
class MapPainter extends MinecraftMap
{
    public function draw($img, $x0, $z0, $width, $height, array $colors)
    {
        $dx = $this->x0 - $x0;
        $dz = $this->z0 - $z0;
        $size = $this->blocks_in_pixel - 1;
        foreach ($this->get_pixels() as $position => $color_index) {
            $x = $dx + $this->blocks_in_pixel * ($position % MinecraftMap::SIZE);
            $z = $dz + $this->blocks_in_pixel * floor($position / MinecraftMap::SIZE);
            if ($x < 0 || $x > $width || $z < 0 || $z > $height) {
                continue;
            }
            if (isset($colors[$color_index])) {
                imagefilledrectangle($img, $x, $z, $x + $size, $z + $size, $colors[$color_index]);
            }
        }
    }
}
