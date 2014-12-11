<?php
/**
 * Listar o estado dos leiloes em curso, apresentando os lances com maiores valores em cada leilão em que está
 *  inscrito e o tempo que falta para esse leilão fechar
 */

require_once( "config.php" );
forcar_autenticacao();

get_header();
?>

<h2>Informação dos Leilões a Decorrer</h2>

<?php
$dia_hoje = date("Y-m-d");

$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
						WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
						AND CURDATE() > ADDDATE(l.dia, INTERVAL r.nrdias DAY)");
$query->execute();

foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $leilao ) {
	$tempo_falta = strtotime($leilao['dia']) - strtotime($dia_hoje);
	$tempo_falta = date("j \d\i\a\s", $tempo_falta);
	?>
	<h3 style="margin-bottom: 0px;"><?php echo $leilao['nome'] ?></h3>
	<small>Colocado por <?php echo get_nome($leilao['nif'])?> a <?php echo $leilao['dia']?>. É o <?php echo $leilao['nrleilaonodia']?>º leilão do dia. </small>

	<p>Falta <?php echo $tempo_falta ?> para terminar o leilão.<br/>
	<?
	if( $licitacao = get_maior_licitacao( $leilao['lid'] ) ) {
		?>
		O maior lance foi colocado por <?php echo get_nome($licitacao['pessoa']) ?> com o valor de <?php echo $licitacao['valor'] ?>€.
		<?
	} else {
		?>
		Ainda não foi colocada nenhuma licitação. O valor base é de <?php echo $leilao['valorbase'] ?>€.
		<?
	}
}
?>


<?php
get_footer();
