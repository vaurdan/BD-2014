<?php
/**
 * Visualizar os leilões em curso ou a iniciar e as respectivas concessões ou áres de expliração de recursos
 *  marinhos.
 */
require_once("config.php");

forcar_autenticacao();

get_header();
$dia_hoje = date("Y-m-d");

?>

	<!-- replace 'top' with 'bottom', 'left' or 'right' to reposition navigation -->
	<div id="myTabs" class="ink-tabs top all-100">
		<!-- If you're using 'bottom' positioning, put this div AFTER the content. -->
		<ul class="tabs-nav">
			<li><a class="tabs-tab" href="#curso">Em Curso</a></li>
			<li><a class="tabs-tab" href="#iniciar">A Iniciar</a></li>
			<li><a class="tabs-tab" href="#historico">Histórico</a></li>
		</ul>

		<!-- Now just place your content -->
		<div id="curso" class="tabs-content">
			<h2>Lista de Leilões em Curso</h2>
			<p>
				Leilões atualmente a decorrer. Hoje é <?php echo $dia_hoje ?>.
			</p>
			<table class="ink-table bordered hover alternating">
				<thead>
				<tr>
					<th class="align-left">Dia</th>
					<th class="align-left">Nº Leilão</th>
					<th class="align-left">Empresa Leiloeira</th>
					<th class="align-left">Nome</th>
					<th class="align-left">Valor Base</th>
					<th class="align-left">Final</th>

				</tr>
				</thead>
				<tbody>
					<?php

					$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
						WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
						AND CURDATE() > ADDDATE(l.dia, INTERVAL r.nrdias DAY)");
					$query->execute();

					foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) { ?>
						<tr>
							<td><?php echo $linha['dia']; ?></td>
							<td><?php echo $linha['nrleilaonodia']; ?></td>
							<td><?php echo get_nome($linha['nif']); ?></td>
							<td><?php echo $linha['nome']; ?></td>
							<td><?php echo $linha['valorbase']; ?>€</td>
							<td><?php echo $linha['final']; ?></td>
						</tr>
					<?}
					if( $query->rowCount() == 0) {
						echo "<tr width='100%'><td>Não existe nenhum leilão para apresentar.</td></tr>";
					}
					?>
				</tbody>
			</table>

		</div>
		<div id="iniciar" class="tabs-content">
			<h2>Lista de Leilões a Iniciarem-se</h2>
			<p>
				Leilões a iniciarem-se no futuro. Hoje é <?php echo $dia_hoje ?>.
			</p>
			<table class="ink-table bordered hover alternating">
				<thead>
				<tr>
					<th class="align-left">Dia</th>
					<th class="align-left">Nº Leilão</th>
					<th class="align-left">Empresa Leiloeira</th>
					<th class="align-left">Nome</th>
					<th class="align-left">Valor Base</th>
					<th class="align-left">Final</th>

				</tr>
				</thead>
				<tbody>
				<?php

				$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
						WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
						AND CURDATE() < l.dia)");
				$query->execute();

				foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) { ?>
					<tr>
						<td><?php echo $linha['dia']; ?></td>
						<td><?php echo $linha['nrleilaonodia']; ?></td>
						<td><?php echo get_nome($linha['nif']); ?></td>
						<td><?php echo $linha['nome']; ?></td>
						<td><?php echo $linha['valorbase']; ?>€</td>
						<td><?php echo $linha['final']; ?></td>
					</tr>
				<?}
				if( $query->rowCount() == 0) {
					echo "<tr width='100%'><td>Não existe nenhum leilão para apresentar.</td></tr>";
				}
				?>				</tbody>
			</table>
		</div>

		<div id="historico" class="tabs-content">
			<h2>Histórico de Leilões</h2>
			<p>
				Leilões que já acabaram. Serve de histórico.
			</p>
			<table class="ink-table bordered hover alternating">
				<thead>
				<tr>
					<th class="align-left">Dia</th>
					<th class="align-left">Nº Leilão</th>
					<th class="align-left">Empresa Leiloeira</th>
					<th class="align-left">Nome</th>
					<th class="align-left">Valor Base</th>
					<th class="align-left">Final</th>

				</tr>
				</thead>
				<tbody>
				<?php

				$query = $db->prepare("SELECT *, ADDDATE(l.dia, INTERVAL r.nrdias DAY) AS final  FROM leilao l, leilaor r
						WHERE (l.dia, l.nrleilaonodia, l.nif) = (r.dia, r.nrleilaonodia, r.nif)
						AND CURDATE() < ADDDATE(l.dia, INTERVAL r.nrdias DAY)");
				$query->execute();

				foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) { ?>
					<tr>
						<td><?php echo $linha['dia']; ?></td>
						<td><?php echo $linha['nrleilaonodia']; ?></td>
						<td><?php echo get_nome($linha['nif']); ?></td>
						<td><?php echo $linha['nome']; ?></td>
						<td><?php echo $linha['valorbase']; ?>€</td>
						<td><?php echo $linha['final']; ?></td>
					</tr>
				<?}
				if( $query->rowCount() == 0) {
					echo "<tr width='100%'><td>Não existe nenhum leilão para apresentar.</td></tr>";
				}
				?>
				</tbody>
			</table>
		</div>
	</div>

	<script>
		Ink.requireModules( ['Ink.Dom.Selector_1','Ink.UI.Tabs_1'], function( Selector, Tabs ){
			var tabsObj = new Tabs('#myTabs');
		});
	</script>


<?php

get_footer();