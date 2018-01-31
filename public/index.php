<?php 
use \GuzzleHttp\Client;

require __DIR__.'/../vendor/autoload.php';

$http = new Client();

$page = $http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao.asp');
$cnpj = '05020839000105';

dump($page['headers'], $cnpj);