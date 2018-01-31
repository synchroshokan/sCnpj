<?php 
use \GuzzleHttp\Client;

require __DIR__.'/../vendor/autoload.php';

$http = new Client();

$request = $http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao.asp');
$headers = $request->getHeaders();
$cookie = $headers['Set-Cookie'];

$image = $http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/captcha/gerarCaptcha.asp');
$x = $image->getBody()->getContents();

echo base64_encode($x);

file_put_contents(__DIR__.'/z.png', $x);

dump($image->getBody()->getContents(), $cookie);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Captch</title>
</head>
<body>
	<label>Imagem<input type="text" name="" value="">
	<img src="z.png" alt="">
	</label>
</body>
</html>