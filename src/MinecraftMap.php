<?php

if (!defined('BIG_ENDIAN')) {
    define('BIG_ENDIAN', pack('L', 1) === pack('N', 1));
}

class MinecraftMap
{
    protected const KEYS_09 = [
        'frames' => [
            'EntityId' => true,
            'Rotation' => true,
            'Pos' => true
        ],
        'banners' => [
            'Color' => true,
            'Name' => false,
            'Pos' => true
        ],
    ];
    
    protected const READERS = [
        1 => 'read_01',
        3 => 'read_03',
        7 => 'read_07',
        8 => 'read_08',
        9 => 'read_09',
        10 => 'read_0A',
    ];

    public const SIZE = 128;

    protected $data = '';
    protected $position = 0;
    protected $len = 0;
    
    protected $zoom = 0;

    protected $size_in_blocks = 128;
    protected $blocks_in_pixel = 1;
    protected $x0 = 0; // horizontal
    protected $z0 = 0; // vertical
    

    public function get_banners($data_in_binary)
    {
        $this->len = strlen($data_in_binary);
        $headers = [];
        $this->read_any($data_in_binary, $headers);
        return $this->setup_parameters($headers[0]);
    }

    public function get_pixels()
    {
        return array_values(unpack('C*', $this->data));
    }
    
    public function is_bounding($x0, $z0, $width, $height)
    {
        if ($x0 >= ($this->x0 + $this->size_in_blocks) || ($x0 + $width) <= $this->x0) {
            return false;
        }
        if ($z0 >= ($this->z0 + $this->size_in_blocks) || ($z0 + $height) <= $this->z0) {
            return false;
        }
        return true;
    }
    
    public function get_zoom()
    {
        return $this->zoom;
    }

    private function read_01($data)
    {
        $name = $this->read_name($data);
        $value = $this->read_byte($data);
        return [$name, $value];
    }

    private function read_03($data)
    {
        $name = $this->read_name($data);
        $value = $this->read_32_signed_big_endian($data);
        return [$name, $value];
    }

    private function read_07($data)
    {
        $name = $this->read_name($data);
        $value = $this->read_32_signed_big_endian($data);
        $this->data = substr($data, $this->position, $value); //array_values(unpack('C' . $value, $data, $this->position));
        $this->position += $value;
        return [$name, $value];
    }

    private function read_08($data)
    {
        $name = $this->read_name($data);
        [1 => $len] = unpack('n', $data, $this->position);
        $this->position += 2;
        if (!$len) {
            return null;
        }
        [1 => $string] = unpack('Z' . $len, $data, $this->position);
        $this->position += $len;
        return [$name, $string];
    }

    private function read_09($data)
    {
        $name = $this->read_name($data);
        $sub = [];
        [1 => $cnt] = unpack('n', $data, $this->position + 3);
        $this->position += 5;
        if (!$cnt) {
            return [$name, $sub];
        }
        for ($i = 0; $i < $cnt; $i++) {
            $s_data = [];
            $result = $this->read_any($data, $s_data);
            if ($result === false) {
                break;
            }
            $next_name = $this->read_next_name($data);
            while (isset(self::KEYS_09[$name][$next_name]) && !isset($s_data[$next_name])) {
                $result = $this->read_any($data, $s_data);
                if ($result === false) {
                    break 2;
                }

                $next_name = $this->read_next_name($data);
            }
            $sub[$i] = $s_data;
        }
        return [$name, $sub];
    }

    private function read_0A($data)
    {
        $d = [];
        $p_name = $this->read_name($data);
        while(true) {
            $result = $this->read_any($data, $d);
            if ($result === null) {
                break;
            }
            if (($this->position + 1) >= $this->len) {
                break;
            }
        }
        return [$p_name, $d];
    }

    private function read_byte($data, $move = true)
    {
        if (($this->position + 1) >= $this->len) {
            //echo $this->debug();
            return false;
        }
        [1 => $chr] = unpack('C', $data, $this->position);
        if ($move) {
            $this->position++;
        }
        if (!$chr && $move && !$this->read_byte($data, false)) {
            $this->position++;
        }
        return $chr;
    }

    private function read_name($data)
    {
        if (($this->position + 2) >= $this->len) {
            return false;
        }
        [1 => $len] = unpack('n', $data, $this->position);
        $this->position += 2;
        if (!$len) {
            return null;
        }
        [1 => $name] = unpack('Z' . $len, $data, $this->position);
        $this->position += $len;
        return $name;
    }

    private function read_next_name($data)
    {
        if (!$this->read_byte($data, false)) {
            $this->read_byte($data);
        }
        [1 => $len] = unpack('n', $data, $this->position + 1);
        if (!$len) {
            return null;
        }
        [1 => $name] = unpack('Z' . $len, $data, $this->position + 3);
        return $name;
    }

    private function read_32_signed_big_endian($data)
    {
        $d = substr($data, $this->position, 4);
        $this->position += 4;
        if (!BIG_ENDIAN) {
            $d = strrev($d);
        }
        [1 => $value] = unpack('l', $d);
        return $value;
    }

    private function read_any($data, &$output_array) 
    {
        $type = $this->read_byte($data);
        if (!$type) {
            return null;
        }
        if (!isset(self::READERS[$type])) {
            return false;
        }
        [$name, $value] = call_user_func([$this, self::READERS[$type]], $data);
        if (is_string($name)) {
            $output_array[$name] = $value;
        } else {
            $output_array[] = $value;
        }
        return true;
    }

    private function setup_parameters(array $headers)
    {
        if (!isset($headers['data'])) {
            return;
        }
        $this->zoom = $headers['data']['scale'] ?? 0;
        $this->blocks_in_pixel = (1 << $this->zoom);
        $this->size_in_blocks = self::SIZE * $this->blocks_in_pixel;
        $xCenter = $headers['data']['xCenter'] ?? 0;
        $zCenter = $headers['data']['zCenter'] ?? 0;
        $this->x0 = $xCenter - $this->size_in_blocks / 2;
        $this->z0 = $zCenter - $this->size_in_blocks / 2;
        return $this->set_banners($headers);
    }

    private function set_banners(array $headers)
    {
        $banners = [];
        foreach (($headers['data']['banners'] ?? []) as $banner) {
            ['X' => $x, 'Z' => $z] = $banner['Pos'];
            $name = json_decode($banner['Name'] ?? '{"text":""}', true);
            $banners[] = [
                'x' => $x,
                'z' => $z,
                'c' => strtolower($banner['Color'] ?? 'white'),
                'title' => $name['text'] ?? '',
            ];
        }
        return $banners;
    }

    private function debug()
    {
        $r = $this->position . ":\n";
        foreach (debug_backtrace() as ['line' => $l, 'function' => $f]) {
            $r .= "\t$l: $f\n";
        }
        return $r;
    }

}