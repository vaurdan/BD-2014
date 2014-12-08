<?php
/**
 * Página de Login
 */
require_once("config.php");


// Vamos ver se foi feito uma tentativa de login

if ( isset( $_POST['submit'] ) ) {
	$_SESSION['username'] = escape( $_POST['nif'] );
	$_SESSION['pin'] = escape( $_POST['pin'] );

	if( esta_autenticado() ) {
		header("Location: index.php");
	}
}

get_header();
?>
<h2>Bem Vindo ao Super Leilões</h2>
<p>Para continuar, tem que iniciar sessão.</p>

	<form class="ink-form" method="post">
		<div class="control-group">
			<label for="email">NIF</label>
			<div class="control">
				<input id="nif" name="nif" type="text" placeholder="O SEU NIF">
			</div>
		</div>
		<div class="control-group">
			<label for="email">PIN</label>
			<div class="control">
				<input id="pin" name="pin" type="password" placeholder="O SEU PIN">
			</div>
		</div>
		<div class="control-group">
			<div class="control">
				<input id="submit" name="submit" type="submit" value="Submeter">
			</div>
		</div>

	</form>


<?php
get_footer();

