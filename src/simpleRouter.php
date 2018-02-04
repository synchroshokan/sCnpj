<?php 
namespace sCnpj;

use \sCnpj\sCnpj;
/**
* Roteardor simples
*/
class simpleRouter
{
	private $c;

	function __construct() 
	{
		$this->c = new sCnpj;
	}

	public function run()
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = ltrim($url, '/');
		$route = $this->c;

		if (method_exists($route, $url)) {
			$route->$url();
			return true;
		}

		$route->home();
		return true;
	}
}