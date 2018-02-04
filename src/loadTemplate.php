<?php 
namespace sCnpj;
/**
* Class to load templates
*/
class loadTemplate
{
	public function load($name, array $data = [])
	{
		include __DIR__.'/../template/template.php';
	}
	
	public function loadTemplate($name, array $data = [])
	{
		extract($data);
		include __DIR__.'/../template/'. $name .'.php';
	}
}