<?php
/**
 * Ficheiro de index
 */

require_once('config.php'); //Incluir o ficheiro de configuração e o core da app

if( ! esta_autenticado() ) {
	header( "Location: login.php" );
	exit();
}

get_header();
?>

<h2>Bem Vindo ao Super Leilões.</h2>
<p>Para continuar, selecione um dos menús.</p>
<?php
get_footer();

