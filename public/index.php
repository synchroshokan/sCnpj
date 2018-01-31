<?php 
use \sCnpj\sCnpj as c;

require __DIR__.'/../vendor/autoload.php';

$img = new c();

$imageString = $img->getImg();

file_put_contents(__DIR__.'/z.png', $imageString);

echo "<img src='z.png'>";