angular.module('pedidos', []);
angular.module('pedidos').controller('defaultCtrl', function($scope, $http){
	$scope.carregando = true;
	//obtem a tabela de pedidos (ultimos 100).
	$scope.num = '100';//numero inicial de pedidos carregados.
	$scope.lista = {};
	$scope.linha_atual = '93';
	$scope.alterandoStatus = false;

	$scope.getPedidos = function( ){
		$scope.carregando = true;
		$http({
	      method  : 'POST',
	      url     : BASE_URL+'admin/get_last_pedidos/'+$scope.num,
	      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
	     })
		  .success(function(data) {
		  	$scope.carregando = false;
		    $scope.isDisabled = false;
		    //aplica mensagens.
		    $scope.lista=data;
		    $scope.num_registros = data.length;
		  });
	}

	$scope.alteraStatus = function(id_reg, id_sta){
		$scope.alterandoStatus = true;
		$http({
	      method  : 'POST',
	      url     : BASE_URL+'admin/altera_status/'+id_reg+"/"+id_sta,
	      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
	     })
		  .success(function(data) {
		  	$scope.alterandoStatus = false;
		    $scope.getPedidos();
		  });
	}

	$scope.verDetalhesPedido = function(item){
		$('#detalhes').remove();
		console.log('Vendo detalhes '+item);	

		//remove se clicado em item que já está aberto
		if( $scope.linha_atual == item ){
			$scope.linha_atual = "0";
			return;
		}
		$scope.linha_atual = item;

		
		el = $('#linha_ped'+item);	


		
		//cria linha da tabela para exibir detalhes.
		h = "<tr id='detalhes' style='padding: 0;'>";
		h += "<td colspan=8 style='padding: 0;'>";
		h += "<div style='padding: 10px;border: 1px solid #006699;'>";
		h += "<div class='conteudo_detalhes row'><center><img src='"+BASE_URL+"images/loading.gif'></center></div>";
		h += "</div>";
		h += "</td>";
		h += "</tr>";
		el.after(h);

		//faz o carregamento dos detalhes via AJAX.
		//á moda antiga :D 
		el = $('.conteudo_detalhes');//novo elemento
		$http({
	      method  : 'POST',
	      url     : BASE_URL+'admin/get_pedidos_detalhes/'+item,
	      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
	     })
		  .success(function(data) {
		  	
		  	h = "<div class='container'>";
		  	////painel de pizzas
		  	h += "<div class='col-md-4'>"
		  	h += "<div class='panel panel-primary'>";
		  	h += "<div class='panel-heading'>Pizzas</div>";
		  	h += "<div style='padding: 5px;'>";
		  	for (var i = 0; i < data.pizzas.length; i++) {
		  		sabores = JSON.parse(data.pizzas[i].sabores);
		  		h += "<div style='border-bottom: 1px dotted #ddd;'><span><strong>"+data.pizzas[i].quantidade+" Pizza(s) "+data.pizzas[i].tamanho_desc+" / "+sabores.length+" Sabor(es)</strong></span><br>";
		  		for (var j = 0; j < sabores.length; j++) {
		  			h += "<span><small>"+sabores[j].nome+" / </small></span>";
		  		}
		  		h += "</div>";
		  	}
		  	//mostra que nao tem 
		  	if( data.pizzas.length == 0 ){
		  		h += "<center><strong>Nenhuma pizza.</strong></center>";
		  	}
		  	h += "</div>";
		  	h += "</div>";
		  	h += "</div>";

		  	//painel de calzones
		  	h += "<div class='col-md-4'>"
		  	h += "<div class='panel panel-primary'>";
		  	h += "<div class='panel-heading'>Calzones</div>";
		  	h += "<div style='padding: 5px;'>";
		  	for (var i = 0; i < data.calzones.length; i++) {
		  		sabores = JSON.parse(data.calzones[i].sabores);
		  		h += "<div style='border-bottom: 1px dotted #ddd;'><span><strong>"+data.calzones[i].quantidade+" Calzone(s) "+data.calzones[i].tamanho_desc+" / "+sabores.length+" Sabor(es)</strong></span><br>";
		  		for (var j = 0; j < sabores.length; j++) {
		  			h += "<span style='border-bottom: 1px dotted #ddd;'><small>"+sabores[j].nome+" / </small></span>";
		  		}
		  		h += "</div>";
		  	}
		  	//mostra que nao tem 
		  	if( data.calzones.length == 0 ){
		  		h += "<center><strong>Nenhum calzone.</strong></center>";
		  	}
		  	h += "</div>";
		  	h += "</div>";
		  	h += "</div>";

		  	//painel de bebidas
		  	h += "<div class='col-md-4'>"
		  	h += "<div class='panel panel-primary'>";
		  	h += "<div class='panel-heading'>Bebidas</div>";
		  	h += "<div style='padding: 5px;'>";
		  	for (var i = 0; i < data.bebidas.length; i++) {
		  		h += "<div style='border-bottom: 1px dotted #ddd;'><span><strong>"+data.calzones[i].quantidade+" -  "+data.bebidas[i].categoria+" - "+data.bebidas[i].nome+"</strong></span><br>";
		  		
		  		h += "</div>";
		  	}
		  	//mostra que nao tem 
		  	if( data.bebidas.length == 0 ){
		  		h += "<center><strong>Nenhuma bebida.</strong></center>";
		  	}
		  	h += "</div>";
		  	h += "</div>";
		  	h += "</div>";
		  	//fecha linha
		  	h += "</div>";
		  	h += "<hr>";
		  	//dados da entrega
		  	h += "<div class='container'>"
		  	if(data.tipo_entrega == "retirar"){
		  		h += "<span class='label label-info'>Cliente irá retirar</span>";
		  	}else{
		  		h += "<span class='label label-info'>Entregar</span>";
		  		h += "<span class='label label-primary'>Valor da entrega : R$"+data.valor_entrega+"</span>";
		  		h += "<span class='label label-warning'>Troco para : R$"+data.troco_para+"</span>"
		  	}
		  	h += "</div>";

		  	//aplica ao conteúdo
		  	el.html(h);
		  });
		
	}

	//faz o primeiro carregamento.
	$scope.getPedidos('carrega_atual');
});