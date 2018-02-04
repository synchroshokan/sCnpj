<div>
	<button><a href="/sintegra">Inscrição estadual</a></button>
	<label>
		<form method="post" action="consultaSintegra">
			CNPJ: <input type="text" name="cnpj"><br>
			Captcha: <input type="text" name="solve">
			<img src="data:image/png;base64,<?php echo $img ?>">
			<input type="hidden" name="cookie" value="<?php echo $cookie ?>"> 
			<input type="submit" value="Mandar">
			<p><?php echo $cookie ?></p>
		</form>
	</label>
</div>