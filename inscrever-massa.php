<?php
/**
 * Inscrever-se depois de autenticado, num ou mais leiloes. É possível a inscricao em leiloes até ao momento em que este termina.
 * Utilizar transações
 */

require_once( "config.php");
forcar_autenticacao();

get_header();

//Se não estiver definido nenhum leilão por GET ou POST
if( !isset( $_POST['leilao'] ) && !isset( $_GET['leilao'] ) ) {
	?>
	<h2>Inscrever em Multiplos Leilões</h2>
	<p>Selecione quais os leilões se deseja inscrever, e clique no botão abaixo.</p>
	<form class="ink-form" method="post">
		<table class="ink-table bordered hover alternating">
			<thead>
			<tr>
				<th class="align-left">Dia</th>
				<th class="align-left">Nº Leilão</th>
				<th class="align-left">Empresa Leiloeira</th>
				<th class="align-left">Nome</th>
				<th class="align-left">Valor Base</th>
				<th class="align-left"></th>

			</tr>
			</thead>
			<tbody>
			<fieldset>
			<?php
			$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
						WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
						AND CURDATE() > ADDDATE(l.dia, INTERVAL r.nrdias DAY)");
			$query->execute();

			foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) {
				$chave = base64_encode( $linha['nif'] . "###" . $linha['nrleilaonodia'] . "###" . $linha['dia'] );
				?>
				<tr>
					<td><?php echo $linha['dia']; ?></td>
					<td><?php echo $linha['nrleilaonodia']; ?></td>
					<td><?php echo get_nome($linha['nif']); ?></td>
					<td><?php echo $linha['nome']; ?></td>
					<td><?php echo $linha['valorbase']; ?>€</td>
					<td><input type="checkbox" name="leilao[]" value="<?php echo $chave?>"></td>
				</tr>
			<?}
			if( $query->rowCount() == 0) {
				echo "<tr width='100%'><td>Não existe nenhum leilão para apresentar.</td></tr>";
			}
			?>
			</fieldset>
			</tbody>
		</table>
		<div class="control-group">
			<div class="control">
				<input id="submit" name="submit" type="submit" value="Inscrever">
			</div>
		</div>
	</form>
<?php
} else {
	// Iniciar transação
	$db->beginTransaction();
	// Preparação das queries
	$verificacao = $db->prepare( "SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
												WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
												AND (l.dia, l.nrleilaonodia, l.nif) = (?, ?, ?)
												AND CURDATE() > ADDDATE(l.dia, INTERVAL r.nrdias DAY)" );
	$insercao = $db->prepare( "INSERT INTO concorrente (pessoa,leilao) VALUES (?,?)" );

	?>
		<h2>Inscrição em Massa</h2>
	<?php
	foreach ( $_POST['leilao'] as $leilao) {

		//Descodificamos o base64
		$leilao = base64_decode( $leilao );
		//Partimos a string. formato NIF##NRLEILAODIA##DIA.
		$leilao = explode( "###", $leilao );
		//Preenchemos as variaveis
		list( $nif, $nrleilao, $dia ) = $leilao;

		// Modo paranoico. Vamos verificar se esta entrada é válida (data) e se está na base de dados.
		$verificacao->execute( array( $dia, $nrleilao, $nif ) );
		if ( $verificacao->rowCount() != 1 ) {
			// Se não existir, estamos perante uma inscrição invalida, ie, na data inválida, ou nao existe.
			// Vamos ignorar em vez de cancelar toda a query.
			continue;
		}

		$resultado = $verificacao->fetch( PDO::FETCH_ASSOC );

		// Inscrever o utilizador
		$insercao->execute( array( get_user()->nif, $resultado['lid'] ) );

		?>
		<?php if ( $insercao->rowCount() != 0 ): ?>
			<p> Inscrito com sucesso em <?php echo $resultado['nome'] ?> #<?php echo $resultado['lid'] ?></p>
		<?php else:
			// Vamos avisar o utilizador que não foi possivel inscrever, mas continuamos a inserir o resto,
			// não vale a pena fazer rollback.
			?>
			<p> Não foi inscrito em <?php echo $resultado['nome'] ?> #<?php echo $resultado['lid'] ?>.
				Já se encontra inscrito? :)</p>
		<?php endif;
	}
	// Tudo bom, vamos fazer commit.
	$db->commit();

}

get_footer();