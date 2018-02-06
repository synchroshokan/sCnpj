<?php 
use Symfony\Component\DomCrawler\Crawler;

require __DIR__.'/../vendor/autoload.php';


function limpaString($str) { 
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    return $str;
}

$xml = <<<'XML'
 <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">\n
      <html>\n
        <head>\n
          <title>SINTEGRA/ICMS - Consulta P&#xFA;ublica</title>\n
          <link rel="stylesheet" type="text/css" href="css/principal.css"/>\n
        </head>\n
        <body>\n
      \t\t<div id="corpo">\n
      \t\t\t<div id="topo"/>\n
      \t\t\t<table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3" width="150px">Data da consulta:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo">\n
      \t      \t\t\t\t\t\n
      \t\t\t\t \t\t\t<strong>05/02/2018</strong>\t\t\n
      \t      \t\t\t\t</td>\n
      \t    \t\t\t</tr></table><div id="conteudo">\t\n
      \t\t\t\t<label class="font-title">IDENTIFICA&#xC7;&#xC3;O</label>\t\t\t\t\n
      \t\t\t\t<table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3">CNPJ:</td>\n
      \t     \t\t\t\t<td class="td-conteudotwo">05.020.839/0001-05&#xA0;</td>\n
      \t     \t\t  \t\t<td class="td-title3">Inscri&#xE7;&#xE3;o Estadual:</td>\n
      \t     \t\t\t\t<td class="td-conteudotwo">15.111.380-7&#xA0;</td>\n
      \t    \t\t\t\t<td class="td-title3">UF:</td> \n
      \t    \t\t\t\t<td class="td-conteudotwo">PA&#xA0;</td>\n
      \t      \t\t\t</tr><tr><td class="td-title3">Raz&#xE3;o Social:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo" colspan="5">URNAS MART LTDA&#xA0;</td>\n
      \t      \t\t\t</tr></table><br/><label class="font-title">ENDERE&#xC7;O</label>    \n
      \t \t\t\t<table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3">Logradouro:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo" colspan="5">TRV PEDRO MESQUITA&#xA0;</td>\n
      \t    \t\t\t</tr><tr><td class="td-title3">N&#xFA;mero:</td>\n
      \t\t\t\t\t    <td class="td-conteudotwo">1260&#xA0;</td>\n
      \t\t\t\t\t    <td class="td-title3">Complemento:</td>\t\t\t\t    \n
      \t\t\t\t\t    <td class="td-conteudotwo">PEDRO MESQUITA&#xA0;</td>\t\t\t\t    \n
      \t\t\t\t\t    <td class="td-title3">Bairro:</td>\n
      \t\t\t\t\t    <td class="td-conteudotwo">CENTRO&#xA0;</td>\n
      \t\t\t\t    </tr></table><table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3">UF:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo">PA&#xA0;</td>\n
      \t\t\t\t\t\t<td class="td-title3">Munic&#xED;pio:</td>\n
      \t    \t\t\t\t<td class="td-conteudotwo">MARITUBA&#xA0;</td>  \n
      \t    \t\t\t\t<td class="td-title3">CEP:</td>\n
      \t    \t\t\t\t<td class="td-conteudotwo">67200000&#xA0;</td>\n
      \t    \t\t\t</tr></table><table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3" width="150px">Endere&#xE7;o Eletr&#xF4;nico:</t ▶
      \t      \t\t\t\t<td class="td-conteudotwo">sefa.obrigacao@gmail.com&#xA0;</td>\n
      \t      \t\t\t</tr><tr><td class="td-title3" width="150px">Telefone:</td>\n
      \t    \t\t\t\t<td class="td-conteudotwo">\n
      \t    \t\t\t\t\t(91) 32660088&#xA0;\n
      \t    \t\t\t\t</td>\n
      \t    \t\t\t</tr></table><br/><label class="font-title">INFORMA&#xC7;&#xD5;ES COMPLEMENTARES</label>\n
      \t \t\t\t<table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3" width="190px">Atividade Econ&#xF4;mica:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo"><strong>Principal:</strong>\n
      \t      \t\t\t\t\t<br/> 3101200 - Fabrica&#xE7;&#xE3;o de m&#xF3;veis com predomin&#xE2;ncia de madeira&#xA0;\n
      \t      \t\t\t\t\t\n
      \t\t      \t\t\t\t\t<p><strong>Secund&#xE1;rio:</strong>\n
      \t\t      \t\t\t\t\t<br/>\n
      \t\t      \t\t\t\t\t\n
      \t\t      \t\t\t\t\t\t- Com&#xE9;rcio atacadista de madeira e produtos derivados<br/></p></td>\n
      \t    \t\t\t</tr><tr><td class="td-title3" width="190px">Data da Inscri&#xE7;&#xE3;o Estadual:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo">01/06/1984&#xA0;</td>\n
      \t    \t\t\t</tr></table><table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3" width="190px">Situa&#xE7;&#xE3;o Cadastral Atua ▶
      \t      \t\t\t\t<td class="td-conteudotwo">\n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t\t\t\n
      \t      \t\t\t\t\t\t\n
      \t      \t\t\t\t\t\t\n
      \t      \t\t\t\t\t\t\n
      \t      \t\t\t\t\t\t\t\n
      \t\t\t\t\t\t\t\t\t\tHabilitado\n
      \t\t\t\t\t\t\t\t\t\t\n
      \t\t\t\t\t\t\t\t\t\t\n
      \t\t\t\t\t\t\t\t\t\t\n
      \t\t\t\t\t\t\t\t\t\n
      \t\t\t\t\t\t\t\t\t\n
      \t      \t\t\t\t\t\t\n
      \t      \t\t\t\t\t\t\n
      \t      \t\t\t\t\t&#xA0;\n
      \t\t\t\t\t\t</td>\n
          \t\t\t\t\t<td class="td-title3" width="200px">Data desta Situa&#xE7;&#xE3;o Cadastral:</td>\n
      \t    \t\t\t\t<td class="td-conteudotwo">01/01/2017&#xA0;</td>\n
      \t    \t\t\t</tr></table><table width="670" border="1" cellspacing="0" cellpadding="3"><tr><td class="td-title3" width="200px">Observa&#xE7;&#xF5;es:</td>\t\t\n
      \t      \t\t\t\t<td class="td-conteudotwo"> \n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t\t\t-EMPRESA OBRIGADA A EMISS&#xC3;O DA NF-e DESDE \n
      \t      \t\t\t\t\t\t01/07/2010.\n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t\t\n
      \t      \t\t\t\t&#xA0;\t\n
      \t      \t\t\t\t</td>\n
      \t    \t\t\t</tr><tr><td class="td-title3" width="200px">Regime de Apura&#xE7;&#xE3;o de ICMS:</td>\n
      \t      \t\t\t\t<td class="td-conteudotwo">Simples Nacional&#xA0;</td>\n
      \t    \t\t\t</tr></table><center><p>\n
      \t  \t\t\t\t<a href="ajuda.html" target="_blank">\n
      \t\t\t\t\t\tEsclarecimento quanto as situa&#xE7;&#xF5;es da consulta do SINTEGRA\n
      \t\t\t\t\t</a></p><p>\n
      \t  \t\t\t\t<a href="./index.jsp">Voltar para nova sele&#xE7;&#xE3;o de contribuinte (PA)</a></p><p>\n
      \t\t\t\t\t<a href="http://www.sintegra.gov.br/">Acessar cadastro de outro Estado</a>\t\t\t\t\t\n
      \t\t\t\t</p></center> \n
      \t\t\t</div>\n
      \t\t</div>\n
      \t</body>\n
      </html>\n
XML;
$array = array();
$array2 = array();
$selector = '.td-title3';

$crawler = new Crawler($xml);

$filter = $crawler->filter('.td-conteudotwo');

foreach ($filter as $e) {
	$array[] = $e->nodeValue;
}

$filter2 = $crawler->filter($selector);

foreach ($filter2 as $e2) {
	$array2[] = $e2->nodeValue;
}

$array2 = array_map(function ($array2) {
	$array2 = trim(preg_replace('/\s+/', '_', $array2));
	$array2 = str_replace('\t', '', $array2);
	$array2 = str_replace('\n', '', $array2);
	$array2 = strtolower($array2);
	return limpaString($array2);
}, $array2);

$array = array_map(function ($array) {
	$array = trim(preg_replace('/\s+/', ' ', $array));
	$array = str_replace('\t', '', $array);
	$array = str_replace('\n', '', $array);
	return $array;
}, $array);

for ($i=0; $i < count($array); $i++) { 
	$array3[$array2[$i]] = $array[$i];
}


dump($array3);

/**
$string = str_replace('\t', '', $filter->text());

$string = str_replace('\n', '', $string);

$string = trim(preg_replace('/\s+/', '', $string));

$array = explode(' ',$string);

for ($a=0; $a < count($array); $a++) { 
	echo $a .' - '.strlen($array[$a]). '<br>';
}

for ($a=0; $a < count($array); $a++) {
	if (strlen($array[$a]) < 1) {
		unset($array[$a]);
	}
}

echo "<hr>";
dump($filter, $filter->text(), $array);
*/