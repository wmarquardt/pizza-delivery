<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendas Online - Pizzas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script type="text/javascript">
    //variaveis para os JS
    var BASE_URL = "<?=base_url()?>";
  </script>
    
<?php 
if(isset($css_files)):
foreach($css_files as $file): ?>

	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach;
else:
  echo '<link type="text/css" rel="stylesheet" href="'.base_url('css/bootstrap.min.css').'" />';
endif;
?>

<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
<script src="<?=base_url('bower_components/jquery/dist/jquery.min.js')?>"></script>
<script src="<?=base_url('js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('bower_components/angular/angular.min.js')?>"></script>
<script src="<?=base_url('js/app/app.admin.js')?>"></script>
</head>
<body>
	<div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=base_url('admin')?>">Pizzas</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              
              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cadastros <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="dropdown-header">Bebidas</li>
                  <li><a href="<?=base_url('admin/bebidas_categorias')?>">Categorias</a></li>
                  <li><a href="<?=base_url('admin/bebidas')?>">Bebidas</a></li>

                  
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Pizzas</li>
                  <li><a href="<?=base_url('admin/categorias')?>">Categorias</a></li>
                  <li><a href="<?=base_url('admin/sabores')?>">Sabores</a></li>
                  <li><a href="<?=base_url('admin/tamanhos')?>">Tamanhos</a></li>
                  <li role="separator" class="divider"></li>

                  <li class="dropdown-header">Calzones</li>
                  <li><a href="<?=base_url('admin/cal_categorias')?>">Categorias</a></li>
                  <li><a href="<?=base_url('admin/cal_sabores')?>">Sabores</a></li>
                  <li><a href="<?=base_url('admin/cal_tamanhos')?>">Tamanhos</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Usuários</li>
                  <li><a href="<?=base_url('admin/clientes')?>">Clientes</a></li>
                  <li><a href="<?=base_url('admin/ruas')?>">Ruas (Taxa de entrega)</a></li>
                </ul>
              </li>
              <li><a href="<?=base_url('admin/pedidos')?>">Pedidos</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="<?=base_url('admin/meus_dados')?>">Meus dados</a></li>
              <li><a href="<?=base_url('admin/sair')?>">Sair</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

    <div style='height:20px;'></div>  
    <div>
