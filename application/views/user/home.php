<div class="" ng-controller="inicialCtrl">
<div class="row">
	<div class='container jumbotron'>
			<center><h1 style="text-transform: uppercase; text-shadow: 4px 3px 11px #555;">Você já possui uma conta?</h1></center>
			<div class="col-md-6 " style="border-right: 1px solid #fff;">
				<div class="panel panel-primary">	
					<div class="panel-heading" style="margin: 0 auto;"> 
						<h3 class="panel-title"><center>SIM</center></h3> 
					</div>	
					<div class='panel-body'>			
						<center><p>Faça login abaixo:</p></center>	
						<div class="alert alert-info" ng-show="mensagemCadastro">
							{{mensagemCadastro}}
						</div>

						<div class="alert alert-danger" ng-show="erroLogin">
							{{erroLogin}}
						</div>
						<form name='loginForm' ng-submit="doLogin()">
							<div class="form-group form-group-lg label-floating is-empty">
								<label for='email' class="control-label">E-mail</label>
								<input id='email' class="form-control input-lg" name='email' ng-model='login.email' type="email" ng-required="true">
							</div>
							<div class="form-group form-group-lg label-floating is-empty">
								<label for='senha' class="control-label">Senha</label>
								<input id='senha' class="form-control input-lg" name='senha' ng-model='login.senha' type="password"  ng-required="true">
							</div>

							<input type="submit" ng-disabled="loginForm.$invalid || loginDisabled" class='btn btn-lg btn-raised btn-block btn-primary' value="Entrar">
						</form>
						<a data-toggle='modal' data-target='#modalEsqueciASenha' href="#modalEsqueciASenha" onclick="return false;" class="pull-right">Esqueceu a senha?</a>
						<p ng-show='loginOk'>Login efetuado, aguarde redirecionamento...<i class='fa fa-spin fa-spinner'></i></p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading" style="margin: 0 auto;"> 
						<h3 class="panel-title"><center>Não</center></h3> 
					</div>	
				
				<div class="panel-body">
					<center><p>Cadastre-se agora mesmo:</p></center>
					<a href="<?=base_url('#/cadastro/')?>" class='btn btn-lg btn-block btn-default btn-raised'>Cadastrar</a>	
				</div>
				
				</div>
			</div>
	</div>
</div>
<div class="modal modalEsqueciASenha" id='modalEsqueciASenha'>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">ESQUECI A SENHA</h4>
        </div>
        <div class="modal-body">
          	<p>Digite abaixo e-mail usado no cadastro:</p><br>
        	<form name="form_recupera" id="form_recupera">
        	<div class="form-group form-group-lg label-floating is-empty">
				<label for='esqueci' class="control-label">E-mail</label>
				<input id='esqueci' class="form-control input-lg" name='esqueci' ng-model='esqueci' type="email"  maxlength="70" ng-required="true">
			</div>
			<button ng-click='solicitaAlteracaoSenha()' ng-disabled="form_recupera.$invalid || isDisabled" class="btn btn-block btn-large btn-primary brn-raised">Solicitar recuperação</button>
			</form>
        </div>
        
        <div class="modal-footer">

          <button type="button"  class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>
