<div ng-controller="pedidosCtrl">
	<div class='container'>
		<h1>Meus Pedidos</h1>
	    <div class="jumbotron" ng-hide="has_pedidos">
			<div >
				<h3>Nenhum pedido realizado até o momento</h3>
			</div>
	    </div>
	    <div  ng-hide="!has_pedidos">
			<div >
				<p>Veja abaixo a relação de todos os seus pedidos registrados em nosso sistema.</p>
				<small>Clique sobre o pedido para exibir os detalhes.</small>
				<table class="table table-hover table-responsive">
					<tr class='table-header'>
						<th>#</th>
						<th>Data</th>
						<th>Status</th>
						<th>Valor Total</th>
						<th>Ver</th>
					</tr>
					<tr ng-class="[{'warning' : p.status==0},{'success' : p.status==1}, {'danger' : p.status==2}, {'warning' : p.status==3}]" ng-repeat='p in lista_pedidos'>
						<td style="width: 30%;vertical-align: middle">#000{{p.id}}</td>
						<td style="width: 30%;vertical-align: middle">{{p.dt_ped}}</td>
						<td style="width: 30%;vertical-align: middle">{{p.ped_st}}</td>
						<td style="width: 30%;vertical-align: middle">R${{p.total_total}}</td>
						<td style="width: 30%;margin-top: 0; padding-top: 0;"><button class='btn btn-default' ng-click="carregaDetalhesPedido(p.id)"><i class="fa fa-eye"></i></button> </td>
					</tr>
				</table>
			</div>
	    </div>
	</div>
    <div></div>
	<!-- Modal -->
<div id="modalDetalhes" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detalhes do pedido</h4>
      </div>	
      <div class="modal-body">
        <div class="alert alert-info">
        	<p>Veja abaixo alguns detalhes do pedido #000{{id_pedido_detalhe}}</p>
        </div>
        <div class='row'>
        	<div class="container" ng-hide='id_pedido_detalhe == detalhes.id'>
        		<center>Carregando<img src="<?=base_url('images/loading.gif')?>" alt="carregando"></center>
        	</div>
        	<div class="col-md-4" ng-hide="!detalhes.pizzas[0]">
        		<div class="panel panel-default">
        			<div class='panel-heading'>Pizzas</div>
        			<div class="panel-body">
        				<p style="border-bottom: 2px solid #ddd;" ng-repeat="d in detalhes.pizzas">
        					Tamanho: <strong>{{d.tamanho_desc}} ({{d.quantidade}})</strong>
        					<br>
        					<small style="font-size: 9px;" ng-repeat="s in d.sabores">
        						{{s.nome}}<span ng-hide="$last">, </span>
        					</small>
        					<br>
        					<small>
        						<strong>Valor: R${{d.valor}}</strong>
        						<strong class="pull-right">Total: R${{d.subtotal}}</strong>
        					</small>
        				</p>
        			</div>
        		</div>
        	</div>
        	<div class="col-md-4" ng-hide="!detalhes.calzones[0]">
        		<div class="panel panel-default">
        			<div class='panel-heading'>Calzones</div>
        			<div class="panel-body">
        				<p style="border-bottom: 2px solid #ddd;" ng-repeat="d in detalhes.calzones">
        					Tamanho: <strong>{{d.tamanho_desc}} ({{d.quantidade}})</strong>
        					<br>
        					<small style="font-size: 9px;" ng-repeat="s in d.sabores">
        						{{s.nome}}<span ng-hide="$last">, </span>
        					</small>
        					<br>
        					<small>
        						<strong>Valor: R${{d.valor}}</strong>
        						<strong class="pull-right">Total: R${{d.subtotal}}</strong>
        					</small>
        				</p>
        			</div>
        		</div>
        	</div>
        	<div class="col-md-4" ng-hide="!detalhes.bebidas[0]">
        		<div class="panel panel-default">
        			<div class='panel-heading'>Bebidas</div>
        			<div class="panel-body">
        				<p style="border-bottom: 2px solid #ddd;" ng-repeat="d in detalhes.bebidas">
        					{{d.categoria}}: <strong>{{d.nome}} ({{d.quantidade}})</strong>
        					<br>
        					
        					<small>
        						<strong>Valor: R${{d.valor}}</strong>
        						<strong class="pull-right">Total: R${{d.subtotal}}</strong>
        					</small>
        				</p>
        			</div>
        		</div>
        	</div>
        </div>
        <hr>
        <span class="pull-right label label-info" >Valor da entrega : R${{detalhes.valor_entrega}}</span><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>
</div>

