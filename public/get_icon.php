<?php
$url = 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';
// I should use a PNG actually because manifest requires PNG.
$pngUrl = 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg/512px-Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.png';
file_put_contents(__DIR__ . '/pwa-icon.png', file_get_contents($pngUrl));
echo "Done";
