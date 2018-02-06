<?php
use \asm\sefaConsult;
use \asm\receitaFederalConsult;

require __DIR__.'/../vendor/autoload.php';

$sefa = new sefaConsult();
$receita = new receitaFederalConsult();

echo '<h1>Consultar Imagens</h1>';
echo '<hr>';
echo '<h2>Sintegra - Sefaz</h2>';
echo '<h3>Imagem</h3>';
echo '<p>Para ver a imagem da sefa use a função <i>getImage()</i> do objeto <i>asm\sefaConsult</i></p>';
echo '<img src='. $sefa->getImage() .'>';
echo '<hr>';
echo '<h3>Cookie</h3>';
echo '<p>Para ver a imagem da sefa use a função <i>getCookie()</i> do objeto <i>asm\sefaConsult</i></p>';
echo $sefa->getCookie();
echo '<hr>';
echo '<h2>Receita Federal</h2>';
echo '<p>Para ver a imagem da sefa use a função <i>getImage()</i> do objeto <i>asm\receitaFederalConsult</i></p>';
echo '<h3>Imagem</h3>';
echo '<img src='. $receita->getImage() .'>';
echo '<hr>';
echo '<h3>Cookie</h3>';
echo '<p>Para ver a imagem da sefa use a função <i>getCookie()</i> do objeto <i>asm\receitaFederalConsult</i></p>';
echo $receita->getCookie();
echo '<hr>';