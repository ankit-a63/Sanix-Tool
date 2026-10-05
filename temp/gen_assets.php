<?php
// Script to generate favicon.png and og-preview.png
$im = imagecreatetruecolor(64, 64);
imagesavealpha($im, true);
$trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $trans);

$bg = imagecolorallocate($im, 11, 15, 25);
$cyan = imagecolorallocate($im, 0, 242, 254);
$violet = imagecolorallocate($im, 127, 0, 255);

// Draw rounded rect
imagefilledrectangle($im, 4, 4, 59, 59, $bg);
imagerectangle($im, 4, 4, 59, 59, $cyan);
imagerectangle($im, 6, 6, 57, 57, $violet);
imagestring($im, 5, 24, 22, 'S', $cyan);

imagepng($im, 'E:/Sanni/Sanix Tool/assets/images/branding/favicon.png');
imagedestroy($im);

// Generate OG Preview Image (1200x630)
$og = imagecreatetruecolor(1200, 630);
$dark = imagecolorallocate($og, 11, 15, 25);
$white = imagecolorallocate($og, 255, 255, 255);
$accent = imagecolorallocate($og, 0, 242, 254);
$purple = imagecolorallocate($og, 127, 0, 255);
$gray = imagecolorallocate($og, 148, 163, 184);

imagefill($og, 0, 0, $dark);

// Decorative glow circles
for ($r = 300; $r > 0; $r -= 10) {
    $alpha = (int)(127 - ($r / 300) * 40);
    $gcol = imagecolorallocatealpha($og, 0, 242, 254, max(0, min(127, $alpha)));
    imagefilledellipse($og, 200, 150, $r, $r, $gcol);
}

imagestring($og, 5, 100, 260, 'SANIX TOOL', $white);
imagestring($og, 5, 100, 300, 'One Platform. Every Tool.', $accent);
imagestring($og, 4, 100, 350, 'Image, PDF, Text, Developer & Calculator Tools', $gray);

imagepng($og, 'E:/Sanni/Sanix Tool/assets/images/branding/og-preview.png');
imagedestroy($og);

echo "Assets generated successfully!\n";
