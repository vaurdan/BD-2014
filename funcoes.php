<?php

/**
 * Verifica se o utilizador está autenticado.
 * @return bool
 */
function esta_autenticado() {
	global $db;
	if( ! isset( $_SESSION['username'] ) || ! isset( $_SESSION['pin'] ) )
		return false;

	$query = $db->prepare("SELECT COUNT(nif) FROM pessoa WHERE nif = ? AND pin = ?");
	$query->execute( array( $_SESSION['username'], $_SESSION['pin'] ) );

	// Verificamos quantas colunas devolveu
	$resultado = $query->fetch( PDO::FETCH_NUM );
	return $resultado[0] == 1;

}


function get_header() {
	include ABSPATH . "/theme/header.php";
}

function get_footer() {
	include ABSPATH . "/theme/footer.php";
}

/**
 * Função muito dummy de escaping.
 *
 * @param $string String a ser limpa
 *
 * @return string
 */
function escape( $string ) {
	return htmlspecialchars( stripslashes( trim( $string ) ) );
}