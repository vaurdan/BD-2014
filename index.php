<?php
/**
 * Ficheiro de index
 */

require_once('config.php'); //Incluir o ficheiro de configuração e o core da app

if( ! esta_autenticado() ) {
	header( "Location: login.php" );
	exit();
}

