<!doctype html>
<html>
<head>
    <title>HSL Tests</title>
    <style>
    body {
        width: 999px;
        margin: 0 auto;
    }

    b {
        width: 1px;
        height: 160px;
        display: inline;
        float: left;
    }
p {
    padding: 30px 0;
    margin: 0;
    clear: both;
}
    </style>
</head>
<body>

<p>
    <code><strong>Varying Hue</strong> // Saturation = 1.0, Lightness = 0.5</code>
</p>
<?php

require_once '../Color.php';

$s = 1;
$l = 0.5;

for ($i=0; $i <= 1000; $i++) {
    $c = new Color();
    $h = $i / 1000.0;
    $hex = $c->fromHSL($h, $s, $l)->toHexString();
    ?><b style="background:<?php echo $hex ?>"></b>
<?php
}
?>

<p>
    <code><strong>Varying Saturation</strong> // Hue=0.33, Lightness = 0.5</code>
</p>
<?php

$h = 0;
$l = 0.5;

for ($i=0; $i < 1000; $i++) {
    $c = new Color();
    $s = $i / 1000.0;
    $hex = $c->fromHSL($h, $s, $l)->toHexString();
    ?><b style="background:<?php echo $hex ?>"></b>
<?php
}
?>

<p>
    <code><strong>Varying Lightness</strong> // Hue = 0.33, Saturation = 1.0</code>
</p>
<?php

$s = 1;
$h = 2/3.0;

for ($i=0; $i < 1000; $i++) {
    $c = new Color();
    $l = $i / 1000.0;
    $hex = $c->fromHSL($h, $s, $l)->toHexString();
    ?><b style="background:<?php echo $hex ?>"></b>
    <?php
}
?>
</body>

