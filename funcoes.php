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
	if ( $resultado[0] == 1 ) {
		obter_utilizador( $_SESSION['username'] );
		return true;
	}

	unset($_SESSION['username']);
	unset($_SESSION['pin']);
	$_SESSION['erro'] = "Número de Contríbuinte ou PIN inválido.";
	return false;
}

/**
 * Devolve o objecto do utilizador
 *
 * @return User|UserEmpresa utilizador
 */
function obter_utilizador($nif) {
	global $db, $user;

	if (! isset( $_SESSION['username'] ) ) {
		return false;
	}

	if ( is_object( $user ) && $user instanceof User )
		return $user;

	$query = $db->prepare( "SELECT COUNT(nif) FROM pessoac WHERE nif = ?" );
	$query->execute( array( $_SESSION['username'] ) );

	$resultado = $query->fetch( PDO::FETCH_NUM );
	if ( $resultado[0] == 1 ) {
		$user = new UserEmpresa( $nif );
	} else {
		$user = new User( $nif );
	}
	/* @var User|UserEmpresa */
	return $user;

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

function print_sql_error() {
	global $db;

	$errors = $db->errorInfo();
	echo($errors[2]);
}

/**
 * Devolve o utilizador ligado
 * @return bool|User Utilizador
 */
function get_user() {
	global $user;

	if( $user instanceof User) {
		return $user;
	}

	return false;
}

/**
 * Obriga ao utilizador estar autenticado.
 */
function forcar_autenticacao() {
	if( ! esta_autenticado() ) {
		header("Location: login.php");
		exit();
	}
}

function get_valor_maximo_licitacao( $lid ) {
	global $db;

	$query = $db->prepare( "SELECT max(valor) FROM lance WHERE leilao = ?" );
	$query->execute( array( $lid ) );

	if( $query->rowCount() == 0 )
		return false;


	$resultado = $query->fetch(PDO::FETCH_NUM);
	return $resultado[0];
}


function get_valor_base_licitacao( $lid ) {
	global $db;

	$query = $db->prepare( "SELECT valorbase FROM leilao WHERE (dia, nrleilaonodia, nif) = (SELECT DATE(dia), nrleilaonodia, nif FROM leilaor WHERE lid = ?)" );
	$query->execute( array( $lid ) );

	if( $query->rowCount() == 0 )
		return false;

	$resultado = $query->fetch(PDO::FETCH_ASSOC);
	return $resultado['valorbase'];
}

function get_maior_licitacao( $lid ) {
	global $db;

	$query = $db->prepare( "SELECT * FROM lance WHERE leilao = :lid and valor = (SELECT max(valor) FROM lance WHERE leilao = :lid);" );
	$query->execute( array( 'lid' => $lid ) );

	if( $query->rowCount() == 0 )
		return false;

	$resultado = $query->fetch(PDO::FETCH_ASSOC);
	return $resultado;
}