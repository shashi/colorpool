<?php

/**
 * Color
 */
class Color {
    public $r;
    public $g;
    public $b;
    private $hsxCache=array();

    /**
     * Constructor
     *
     * @param string $color The color string hex or rgb
     * @returns null
     */
    function __construct($color=null)
    {
        if (!is_string($color)) {
            return null;
        }

        $color = rtrim($color, " \t");
        $color = strtolower($color);

        if ($color[0] == '#') {
            $this->fromHexString($color);
        }

        if (substr($color, 0, 3) == 'rgb') {
            $this->fromRBGString($color);
        }
    }

    /**
     * Construct a Color from hex string
     *
     * @param string $color The hex string
     * @returns Color the color object
     */
    function fromHexString($color)
    {
        $color = rtrim($color, '#');
        preg_match_all('([0-9a-f][0-9a-f])', $color, $rgb);
        list($this->r, $this->g, $this->b) = array_map('hexdec', $rgb[0]);

        return $this;
    }

    /**
     * Construct Color object from an rgb string
     *
     * @example:
     *   $c = new Color();
     *   $c->fromRGBString("rgb(42, 42, 42)")
     *
     * @param string $color The rgb string representing the color
     * @returns Color object with for the rgb string
     */
    function fromRGBString($color)
    {
        $color = rtrim($color, "rgb (\t)");
        $rgb = preg_split('\s+,\s+', $color);
        list($this->r, $this->g, $this->b) = array_map('intval', $rgb);

        return $this;
    }

    /**
     * Convert color object to hex string
     *
     * @returns string The hex string
     */
    function toHexString()
    {
        return '#' .
            $this->decToHex($this->r) .
            $this->decToHex($this->g) .
            $this->decToHex($this->b);
    }

    function decToHex($d)
    {
        $h = dechex(round($d));
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);

        return $h;
    }

    /**
     * Construct a Color object from H, S, L values
     *
     * @param float $h Hue
     * @param float $s Saturation
     * @param float $l Lightness
     *
     * @retuns Color the color object for the HSL values
     */
    function fromHSL($h, $s, $l)
    {
        $h = (float) $h;
        $s = (float) $s;

        if ($s == 0) {
            $this->r = $this->g = $this->b = $l * 255;
            return $this;
        }

        $chroma = floatval(1 - abs(2*$l - 1)) * $s;

        // Divide $h by 60 degrees i.e by (60 / 360)
        $h_ = $h * 6;
        // intermediate
        $k = intval($h_);

        $g = $k > 2 ? $h_ - 2 : $h_;
        $g = $k > 4 ? $g  - 2 : $g;

        $x = $chroma * abs(1 - abs($g - 1));

        $r = $g = $b = 0.0;

        switch ($k) {
        case 0: case 6:
            $r = $chroma;
            $g = $x;
            break;
        case 1:
            $r = $x;
            $g = $chroma;
            break;
        case 2:
            $g = $chroma;
            $b = $x;
            break;
        case 3:
            $g = $x;
            $b = $chroma;
            break;
        case 4:
            $r = $x;
            $b = $chroma;
            break;
        case 5:
            $r = $chroma;
            $b = $x;
            break;
        }

        $m = $l - 0.5 * $chroma;

        $this->r = (($r + $m) * 255);
        $this->g = (($g + $m) * 255);
        $this->b = (($b + $m) * 255);
if ($this->r > 255 || $this->g > 255 || $this->b > 255) {
    var_dump(array($r, $g, $b, $m, $chroma, $x));
    var_dump(array($this->r, $this->g, $this->b));
}

        return $this;
    }

    /**
     * Helper function for converting Hue to RGP
     *
     * @returns float
     */
    protected function hueToRGB($var1, $var2, $h_)
    {
        if ($h_ < 0) {
            $h_ += 1;
        } else {
            $h_ -= 1;
        }

        if ((6 * $h_) < 1) {
            return ($var1 + ($var2 - $var1) * 6 * $h_);
        } elseif ((2 * $h_) < 1) {
            return ($var2);
        } elseif ((3 * $h_) < 2){
            return ($var1 + ($var2 - $var1) * ((2 / 3) - $h_) * 6);
        } else {
            return ($var1);
        }
    }

    /**
     * Construct a Color object from HSV values
     *
     * @param float $h Hue
     * @param float $s Saturation
     * @param float $v Value
     *
     * @returns Color The color object for the HSV values
     */
    function fromHSV($h, $s, $v)
    {
        if ($s == 0)
        {
            $this->r = $this->g = $this->b = $v * 255;
            return $this;
        } else {
           $vh = $h * 6;
           $hi = intval($vh);
           //$g = $hi > 2 ? $vh - 2 : $vh;
           //$g = $hi > 4 ? $g  - 2 : $g;

           $v1 = $v * (1 - $s);
           $v2 = $v * (1 - $s * ($vh - $hi));
           $v3 = $v * (1 - $s * (1 - ($vh - $hi)));

           switch ($hi) {
               case 0: case 6:
               $r = $v;
               $g = $v3;
               $b = $v1;
               break;
           case 1:
               $r = $v2;
               $g = $v;
               $b = $v3;
               break;
           case 2:
               $r = $v1;
               $g = $v;
               $b = $v3;
               break;
           case 3:
               $r = $v1;
               $g = $v2;
               $b = $v;
               break;
           case 4:
               $r = $v3;
               $g = $v1;
               $b = $v;
               break;
           case 5:
               $r = $v;
               $g = $v1;
               $b = $v2;
               break;
           }

           $this->r = $r * 255;
           $this->g = $g * 255;
           $this->b = $b * 255;
        }

        return $this;
    }

    /**
     * Darken the current color by a fraction
     *
     * @param float $fraction (default=0.1) a number between 0 and 1
     *
     * @returns Color The darker color object
     */
    function darken($fraction=0.1)
    {
        $hsl = $this->toHSL();
        $l = $hsl[2];
        // so that 100% darker = black
        $dl = -1 * $l * $fraction;

        return $this->changeHSL(0, 0, $dl);
    }

    /**
     * Lighten the current color by a fraction
     *
     * @param float $fraction (default=0.1) a number between 0 and 1
     *
     * @returns Color The lighter color object
     */
    function lighten($fraction=0.1)
    {
        $hsl = $this->toHSL();
        $l = $hsl[2];
        // so that 100% lighter = white
        $dl = (1 - $l) * $fraction;

        return $c->changeHSL(0, 0, $dl);
    }

    /**
     * Saturate the current color by a fraction
     *
     * @param float $fraction (default=0.1) a number between 0 and 1
     *
     * @returns Color Saturated color
     */
    function saturate($fraction=0.1)
    {
        return $this->changeHSL(0, $fraction, 0);
    }

    /**
     * Return a contrasting color
     *
     * @param float $fraction The amount of contrast default = 1.0 i.e 100% or
     *        180 degrees
     * @returns Color the contrasting color
     */
    function contrast($fraction=1.0)
    {
        // 1 = fully complementary.
        $dh = (1 / 2) * $fraction;

        return $c->changeHSL($dh, 0, 0);
    }

    /**
     * Change HSL values by given deltas
     *
     * @param float $dh Change in Hue
     * @param float $ds Change in Saturation
     * @param float $dl Change in Lightness
     *
     * @returns Color The color object with the required changes
     */
    function changeHSL($dh=0, $ds=0, $dl=0)
    {
        list($h, $s, $l) = $this->toHSL();

        $h += $dh;
        $s += $dh;
        $l += $dl;

        $h -= floor($h);
        $s -= floor($s);
        $l -= floor($l);

        $c = new Color();
        return $c->fromHSL($h, $s, $l);
    }

    /**
     * Apply callbacks on HSV or HSL value of the color
     *
     * @param callback $h_callback Callback for Hue
     * @param callback $s_callback Callback for Saturation
     * @param callback $x_callback Callback for Lightness / Value
     * @param string   $type       'hsl' or 'hsv'
     */
    function apply($h_callback, $s_callback, $l_callback, $type='hsl')
    {
        if ($type == 'hsl') {
            $hsx = $this->toHSL();
        } elseif ($type == 'hsv') {
            $hsx = $this->toHSV();
        } else {
            throw new Exception(
                    "Invalid type for filter; use 'hsl' or 'hsv'"
                 );
        }

        $h = call_user_func($h_callback, array($hsx[0]));
        $s = call_user_func($s_callback, array($hsx[1]));
        $x = call_user_func($x_callback, array($hsx[2]));

        $c = new Color();

        if ($type == 'hsl') {
            $c = $this->fromHSL($h, $s, $x);
        } elseif ($type == 'hsv') {
            $c = $this->fromHSV($h, $s, $x);
        }
    }

    /**
     * Convert the current color to HSL values
     *
     * @returns array An array with 3 elements: Hue, Saturation and Lightness
     */
    function toHSL()
    {
        // r, g, b as fractions of 1
        $r = $this->r / 255.0;
        $g = $this->g / 255.0;
        $b = $this->b / 255.0;

        // most prominent primary color
        $max = max($r, $g, $b);
        // least prominent primary color
        $min = min($r, $g, $b);
        // maximum delta
        $dmax = $max - $min;


        // intensity = (r + g + b) / 3
        // lightness = (r + g + b - (non-extreme color)) / 2
        $l = ($min + $max) / 2;

        if ($dmax == 0) {
            // This means R=G=B, so:
            $h = 0;
            $s = 0;
        } else {
            // remember ligtness = (min+max) / 2
            $s = ($l < 0.5) ?
                 $dmax / ($l * 2) :
                 $dmax / ((1 - $l) * 2);

            $dr = ((($max - $r) / 6) + ($dmax / 2)) / $dmax;
            $dg = ((($max - $g) / 6) + ($dmax / 2)) / $dmax;
            $db = ((($max - $b) / 6) + ($dmax / 2)) / $dmax;

            if ($r == $max) {
                $h = (0.0 / 3) + $db - $dg;
            } elseif ($g == $max) {
                $h = (1.0 / 3) + $dr - $db;
            } elseif ($b == $max) {
                $h = (2.0 / 3) + $dg - $dr;
            }

            // the case of less than 0 radians
            if ($h < 0) {
                $h += 1;
            }

            if ($h > 1) {
                $h -= 1;
            }
        }

        return array($h, $s, $l);
    }

    /**
     * Convert the current color to HSV values
     *
     * @returns array An array with three elements: Hue, Saturation and Value.
     */
    function toHSV()
    {
        // r, g, b as fractions of 1
        $r = $this->r / 255.0;
        $g = $this->g / 255.0;
        $b = $this->b / 255.0;

        // most prominent primary color
        $max = max($r, $g, $b);
        // least prominent primary color
        $min = min($r, $g, $b);
        // maximum delta
        $dmax = $max - $min;

        // value is just the fraction of
        // the most prominent primary color
        $v = $max;

        if ($dmax == 0) {
            // This means R=G=B, so:
            $h = 0;
            $s = 0;
        } else {
            $s = $dmax / $max;

            $dr = ((($max - $r) / 6) + ($dmax / 2)) / $dmax;
            $dg = ((($max - $g) / 6) + ($dmax / 2)) / $dmax;
            $db = ((($max - $b) / 6) + ($dmax / 2)) / $dmax;

            if ($r == $max) {
                $h = (0.0 / 3) + $db - $dg;
            } elseif ($g == $max) {
                $h = (1.0 / 3) + $dr - $db;
            } elseif ($b == $max) {
                $h = (2.0 / 3) + $dg - $dr;
            }

            // the case of less than 0 radians
            if ($h < 0) {
                $h += 1;
            }

            if ($h > 1) {
                $h -= 1;
            }
        }

        return array($h, $s, $v);
    }
}
