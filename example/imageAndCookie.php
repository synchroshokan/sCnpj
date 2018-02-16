<?php
use \ConsultaSimples\sefaConsult;
use \ConsultaSimples\receitaFederalConsult;

require __DIR__.'/../vendor/autoload.php';

$sefa = new sefaConsult();
$receita = new receitaFederalConsult();

$sefaResult = $sefa->getParams();
$receitaResult = $receita->getParams();

echo '<h1>Consultar Imagens</h1>';
echo '<hr>';
echo '<h2>Sintegra - Sefaz</h2>';
echo '<h3>Imagem</h3>';
echo '<p>Para ver a imagem da sefa use a função <i>getImage()</i> do objeto <i>ConsultaSimples\sefaConsult</i></p>';
echo '<img src='. $sefaResult['img'] .'>';
echo '<hr>';
echo '<h3>Cookie</h3>';
echo '<p>Para ver a imagem da sefa use a função <i>getCookie()</i> do objeto <i>ConsultaSimples\sefaConsult</i></p>';
echo $sefaResult['cookie'];
echo '<hr>';
echo '<h2>Receita Federal</h2>';
echo '<p>Para ver a imagem da sefa use a função <i>getImage()</i> do objeto <i>ConsultaSimples\receitaFederalConsult</i></p>';
echo '<h3>Imagem</h3>';
echo '<img src='. $receitaResult['img'] .'>';
echo '<hr>';
echo '<h3>Cookie</h3>';
echo '<p>Para ver a imagem da sefa use a função <i>getCookie()</i> do objeto <i>ConsultaSimples\receitaFederalConsult</i></p>';
echo $receitaResult['cookie'];
echo '<hr>';