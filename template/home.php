<div>
	<button><a href="/sintegra">Inscrição estadual</a></button>
	<label>
		<form method="post" action="consultar">
			CNPJ: <input type="text" name="cnpj"><br>
			Captcha: <input type="text" name="solve">
			<img src="data:image/png;base64,<?php echo $img ?>">
			<input type="hidden" name="cookie" value="<?php echo $cookie ?>"> 
			<input type="submit" value="Mandar">
		</form>
	</label>
</div>