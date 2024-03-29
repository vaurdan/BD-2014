<?php
/**
 * Ficheiro de configuração
 */
$utilizador = exec("whoami");
define( "ABSPATH", __DIR__);
define( "SITEURL", "http://web.ist.utl.pt/~$utilizador/BD");

// Configuração do mySQL
$mysql_user = 'ist174197';
$mysql_pass = 'zjxp3945';
$mysql_db = 'ist174197';
$mysql_server = 'db.tecnico.ulisboa.pt';

// Inicializar sessões
session_start();

// Inicializar a variavel $db com a ligação ao servidor
try {
	$db = new PDO( "mysql:host=$mysql_server;dbname=$mysql_db", $mysql_user, $mysql_pass,
		array( PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ) );
	$db->exec("set names utf8");
} catch ( PDOException $e ) {
	die( "<strong>Falhou a ligacao ao servidor MySQL.</strong>" . $e->getMessage() );
}


require_once( "funcoes.php" );

// Incluir as classes
require_once( ABSPATH . "/classes/User.php" );