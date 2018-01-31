<?php 
namespace sCnpj;

use \GuzzleHttp\Client;

class sCnpj
{
	private $cookie;
	private $http;

	function __construct()
	{
		$this->http = new Client();
	}

	public function getImg()
	{
		$request = $this->http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/captcha/gerarCaptcha.asp');
		$image = $request->getBody()->getContents();

		return $image;
	}

	public function getCookie()
	{
		$http = new Client();

		$request = $http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao.asp');
		$cookie = $headers['Set-Cookie'];
	}
}
