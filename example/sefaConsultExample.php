<?php 
use ConsultaSimples\sefaConsult;

require __DIR__.'/../vendor/autoload.php';

$sefa = new sefaConsult();

$params = $sefa->getParams();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Consulta Sefaz</title>
</head>
<body>
	<div>
		<form action="test.php" method="get" accept-charset="utf-8">
			<input type="text" name="cnpj" placeholder="cnpj">
			<input type="text" name="captcha" placeholder="captcha">
			<input type="text" disabled value="<?php echo $params['cookie'] ?>">
			<input type="hidden" name="cookie" value="<?php echo $params['cookie'] ?>">
			<img src="<?php echo $params['img'] ?>" alt="">
			<input type="submit" value="mandar">
		</form>
	</div>
</body>
</html>
<?php 
	if (isset($_GET['cnpj'])) {
		$cnpj = filter_input(INPUT_GET, 'cnpj');
		$cookie = filter_input(INPUT_GET, 'cookie');
		$solveCaptcha = filter_input(INPUT_GET, 'captcha');
		$result = $sefa->consultar($cnpj, $cookie, $solveCaptcha);
		/*dump*/var_dump($result);
	}
?>