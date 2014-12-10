<?php
/**
 * Inscrever-se depois de autenticado, num ou mais leiloes. É possível a inscricao em leiloes até ao momento em que este termina.
 */

require_once( "config.php");
forcar_autenticacao();

get_header();

//Se não estiver definido nenhum leilão por GET ou POST
if( !isset( $_POST['leilao'] ) && !isset( $_GET['leilao'] ) ) {
?>
<h2>Inscrever em Leilão</h2>
<p>Escolha abaixo o Leilão onde se quer inscrever.</p>
<form class="ink-form" method="post">
	<div class="control-group">
		<label for="email">Leilão</label>
		<div class="control">
			<select name="leilao">
				<?php
					$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
											WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
											AND CURDATE() > ADDDATE(l.dia, INTERVAL r.nrdias DAY)");
					$query->execute();

					foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) {
						$chave = base64_encode( $linha['nif'] . "###" . $linha['nrleilaonodia'] . "###" . $linha['dia'] );
						?>
						<option value="<?php echo $chave?>"><?php echo $linha['nome']?></option>
	                <?php } ?>
			</select>
		</div>
	</div>

	<div class="control-group">
		<div class="control">
			<input id="submit" name="submit" type="submit" value="Inscrever">
		</div>
	</div>

</form>
<?php
} else {

	$leilao = isset( $_POST['leilao'] ) ? $_POST['leilao'] : $_GET['leilao'];
	//Descodificamos o base64
	$leilao = base64_decode($leilao);
	//Partimos a string. formato NIF##NRLEILAODIA##DIA.
	$leilao = explode("###", $leilao);
	//Preenchemos as variaveis
	list($nif,$nrleilao,$dia) = $leilao;

	// Modo paranoico. Vamos verificar se esta entrada é válida (data) e se está na base de dados.
	$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
											WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
											AND (l.dia, l.nrleilaonodia, l.nif) = (?, ?, ?)
											AND CURDATE() > ADDDATE(l.dia, INTERVAL r.nrdias DAY)");
	$query->execute( array( $dia, $nrleilao, $nif ) );
	if ( $query->rowCount() != 1 ) {
		//Se não existir, estamos perante uma inscrição invalida, ie, na data inválida, ou nao existe.
		echo "<h2>Inscrição Inválida.</h2><p>O leilão onde está a tentar inscrever-se não existe ou não está aberto.</p>";
		get_footer();
		exit();
	}

	$resultado = $query->fetch( PDO::FETCH_ASSOC );

	// Inscrever o utilizador
	$query = $db->prepare("INSERT INTO concorrente (pessoa,leilao) VALUES (?,?)");
	$query->execute( array( get_user()->nif, $resultado['lid'] ) );

	?>
	<h2>Inscrição no Leilão de <?php echo $resultado['nome']?>. LID #<?php echo $resultado['lid']?></h2>
	<?php if( $query->rowCount() != 0 ): ?>
	<p> Foi inscrito com sucesso. Este leilão tem inicio a <?php echo $resultado['dia']?>, com uma duração de <?php echo $resultado['nrdias']?> dias.</p>
	<?php else: ?>
	<p> Ocorreu um erro na inscrição. Já se encontra inscrito? :)</p>
	<?php endif;

}

get_footer();