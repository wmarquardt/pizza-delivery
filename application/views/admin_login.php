<!DOCTYPE html>
<html>
<head>
    <title>Fazer login no sistema</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url('css/bootstrap.min.css')?>">
</head>
<body>
        <!-- from : http://bootsnipp.com/snippets/featured/login-amp-signup-forms-in-panel -->
        <div class="container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">      
        <?php 
            if( isset( $error ) ){
                echo "<div class='alert alert-danger'>".$error."</div>";
            }
        ?>             
            <div class="panel panel-primary" >
                    <div class="panel-heading">
                        <div class="panel-title">Entrar no sistema</div>
                    </div>     
                    <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form method="POST" id="loginform" class="form-horizontal" role="form" action="<?=base_url('admin/auth')?>">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="Nome de usuário" required='required'>                                        
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="Senha" required='required'>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                              <input id="btn-login" type='submit' class="btn btn-primary" value='Login'>
                            </div>
                        </div>
                    </form>     
                </div>                     
            </div>  
        </div>
    </div>
</body>
</html>