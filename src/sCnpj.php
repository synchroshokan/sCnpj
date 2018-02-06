<?php 
namespace sCnpj;

use \GuzzleHttp\Client;
use \sCnpj\loadTemplate;
use Symfony\Component\DomCrawler\Crawler;

class sCnpj
{
	private $cookie;
	private $http;
	private $template;

	function __construct()
	{
		$this->http = new Client();
		$this->template = new loadTemplate();
	}

	public function home()
	{
		$data = $this->getParams();
		$this->template->load('home', $data);
	}

	public function getParams()
	{
		$http = new Client();

		$request = $http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao3.asp')->getHeaders();
		$cookie = $request['Set-Cookie'][0];

		$requestImg = $this->http->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/captcha/gerarCaptcha.asp', [
			'headers' => [
				'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
				'Accept-Encoding' => ['gzip, deflate'],
				'Accept-Language'=> 'en-US,en;q=0.9',
				'Cache-Control' => 'max-age=0',
				'Connection' => 'keep-alive',
				'Cookie' => $cookie,
				'Host' => 'www.receita.fazenda.gov.br', 
				'Upgrade-Insecure-Requests' => 1
			]
		]);

		$image = $requestImg->getBody()->getContents();

		$data = array(
			'cookie' => $cookie,
			'img' => base64_encode($image),
			'i' => $image
		);

		return $data;
	}

	public function consultar()
	{
		$result = array();

		$cnpj = filter_input(INPUT_POST, 'cnpj');
		$cookie = filter_input(INPUT_POST, 'cookie');
		$solve = filter_input(INPUT_POST, 'solve');
		
		$request = $this->http->get('http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/valida.asp', [
			'headers' => [
				'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
				'Accept-Encoding' => 'gzip, deflate',
				'Accept-Language' => 'pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
				'Connection' => 'keep-alive',
				'Cookie' => $cookie,
				'Referer' => 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_solicitacao3.asp',
				'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
			], 
			'form_params' => [
				'origem' => 'comprovante',
				'cnpj' => $cnpj,
				'txtTexto_captcha_serpro_gov_br' => $solve,
				'submit1' => 'Consultar',
				'search_type' => 'cnpj'
			]
		]);

		$crawler = new Crawler($request->getBody()->getContents());

		if (strpos($crawler->html(), '<b>Erro na Consulta</b>') !== false)
		{
			echo 'Erro ao consultar. Confira se você digitou corretamente os caracteres fornecidos na imagem.';
			throw new \Exception('Erro ao consultar. Confira se você digitou corretamente os caracteres fornecidos na imagem.', 98);
		}

		if ($crawler->filter('body > div > table:nth-child(3) > tr:nth-child(2) > td > b > font')->count() > 0) {
			throw new \Exception('Erro ao consultar. O CNPJ informado não existe no cadastro.', 99);
		}

		$td = $crawler->filter('body > div > table:nth-child(3) > tr > td');
		foreach ($td->filter('td') as $td) {
			$td = new Crawler($td);
			if ($td->filter('font:nth-child(1)')->count() > 0) {
				$key = trim(preg_replace('/\s+/', ' ', $td->filter('font:nth-child(1)')->html()));
				switch ($key) {
					case 'NOME EMPRESARIAL': $key = 'razao_social';
					break;
					case 'TÍTULO DO ESTABELECIMENTO (NOME DE FANTASIA)': $key = 'nome_fantasia';
					break;
					case 'CÓDIGO E DESCRIÇÃO DA ATIVIDADE ECONÔMICA PRINCIPAL': $key = 'cnae_principal';
					break;
					case 'CÓDIGO E DESCRIÇÃO DAS ATIVIDADES ECONÔMICAS SECUNDÁRIAS': $key = 'cnaes_secundario';
					break;
					case 'CÓDIGO E DESCRIÇÃO DA NATUREZA JURÍDICA' : $key = 'natureza_juridica';
					break;
					case 'LOGRADOURO': $key = 'logradouro';
					break;
					case 'NÚMERO': $key = 'numero';
					break;
					case 'COMPLEMENTO': $key = 'complemento';
					break;
					case 'CEP': $key = 'cep';
					break;
					case 'BAIRRO/DISTRITO': $key = 'bairro';
					break;
					case 'MUNICÍPIO': $key = 'cidade';
					break;
					case 'UF': $key = 'uf';
					break;
					case 'SITUAÇÃO CADASTRAL': $key = 'situacao_cadastral';
					break;
					case 'DATA DA SITUAÇÃO CADASTRAL': $key = 'situacao_cadastral_data';
					break;
					case 'MOTIVO DE SITUAÇÃO CADASTRAL': $key = 'motivo_situacao_cadastral';
					break;
					case 'SITUAÇÃO ESPECIAL': $key = 'situacao_especial';
					break;
					case 'DATA DA SITUAÇÃO ESPECIAL': $key = 'situacao_especial_data';
					break;
					case 'TELEFONE': $key = 'telefone';
					break;
					case 'ENDEREÇO ELETRÔNICO': $key = 'email';
					break;
					case 'ENTE FEDERATIVO RESPONSÁVEL (EFR)': $key = 'ente_federativo_responsavel';
					break;
					case 'DATA DE ABERTURA': $key = 'data_abertura';
					break;
					default: $key = null;
					break;
				}

				if (!is_null($key)) {
					$bs = $td->filter('font > b');
					foreach ($bs as $b) {
						$b = new Crawler($b);
						$str = trim(preg_replace('/\s+/', ' ', $b->html()));
						$attach = htmlspecialchars_decode($str);
						if ($bs->count() == 1)
							$result[$key] = $attach;
						else
							$result[$key][] = $attach;
					}
				}
			}
		}

		dump($result);

		$this->template->load('resultadoCnpj', $result);
		return true;
	}

	public function sintegra()
	{
		$result = array();

		$request = $this->http;
		$cookie = $request->get('https://app.sefa.pa.gov.br/Sintegra/');
		$cookie = $cookie->getHeaders()['Set-Cookie'][0];

		$requestImg = $request->get('https://app.sefa.pa.gov.br/Sintegra/image/imagemAntiRobo/1.jpg', [
			'headers' => [
				'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
				'Accept-Encoding' => 'gzip, deflate, br',
				'Accept-Language' => 'en-US,en;q=0.9',
				'Cache-Control' => 'max-age=0',
				'Connection' => 'keep-alive',
				'Cookie' => $cookie,
				'Host' => 'app.sefa.pa.gov.br',
				'Upgrade-Insecure-Requests' => 1,
				'User-Agent' => $_SERVER['HTTP_USER_AGENT']
			]
		]);

		$requestImg = $requestImg->getBody()->getContents();

		$result['cookie'] = $cookie;
		$result['img'] = base64_encode($requestImg);

		$this->template->load('sintegra', $result);
	}

	public function consultaSintegra()
	{
		$request = $this->http;
		$finalResult = array();

		$cookie = $_POST['cookie'];
		$cnpj = $_POST['cnpj'];
		$solve = $_POST['solve'];

		$headers = [
			'Accept' => 'text/html, */*',
			'Accept-Encoding' => 'gzip, deflate, br',
			'Accept-Language' => 'en-US,en;q=0.9',
			'Connection' => 'keep-alive',
			'Content-Length' => '36',
			'Content-Type' => 'application/x-www-form-urlencoded',
			'Cookie' => $cookie,
			'Host' => 'app.sefa.pa.gov.br',
			'Origin' => 'https://app.sefa.pa.gov.br',
			'Referer' => 'https://app.sefa.pa.gov.br/Sintegra/',
			'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
			'X-Requested-With' => 'XMLHttpRequest'
		];

		$params = array(
			'CNPJ' => $cnpj,
			'OP' => 1,
			'CODIGO' => $solve
		);

		$consulta = $request->post('https://app.sefa.pa.gov.br/Sintegra/consulta.do', [
			'headers' => $headers,
			'form_params' => $params
		]);

		$crawler = new Crawler($consulta->getBody()->getContents());
		
		$consultLink = $crawler->filter('a');

		$c = $consultLink->attr('href');

		$sintegraLink = 'https://app.sefa.pa.gov.br'.$c;

		$sintegraHeaders = [
			'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
			'Accept-Encoding' => 'gzip, deflate, br',
			'Accept-Language' => 'en-US,en;q=0.9',
			'Connection' => 'keep-alive',
			'Cookie' => $cookie,
			'Host' => 'app.sefa.pa.gov.br',
			'Referer' => 'https://app.sefa.pa.gov.br/Sintegra/',
			'Upgrade-Insecure-Requests' => 1,
			'User-Agent' => $_SERVER['HTTP_USER_AGENT']
		];

		$result = $request->post($sintegraLink, [
			'headers' => $sintegraHeaders
		]);

		$sintegraCrawlerResult = new Crawler($result->getBody()->getContents());

		$titleField = $sintegraCrawlerResult->filter('.td-title3'); 
		$dataField = $sintegraCrawlerResult->filter('.td-conteudotwo');

		foreach ($dataField as $data) {
			$valueDataField[] = $data->nodeValue;
		}

		foreach ($titleField as $data) {
			$keyTitleField[] = $data->nodeValue;
		}

		$keyValues = array_map(function ($value) {
			$value = trim(preg_replace('/\s+/', '_', $value), ':');
			$value = str_replace('\t', '', $value);
			$value = str_replace('\n', '', $value);
			$value = strtolower($value);
			$value = $this->limpaString($value);
			return $value;
		}, $keyTitleField);

		$values = array_map(function ($value) {
			$value = trim(preg_replace('/\s+/', ' ', $value), ' ');
			$value = str_replace('\t', '', $value);
			$value = str_replace('\n', '', $value);
			return $value;
		}, $valueDataField);		

		foreach ($values as $key => $value) {
			$finalResult[$keyValues[$key]] = $value;
		}

		dump($finalResult);

		$this->template->load('resultSintegra', $finalResult);
	}

	public function limpaString($str) { 
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		return $str;
	}
}