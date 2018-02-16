<?php 
namespace ConsultaSimples\Sintegra;

use \GuzzleHttp\Client;
use \Symfony\Component\DomCrawler\Crawler;
use \ConsultaSimples\sefaConsult;
/**
* COnsulta a sefa do maranhão
*/
class ConsultaMaranhao
{
	private $estado = 'MARANHÃO';

	public function getEstado()
	{
		return $this->estado;
	}

	public function getParams()
	{
		$http = new Client();
		$request = $http->request('GET', 'http://aplicacoes.ma.gov.br/sintegra/jsp/consultaSintegra/consultaSintegraFiltro.jsf');
		$cookie = $request->getHeaders()['Set-Cookie'][0];

		$headers = [
			'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
			'Accept-Encoding' => 'gzip, deflate',
			'Accept-Language' => 'en-US,en;q=0.9',
			'Connection' => 'keep-alive',
			'Cookie' => $cookie,
			'Host' => 'aplicacoes.ma.gov.br',
			'Referer:http' => '//aplicacoes.ma.gov.br/sintegra/jsp/consultaSintegra/consultaSintegraFiltro.jsf',
			'User-Agent' => $_SERVER['HTTP_USER_AGENT']
		];

		$requestImage = $http->request('GET', 'http://aplicacoes.ma.gov.br/sintegra/a4j/s/3_3_3.Finalorg.richfaces.renderkit.html.Paint2DResource/DATA/eAGNks9r1EAUx98Gav11KBaEIkJZpYLIBIuKUhd!dLUupFZcEazg8nYyTWadZMaZ2W606E0EBS9ePQgeRFAQPfkfSMHL!gkiiAcvInjUmYQqrggGQobwfd!v5703L77AmNFwROqEaE7TFaTMEM3ymOnr3JLUZoJcQJ7b2eZFZmRfU7a3lWHCmmix8W74YeLH81cBjLdgc6ebzEshdQvGOytSZ2j9KWU8Sd1pU2fAY5suwxaKNGXYFSyCsU7sbCzsiHq4iqHAPAmXuj1G7VzkKpQPvgF3oFYoqJ6a-0y5NwbluGd8WUEqaiozJXOWW9J2nuycFK6JNq4yfWX9TePR4!eLAQSRyxdozHnM2J-5bat5nrjcrcbVxKWHhZ0VGZdhm2mOgt!y5HOF8vH7XCQx!bwEEMwawgSJWIL05iKzqYxP8zx2phvsAdQiqGUWdpWuRchEWAnPFEozY7jMnfW!nC9hMqrecN4OUGiYqsbhIEZ1r8P1Bx-!H3oYlLrJX7rfuU!v3m9!XR4e9wpPUPdXApXfle9q1LCVKXHvyYm31559W!LhfkvbBvthZs8albnpC4tttzyWaDxFreuLlMucR2VpircB-homr5ZjIH7tbmfl-F8OL3!6vHttocRw3QUWSlqCA0sWNKqUUzPbtDDhe6hKqxujVDHgkIQ9o8JRhL9-nOXCakmctpg-efjogWMHp0u-Rv3!-OvFTwEHHNo_.jsf', [
			'headers' => $headers,
		]);
		$image = $requestImage->getBody()->getContents();

		$result = [
			'cookie' => $cookie,
			'img' => 'data:image/png;base64,'.base64_encode($image),
		];

		return $result;
	}

	public function consultar(string $input, string $cookie, string $solveCaptcha)
	{
		$http = new Client();
		$validator = new sefaConsult();

		$params = [
			'cnpj' => $input,
			'captcha' => $solveCaptcha,
			'cookie' => $cookie,
		];

		if (!$this->validarCnpj($input)) {
			//mudar os parametros de inscrição estadual
			$params['ie'] = $input;
			unset($params['cnpj']);
		}

		$headers = [
			'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
			'Accept-Encoding' => 'gzip, deflate',
			'Accept-Language' => 'en-US,en;q=0.9',
			'Connection' => 'keep-alive',
			'Cookie' => $cookie,
			'Host' => 'aplicacoes.ma.gov.br',
			'Referer:http' => '//aplicacoes.ma.gov.br/sintegra/jsp/consultaSintegra/consultaSintegraFiltro.jsf',
			'User-Agent' => $_SERVER['HTTP_USER_AGENT']
		];	

		$sefaConsult = $http->request('POST', 'http://aplicacoes.ma.gov.br/sintegra/jsp/consultaSintegra/consultaSintegraFiltro.jsf', [
			'headers' => $headers,
			'form_params' => $params
		]);

		$sefaResult = $sefaConsult->getBody()->getContents();

		$crawler = new Crawler($sefaResult);

		dump($crawler);	
	}
}