<!DOCTYPE html>
<html>
<head>
	<title>Alteração de senha</title>
	<meta name='author' value="William Marquardt <williammqt@gmail.com>">
	<!-- estas tags devem ser copiadas para o cabeçalho do site -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url('css/dist/main.canvas.min.css')?>">
	<script type="text/javascript">
		//variaveis para os JS
		var BASE_URL = "<?=base_url()?>";
	</script>

	<noscript><h1>Não é possível usar este recurso com o javascript desativado.</h1></noscript>
</head>

<body>
	<?php if ( @$mensagem_erro != "" ): ?>
		<div class="alert alert-danger">
			<?=$mensagem_erro?>
		</div>
	<?php endif ?>

	<div class="col-md-4 col-md-push-4" style="margin-top: 30px;">
		<form action="<?=base_url('recupera_senha/'.$token)?>" method="POST">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h5>Alterar a senha</h5>
				</div>
				<div class="panel-body">

					<div class="form-group form-group-lg label-floating is-empty">
						<p>Senha:</p>
						<input id='senha' class="form-control input-lg" name='senha' type="password" required>
					</div>

					<div class="form-group form-group-lg label-floating is-empty">
						<p>Repita a senha:</p>
						<input id='senha' class="form-control input-lg" name='senha2' type="password" required>
					</div>
				</div>
				<div class="panel-footer">
					<input value='Alterar a senha' type="submit" class="btn btn-primary btn-raised btn-block">
				</div>
			</div>
		</form>
	</div>
</body>
</html>