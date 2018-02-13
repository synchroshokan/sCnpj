<?php 
namespace ConsultaSimples;

use \GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
/**
 * Classe responsavel por fazer consulta no site da sefaz
 */
class sefaConsult
{
	/**
	 * Seleciona estado para consulta a sefaz
	 * @param  string $stateString Inicial do estado
	 * @return [object]            Retorna o objeto com os metodos de consulta da sefaz do estado
	 */
	public function estado(string $stateString)
	{
		$state = strtolower($stateString);
		switch ($state) {
			case 'pa':
				return new \ConsultaSimples\Sintegra\ConsultaPara;
				break;
			default:
				throw new \Exception('Estado não encontrado');
				break;
		}
	}

	/**
	 * Remove simbolos da string de cnpj
	 * @param  [string] $input => input com a inscrição estadual ou cnpj 
	 * @return [string]        	  retorna string sanitizada
	 */
	public function cleanInput(string $input)
	{
		$cleanInput = str_replace('.', '', $input);
		$cleanInput = str_replace('/', '', $cleanInput);
		$cleanInput = str_replace('-', '', $cleanInput);

		return $cleanInput;
	}

	/**
	 * Verifica se input é um cnpj valido
	 * @param  [string] $input => string limpa
	 * @return [boolean]          TRUE/FALSE
	 */
	public function validarCnpj(string $input)
	{
		 if (!is_scalar($input)) {
            return false;
        }

        // Code ported from jsfromhell.com
        $cleanInput = preg_replace('/\D/', '', $input);
        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        if ($cleanInput < 1) {
            return false;
        }
        if (mb_strlen($cleanInput) != 14) {
            return false;
        }
        for ($i = 0, $n = 0; $i < 12; $n += $cleanInput[$i] * $b[++$i]);
        if ($cleanInput[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }
        for ($i = 0, $n = 0; $i <= 12; $n += $cleanInput[$i] * $b[$i++]);
        if ($cleanInput[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }
        return true;
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
