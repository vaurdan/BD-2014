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
					<th class="align-left">Tipo</th>

				</tr>
				</thead>
				<tbody>
					<?php

					$query = $db->prepare("SELECT * FROM leilao WHERE dia <= ?");
					$query->execute( array( $dia_hoje ) );

					foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) { ?>
						<tr>
							<td><?php echo $linha['dia']; ?></td>
							<td><?php echo $linha['nrleilaonodia']; ?></td>
							<td><?php echo $linha['nif']; ?></td>
							<td><?php echo $linha['nome']; ?></td>
							<td><?php echo $linha['valorbase']; ?></td>
							<td><?php echo $linha['tipo']; ?></td>
						</tr>
					<?}?>
				</tbody>
			</table>

		</div>
		<div id="iniciar" class="tabs-content">
			<h2>Lista de Leilões a Iniciarem-se</h2>
			<p>
				Leilões a i. Hoje é <?php echo $dia_hoje ?>.
			</p>
			<table class="ink-table bordered hover alternating">
				<thead>
				<tr>
					<th class="align-left">Dia</th>
					<th class="align-left">Nº Leilão</th>
					<th class="align-left">Empresa Leiloeira</th>
					<th class="align-left">Nome</th>
					<th class="align-left">Valor Base</th>
					<th class="align-left">Tipo</th>

				</tr>
				</thead>
				<tbody>
				<?php

				$query = $db->prepare("SELECT * FROM leilao WHERE dia > ?");
				$query->execute( array( $dia_hoje ) );

				foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $linha) { ?>
					<tr>
						<td><?php echo $linha['dia']; ?></td>
						<td><?php echo $linha['nrleilaonodia']; ?></td>
						<td><?php echo $linha['nif']; ?></td>
						<td><?php echo $linha['nome']; ?></td>
						<td><?php echo $linha['valorbase']; ?></td>
						<td><?php echo $linha['tipo']; ?></td>
					</tr>
				<?}?>
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