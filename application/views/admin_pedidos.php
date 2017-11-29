<?php $this->load->view('inc/header') ?>
<div ng-app='pedidos'>
	
	<div class="row" ng-controller="defaultCtrl">
		<div class='container' style="border-bottom: 1px solid #ddd; padding: 10px; background: #eee;">
			<div class="col-md-9" style="vertical-align: middle;"><h2>Pedidos recentes ({{num_registros}})</h2></div>
			<div class="col-md-3">
				<p>Listar últimos:</p>

				<select ng-model='num' ng-change="getPedidos()" class="form-control">
					<option value="10">10 pedidos</option>
					<option value="50">50 pedidos</option>
					<option value="100">100 pedidos</option>
					<option value="500">500 pedidos</option>
					<option value="1000">1000 pedidos</option>
					<option value="0">Todos (pode demorar)</option>
				</select>
			</div>
		</div>

		<!--tela de carregamento -->
		<div ng-show='carregando'>
			<center><h1>Carregando...</h1></center>
		</div>
		<!-- Tela de listagem -->
		<table ng-hide='carregando' class="table table-responsive table-hover">
			<tr class="table-header">
				<th>#</th>
				<th>Nome </th>
				<th>Data</th>
				<th>Hora</th>
				<th>Status</th>
				<th>Valor</th>
				<th>Entrega</th>
				<th>Ação</th>
			</tr>
			<tr id='linha_ped{{l.id}}' ng-class="[{'warning' : l.status==0},{'success' : l.status==1}, {'danger' : l.status==2}, {'info' : l.status==3}]" ng-repeat="l in lista">
				<td>000{{l.id}}</td>
				<td>{{l.nome}} <small title='E-mail não confirmado' ng-hide="l.confirmado == '1'">(não confirmado)</small><small title='E-mail confirmado' ng-hide="l.confirmado == '0'">(confirmado)</small></td>
				<td>{{l.data}}</td>
				<td>{{l.hora}}</td>
				<td>{{l.nome_status}}</td>
				<td>R${{l.total_total}}</td>
				<td>{{l.tipo_entrega}}</td>
				
				<td>
					<button ng-disabled="alterandoStatus" onclick="return false;" ng-click="verDetalhesPedido(l.id)" href='#' title='Ver detalhes' class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button>
					<button ng-disabled="alterandoStatus" onclick="return false;" ng-click="alteraStatus(l.id,2)" href='#' title='Definir como "Cancelado"' class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></button>
					<button ng-disabled="alterandoStatus" onclick="return false;" ng-click="alteraStatus(l.id,1)" href='#' title='Definir como "Concluído"' class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>
					<button ng-disabled="alterandoStatus" onclick="return false;" ng-click="alteraStatus(l.id,3)" href='#' title='Definir como "Em Produção"' class="btn btn-info btn-sm"><i class="fa fa-clock-o"></i></button>
				</td>
			</tr>
			<tr>
			</tr>
		</table>

	</div>
</div>
<?php $this->load->view('inc/footer') ?>