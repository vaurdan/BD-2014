<?php
/**
 * Fazer Lance nos leilões em que está inscrito.
 */

require_once( "config.php" );
forcar_autenticacao();

get_header();

if (! isset( $_GET['licitar'] ) ):
?>
<h2>Os Meus Leilões</h2>
<p>Lista dos Leilões onde está inscrito. Aqui pode licitar nos leilões onde está inscrito e que estão a decorrer.</p>
<table class="ink-table bordered hover alternating">
	<thead>
	<tr>
		<th class="align-left">Dia</th>
		<th class="align-left">Empresa Leiloeira</th>
		<th class="align-left">Nome</th>
		<th class="align-left">Valor Base</th>
		<th class="align-left">Maior Licitação</th>
		<th class="align-left"></th>

	</tr>
	</thead>
	<tbody>
	<?php

	$query = $db->prepare("SELECT *
					FROM leilao NATURAL JOIN leilaor AS l, concorrente AS c
					WHERE l.lid = c.leilao AND c.pessoa = ?
					ORDER BY dia ASC");
	$query->execute( array( get_user()->nif ) );

	foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) {
		?>
		<tr>
			<td><?php echo $linha['dia']; ?></td>
			<td><?php echo $linha['nif']; ?></td>
			<td><?php echo $linha['nome']; ?> #<?echo $linha['nrleilaonodia']?></td>
			<td style="text-align: right;"><?php echo $linha['valorbase']; ?> €</td>
			<td style="text-align: right;"><?php echo (int)get_valor_maximo_licitacao($linha['lid']) ?> €</td>
			<td><a href="?licitar=<?php echo $linha['lid'] ?>">Licitar</a></td>
		</tr>
	<?}
	if( $query->rowCount() == 0) {
		echo "<tr width='100%'><td>Não existe nenhum leilão para apresentar.</td></tr>";
	}
	?>
	</tbody>
</table>
<?php else:
	$leilao = escape( $_GET['licitar'] );

	// Modo paranoico, verificar se o utilizador pode licitar neste leilão
	$query = $db->prepare("SELECT *
					FROM leilao NATURAL JOIN leilaor AS l, concorrente AS c
					WHERE l.lid = c.leilao AND c.pessoa = ? AND c.leilao = ?
					ORDER BY dia ASC");
	$query->execute( array( get_user()->nif, $leilao ) );

	if( $query->rowCount() != 1) {
		echo "<h1>Não está inscrito neste leilão</h1><p>Antes de licitar, inscreva-se primeiro. Não aceitamos espertalhões!</p>";
		get_footer();
		exit();
	}

	$resultado = $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $_POST['valor'] ) ) {

		$valor = escape( $_POST['valor'] );

		if( $valor <= get_valor_maximo_licitacao($leilao) ) {
			echo "<h2>Ocorreu um erro a licitar.</h2><p>";
			echo "O lance tem que ser superior à licitação mais alta.<br/>";
			echo "<a href='javascript:history.back()'>Voltar</a>";
			get_footer();
			exit();
		}

		if( $valor < get_valor_base_licitacao($leilao) ) {
			echo "<h2>Ocorreu um erro a licitar.</h2><p>";
			echo "O lance tem que ser superior ao valor base da licitação.<br/>";
			echo "<a href='javascript:history.back()'>Voltar</a>";
			get_footer();
			exit();
		}

		$query = $db->prepare("INSERT INTO lance (pessoa, leilao, valor) VALUES( ?, ? ,? )");
		$query->execute( array( get_user()->nif, $leilao, $valor ) );

		if($query->rowCount() != 1) {
			echo "<h2>Ocorreu um erro!</h2><p>";
			echo "Provavelmente já colocou essa licitação.<br/>";
			print_sql_error();
			echo "</p>";
		} else {
			echo "<h2>Lance no leilão {$resultado['nome']} registado!</h2>";
			echo "<p>O lance de $valor€ efectuado com sucesso.</p>";
		}


	} else {
		?>
		<h2>Licitar no Leilão <?php echo $resultado['nome'] ?></h2>

		<?php if (get_valor_maximo_licitacao($leilao) != false ) {
			?>
			<p>A licitação mais alta é de <?php echo get_valor_maximo_licitacao($leilao) ?>€.</p>
			<?php
		} else {
			?>
			<p>Sem licitações de momento. O valor base do leilão é de <?php echo get_valor_base_licitacao($leilao); ?>€.</p>
			<?php
		} ?>
		<form class="ink-form" method="post">
			<div class="control-group">
				<label for="licitacao">Valor</label>
				<div class="control">
					<input id="licitacao" name="valor" type="text" placeholder="Valor de Licitação">
				</div>
			</div>
			<div class="control-group">
				<div class="control">
					<input id="submit" name="submit" type="submit" value="Submeter">
				</div>
			</div>
		</form>
	<?php

	}
	?>

<?php
endif;
get_footer();