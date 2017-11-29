<!DOCTYPE html>
<html>
<head>
	<title>Compra de pizzas</title>
	<meta name='author' value="William Marquardt <williammqt@gmail.com>">
	<!-- estas tags devem ser copiadas para o cabeçalho do site -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url('css/dist/main.canvas.min.css')?>">
	<script type="text/javascript">
		//variaveis para os JS
		var BASE_URL = "<?=base_url()?>";
	</script>
	<script type="text/javascript" src="<?=base_url('js/dist/main.canvas.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('js/app/app.js')?>"></script>
	<noscript><h1>Não é possível usar este recurso com o javascript desativado.</h1></noscript>
	<!-- fim das tags a serem copiadas -->
</head>
<body>
<div class="overlay no-show">
	<center>
		<i  class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size: 200px; color: #555; vertical-align: middle; margin-top: 100px; "></i>
	</center>
</div>
			<!--menu fixo-->
				<nav class="navbar navbar-default inner_element no-show bar_t">
				  <div class="container-fluid">
				    <!-- Brand and toggle get grouped for better mobile display -->
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
				      <a class="navbar-brand" href="#"><?=$empresa[0]->nome?></a>
				    </div>
				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				      <ul class="nav navbar-nav">
				        <li class='menu_compra'><a href="#/compra">Fazer pedido</a></li>
				        <li class='menu_pedidos'><a href="#/pedidos">Meus pedidos</a></li>
				      </ul>
				      <ul class="nav navbar-nav navbar-right">
				      	<li class='menu_dados'><a href="#/user">Meus dados</a></li>
				        <li><a href="<?=base_url('sair')?>">Sair</a></li>
				      </ul>
				    </div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
				<!--menu fixo-->
			<div ng-app="compraPizzas"><!-- inicio do app -->
				<?php if ($this->session->user_confirmado_sucesso): ?>
					<div class="alert alert-success">
						<p>Obrigado por confirmar a sua conta :D</p>
					</div>
					
					<?php $this->session->unset_userdata('user_confirmado_sucesso'); ?>
				<?php endif ?>

				<?php if ($this->session->erro_token_email): ?>
					<div class="alert alert-danger">
						<p>Token inválido</p>
					</div>
					
					<?php $this->session->unset_userdata('erro_token_email'); ?>
				<?php endif ?>
				<?php if ($this->session->senha_alterada): ?>
					<div class="alert alert-success">
						<p>Senha alterada com sucesso</p>
					</div>
					
					<?php $this->session->unset_userdata('senha_alterada'); ?>
				<?php endif ?>
				
				
				<div ng-view></div>

			</div><!-- fim do app -->
		
			<footer class='container'>

				<center>&copy; <?=date('Y')?> - Desenvolvido por <a href="http://www.canvasstudio.com.br" target="_blank">Canvas Studio</a></center>
			</footer>
			<div class="kc_fab_wrapper inner_element no-show"></div>

</body>
</html>
