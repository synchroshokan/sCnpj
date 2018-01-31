<?php 
use \GuzzleHttp\Client;

require __DIR__.'/../vendor/autoload.php';

$http = new Client();

$page = $http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao.asp');

//$cookie = $page['headers']['Set-Cookie'];

dump($page);