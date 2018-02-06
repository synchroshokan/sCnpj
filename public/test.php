<?php 
use sCnpj\receitaFederalConsult as c;

require __DIR__.'/../vendor/autoload.php';

$sefa = new c();

$image = $sefa->getImage();

echo "<img src=".$image.">";
echo '<br>'.$sefa->getCookie();