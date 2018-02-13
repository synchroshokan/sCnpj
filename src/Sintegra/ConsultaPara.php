<?php 
namespace ConsultaSimples\Sintegra;

use \GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use \ConsultaSimples\sefaConsult;
/**
 * Classe responsavel por fazer consulta no site da sefaz
 */
class ConsultaPara
{
	private $estado = "PARÁ";

	public function getState()
	{
		return "Pará";
	}
	/**
	 * Retorna imagem e cookie da sessão de uma vez
	 * @return [array] => Retorna um array, com duas strings, uma com uma string base64 e uma string com cookie da sessão
	 */
	public function getParams()
	{
		$result = array();

		$request = new Client();
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
		$result['img'] = 'data:image/png;base64,'.base64_encode($requestImg);

		return $result;
	}

	/**
	 * Faz consulta com site do sintegra
	 * @return [array] [quando feita com sucesso retorna um array com as seguinte chaves
	 * data_da_consulta
	 * cnpj
	 * inscricao_estadual
	 * uf
	 * razao_social
	 * logradouro
	 * numero
	 * complemento
	 * bairro
	 * municipio
	 * cep
	 * endereco_eletronico
	 * telefone
	 * atividade_economica
	 * data_da_inscricao_estadual
	 * situacao_cadastral_atual
	 * data_desta_situacao_cadastral
	 * observacoes
	 * regime_de_apuracao_de_icms
	 * ]
	 */
	public function consultar(string $input, string $cookie, string $solveCaptcha, $option = 1)
	{
		$validator = new sefaConsult();
		$request = new Client();

		$finalResult = array();

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
			'CNPJ' => $input,
			'OP' => $option,
			'CODIGO' => $solveCaptcha
		);

		if (!$validator->validarCnpj($input)) {
			$params['IE'] = $input;
			$params['OP'] = 3;
			unset($params['CNPJ']);
		}

		try {			
			$consulta = $request->post('https://app.sefa.pa.gov.br/Sintegra/consulta.do', [
				'headers' => $headers,
				'form_params' => $params
			]);

			// criação do link para consulta
			$crawler = new Crawler($consulta->getBody()->getContents());
			$consultLink = $crawler->filter('a');
			$link = $consultLink->attr('href');
			$sintegraLink = 'https://app.sefa.pa.gov.br'.$link;

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
				$value = $validator->limpaString($value);
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

			return $finalResult;	
		} catch (\Exception $e) {
			$message = new Crawler($e->getMessage());
			$error = $message->filter('title');
			echo $error->text();
			die();
		}
	}
}
