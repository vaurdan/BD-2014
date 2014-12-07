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

<form action="login.php" method="post">
	NIF:
	<input type="text" name="nif" /> <br/>
	PIN:
	<input type="password" name="pin" />
	<input type="submit" name="submit" />
</form>

<?php
get_footer();

