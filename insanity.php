<?php
/**
 * Script para inserir entradas na base de dados.
 */
require_once("config.php");
error_reporting(E_ALL);
$utilizadores = array( "74197", "74143" );
$leiloes = array( 19, 21);

foreach( $leiloes as $leilao) {
	echo "<h2>Leilao $leilao</h2>";
	$bids = rand(100, 5000);

	$valor_bid = max( get_valor_base_licitacao($leilao), get_valor_maximo_licitacao($leilao) );
	var_dump($valor_bid);

	$query = $db->prepare("INSERT INTO lance (leilao, pessoa, valor) VALUES (?, ?, ?)");

	while( $bids > 0) {
		$utilizador = $utilizadores[ rand(0, sizeof($utilizadores)-1 ) ];
		$valor_bid = rand($valor_bid+1, $valor_bid+50);
		echo "<p>Selecionado o utilizador $utilizador. Colocar bid de $valor_bid euros. </p>";
		$query->execute( array($leilao, $utilizador, $valor_bid) );
		$bids--;
	}


}