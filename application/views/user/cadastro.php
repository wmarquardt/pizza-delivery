<div ng-controller="cadastroCtrl">
<div class="row">
	<div class='container'>
			<center><h2>Preencha os campos abaixo para fazer seu cadastro</h2></center>
			<div class="col-md-4 col-md-push-4"><br><br>
				<form name='cadastroForm' ng-submit="submitForm()">
					<h3 class='tit-sep'>Dados básicos</h3>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='nome' class="control-label">Nome</label>
						<input class="form-control" id='nome' name="nome"  type="text" ng-model='cadastro.nome' ng-required='true'>
						<span class='error' ng-show="errorNome">{{errorNome}}</span>
					</div>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='email' class="control-label">E-mail</label>
						<input class="form-control" name="email" id='email' type="email" ng-model='cadastro.email' ng-required='true'>
						<span class='error' ng-show="errorEmail">{{errorEmail}}</span>
					</div>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='fone' class="control-label">Telefone</label>
						<input class="form-control" name="fone" id='fone' type="text" ng-model='cadastro.fone' ng-required='true'>
						<span class='error' ng-show="errorFone">{{errorFone}}</span>
					</div>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='cpf' class="control-label">CPF (Apenas números)</label>
						<input class="form-control" name="cpf" id='cpf' type="text" maxlength="11" ng-model='cadastro.cpf' ng-required='true'>
						<span class='error' ng-show="errorCPF">{{errorCPF}}</span>
					</div>
					<h3 class='tit-sep'>Dados de endereço</h3>
					<br>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='cidade' class="control-label">Cidade</label>
						<select id="cidade" name="cidade" class="form-control"  ng-model="cadastro.cidade"> 
							<option value=""></option>                               
						    <option ng:repeat="cid in cidades" value="{{cid.id}}">
						        {{cid.nome}}
						    </option>
						</select>
						<span class='error' ng-show="errorCidade">{{errorCidade}}</span>
					</div>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='rua' class="control-label">Rua</label>
						<input class="form-control" id='rua' name="rua" type="text" ng-model='cadastro.rua' ng-required='true'>
						<span class='error' ng-show="errorRua">{{errorRua}}</span>
					</div>
					<div class='col-md-3' style="padding: 0;">
						<div class="form-group form-group-lg label-floating is-empty">
							<label for='numero' class="control-label">Nº</label>
							<input class="form-control" id='numero' name="numero" type="text" ng-model='cadastro.numero' ng-required='true'>
							<span class='error' ng-show="errorNumero">{{errorNumero}}</span>
						</div>
					</div>
					<div class='col-md-9' style="padding: 0;">
						<div class="form-group form-group-lg label-floating is-empty">
							<label for='bairro' class="control-label">Bairro</label>
							<input class="form-control" name="bairro" type="text" ng-model='cadastro.bairro' ng-required='true'>
							<span class='error' ng-show="errorBairro">{{errorBairro}}</span>
						</div>
					</div><br><br><br><br>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='cep' class="control-label">CEP</label>
						<input class="form-control" id='cep' name="cep" type="text" ng-model='cadastro.cep' ng-required='true'>
						<span class='error' ng-show="errorCEP">{{errorCEP}}</span>
					</div>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='ponto_referencia' class="control-label">Ponto de referência</label>
						<input class="form-control" maxlength="50" id='ponto_referencia' name="ponto_referencia" type="text" ng-model='cadastro.ponto_referencia' ng-required='false'>
					</div>
					<h3 class='tit-sep'>Dados de segurança</h3>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='senha' class="control-label">Senha</label>
						<input class="form-control" name="senha" type="password" id='senha' ng-model='cadastro.senha' ng-required='true'>
						<span class='error' ng-show="errorSenha">{{errorSenha}}</span>
					</div>
					<div class="form-group form-group-lg label-floating is-empty">
						<label for='senhab' class="control-label">Repita a senha</label>
						<input class="form-control" id='senhab' name="senhab" type="password"  ng-model='cadastro.senhab' ng-required='true'>
						<span class='error' ng-show="errorSenhab">{{errorSenhab}}</span>
					</div>
					<br>
					<input type="submit" ng-disabled="cadastroForm.$invalid || isDisabled" class='btn btn-block btn-primary btn-raised btn-lg' value="Cadastrar">
					<span class='error' ng-show="errorForm">{{errorForm}}</span>
				</form> 
			</div>
	</div>
</div>
</div>