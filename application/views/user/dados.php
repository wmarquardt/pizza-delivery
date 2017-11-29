<div class='container' ng-controller="dadosCtrl">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li ng-class="active">
				<a onclick="return false;" href="#dados_gerais"  data-toggle="tab">Dados Gerais</a>
			</li>
			<li>
				<a onclick="return false;" href="#seguranca"  data-toggle="tab">Segurança</a>
			</li>
		</ul>
	
	<div class="tab-content">
		<div ng-class="active" class="tab-pane fade" id="dados_gerais">
			<h1>Dados cadastrais</h1>
			<hr>
			<form name="alteraForm" id="alteraForm">
			<div class='container'>
				<div class="col-md-6">
						<h3 class='tit-sep'>Dados básicos</h3>
						<label for='nome' class="control-label">Nome</label>
						<input class="form-control" id='nome' name="nome"  type="text"  ng-model='meus_dados.nome' ng-required='true'>
						<span class='error' ng-show="errorNome">{{errorNome}}</span>
						
						<label for='fone' class="control-label">Telefone</label>
						<input class="form-control" name="fone" id='fone' type="text" ng-model='meus_dados.fone' ng-required='true'>
						<span class='error' ng-show="errorFone">{{errorFone}}</span>
						
						<label for='email' class="control-label">E-mail</label>
						<input style="color: #ddd;" disabled="disabled" class="form-control" name="email" id='email' type="email" ng-model='meus_dados.email' ng-required='true'>
						<span class='error' ng-show="errorEmail">{{errorEmail}}</span>

						<label for='cpf' class="control-label">CPF (Apenas números)</label>
						<input style="color: #ddd;" disabled="disabled" class="form-control" name="cpf" id='cpf' type="text" maxlength="11" ng-model='meus_dados.cpf' ng-required='true'>
						<span class='error' ng-show="errorCPF">{{errorCPF}}</span>
					</div>
					<div class="col-md-6">
						<h3 class='tit-sep'>Dados de endereço</h3>
						<br>
						<label for='cidade' class="control-label">Cidade</label>
						<select id="cidade" name="cidade" class="form-control"  ng-model="meus_dados.cidade"> 
							<option value=""></option>                               
						    <option ng:repeat="cid in cidades" value="{{cid.id}}">
						        {{cid.nome}}
						    </option>
						</select>
						<span class='error' ng-show="errorCidade">{{errorCidade}}</span>
						
						<label for='rua' class="control-label">Rua</label>
						<input class="form-control" id='rua' name="rua" type="text" ng-model='meus_dados.rua' ng-required='true'>
						<span class='error' ng-show="errorRua">{{errorRua}}</span>
						
						<div class='col-md-3' style="padding: 0;">
							<label for='numero' class="control-label">Nº</label>
							<input class="form-control" id='numero' name="numero" type="text" ng-model='meus_dados.numero' ng-required='true'>
							<span class='error' ng-show="errorNumero">{{errorNumero}}</span>
						</div>
						<div class='col-md-9' style="padding: 0;">
							<label for='bairro' class="control-label">Bairro</label>
							<input class="form-control" name="bairro" type="text" ng-model='meus_dados.bairro' ng-required='true'>
							<span class='error' ng-show="errorBairro">{{errorBairro}}</span>
						</div><br><br><br><br>
						
						<label for='cep' class="control-label">CEP</label>
						<input class="form-control" id='cep' name="cep" type="text" ng-model='meus_dados.cep' ng-required='true'>
						<span class='error' ng-show="errorCEP">{{errorCEP}}</span>
						
						<label for='ponto_referencia' class="control-label">Ponto de referência</label>
						<input class="form-control" maxlength="50" id='ponto_referencia' name="ponto_referencia" type="text" ng-model='meus_dados.ponto_referencia' ng-required='false'>
					</div>
				</div>
				<button ng-click='gravaDadosGerais()' ng-disabled="alteraForm.$invalid || isDisabled" class='btn btn-block btn-primary btn-raised btn-lg'>Gravar</button>
			</form>
		</div>
		<div class="tab-pane fade" id="seguranca">
			<div class='panel panel-warning'>
				<div class='panel-heading'>Dados de segurança</div>
				<div class="panel-body">
					<form name='alteraFormSeg' id='alteraFormSeg'>
						<div class="form-group form-group-lg label-floating is-empty">
							<label for='senha' class="control-label">Senha atual</label>
							<input class="form-control" name="senha" type="password" id='senha' ng-model='sec_dados.senha' ng-required='true'>
							<span class='error' ng-show="errorSenha">{{errorSenha}}</span>
						</div>
						<div class="form-group form-group-lg label-floating is-empty">

							<label for='nova_senha' class="control-label">Nova senha</label>
							<input ng-='6' class="form-control" name="nova_senha" type="password" id='nova_senha' ng-model='sec_dados.nova_senha' ng-required='true'>
							<span class='error' ng-show="errorSenha">{{errorSenha}}</span>
						</div>
						<div class="form-group form-group-lg label-floating is-empty">
							<label for='nova_senha_confirma' class="control-label">Repita a senha</label>
							<input ng-='6' class="form-control" id='nova_senha_confirma' name="nova_senha_confirma" type="password"  ng-model='sec_dados.nova_senha_confirma' ng-required='true'>
							<span class='error' ng-show="errorSenhab">{{errorSenhab}}</span>
						</div>
						<button ng-click='gravaDadosSeguranca()' ng-disabled="alteraFormSeg.$invalid || isDisabled" class='btn btn-block btn-primary btn-raised btn-lg'>Alterar</button>
					</form>
				</div>
			</div>
			<br>
		</div>
	</div>


</div>
	
	
