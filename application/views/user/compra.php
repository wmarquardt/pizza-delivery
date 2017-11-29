<?php if( !$this->session->user_confirmado ):?>
  <div class="alert alert-danger">
    <p>A sua conta não foi confirmada, por favor, faça a confirmação através do link em seu e-mail. Caso não tenha recebido o e-mail <a style='color: #006699;' href="<?=base_url('confirma_email')?>">clique aqui</a> para enviá-lo novamente.</p>
  </div>
<?php endif;?>
<?php if ($this->session->email_confirm_enviado == "ok"): ?>
  <div class="alert alert-info">
    <p>E-mail de confirmação de cadastro enviado com sucesso! Confira a caixa de entrada do e-mail <?=$this->session->user_email?>. Se não encontrar lá, procure na caixa SPAM.<br>Adicione o endereço <?=config_item('system_email')?> aos endereços confiáveis em seu e-mail para sempre receber as nossas mensagens :)</p>
  </div>
  <?php $this->session->unset_userdata('email_confirm_enviado') ?>
<?php endif ?>
<div class="" ng-controller="compraCtrl" id='cont_compra'>
  <div ng-hide="mensagemSucessoPedido" class='container'>
    <div class="jumbotron no-show pedidosWelcome" id='divPedido'>
      <div class=' '>
        <h1>Bem vindo</h1>

        <p>Clique no botão <strong>+</strong> abaixo para adicionar itens ao seu pedido.</p>
      </div>
      
    </div>
  </div>
  <div ng-hide="!mensagemSucessoPedido">
    <div class="container alert alert-success">
      <h1>Pedido finalizado com sucesso</h1>
      <p>Verifique os dados do pedido acessando o menu "Meus Pedidos".<br><br> <a href='<?=base_url("/#/pedidos")?>' class='btn btn-sucess'>Clique aqui para ver os últimos pedidos</a></p>
    </div>
  </div>
  

  <div class="container" ng-show='itens_pedido'>
    <div class='panel panel-warning ' >
      <div class="panel-heading"><h2>PEDIDO</h2></div>
      <div class='panel-body'>
          <table  class='table table-striped table-hover table-responsive'>
            <tr class='table-header'> 
              <th width="10%">#</th>
              <th width="40%">Item</th>
              <th width="10%">Quant.</th>
              <th width="10%">Val.</th>
              <th width="10%">Subtotal.</th>
              <th width="20%">Ações</th>
            </tr>
            <tr ng-class='{linhaEdt : emEdicao && id_edicao == $index}' ng-repeat="item in itens_pedido" > 
              <td>{{$index+1}}</td>
              <td style='text-transform: uppercase;'>{{item.tipo}} <strong>{{item.nome}}</strong>
                <ul  ng-repeat="s in item.sabores">
                  <li class=''  title='{{s.descricao}}' style="font-size: 12px; line-height: 6px;">{{s.nome}}</li>
                </ul>
              </td>
              <td ng-hide='emEdicao && id_edicao == $index'>{{item.quantidade}}</td>
              <td ng-hide='!emEdicao || id_edicao != $index'> <input type="number" maxlength="4" min="1" class='form-control' name='item_edt.quantidade' ng-model='item_edt.quantidade' ng-change='alteraQuantEdtItem()'  value="{{item.quantidade}}" only-num> </td>
              <td>{{item.valor}}</td>
              <td>{{item.quantidade * item.valor}}</td>
              <td>
                <div ng-hide='emEdicao && id_edicao == $index'>
                  <button title='Remover' ng-disabled="carregandoItemEdicao || removendoItemListaPedido" ng-click="removeItemListaPedido($index)" class='btn btn-raised btn-default btn-remove'>
                    <i class="fa fa-trash"></i>
                  </button>
                  <button title='Editar' ng-disabled="carregandoItemEdicao || removendoItemListaPedido" ng-click="editaItemListaPedido($index)" class='btn btn-raised btn-default edita_itens_pedido_botao{{$index}}'>
                    <i class="fa fa-edit o-load"></i>
                    <i class='fa no-show i-load fa-circle-o-notch fa-spin'></i>
                  </button>
                </div>
                <div ng-hide='!emEdicao || id_edicao != $index'>
                  <button title='Concluir Edição' ng-disabled="carregandoItemEdicao || removendoItemListaPedido" ng-click="concluirEdicao()" class='btn btn-raised btn-success btn-remove'>
                    <i class="fa fa-check"></i>
                  </button>
                </div>
              </td>
            </tr><!--repeat do item-->
            
          </table>
      </div>
      <div class="panel-footer">
        <div class='container'>
            <div class='col-md-6'>
             <h1 style="color: #555; font-weight: bolder;">TOTAL:</h1>
            </div>
          <div class="col-md-6 ">
            <h1 style="color: #555; font-weight: bolder; text-align: right;" ui-money-mask>R$ {{total_pedido}}</h1>
            
          </div>
        </div> 
        
        <hr style="border-top: 1px solid #fff;">
        <div class='container'>
          <button data-toggle='modal' data-target='#modalFinaliza' ng-disabled="emEdicao" class="pull-right btn-raised btn btn-primary">Finalizar</button>
        </div> 
      </div>
    </div>
  </div>
  <form ng-submit="realizaCompra()">
    
  </form>

  <div>
  </div>
  <div class="modal modalPizza">
    <!-- add pizza -->
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 ng-hide="regedit" class="modal-title">Adicionar Pizza</h4>
        </div>
        <div class="modal-body bodyEscolhaPizza ">
          <div class="saborPizzaSelect no-show">
            <div class='saborPizzaSelect'>
              <div onclick='returnToaddPizzaModalForm()' class='btn btn-default'> <i class='fa fa-chevron-left'> Voltar </i></div>
              <h2>Selecione o sabor</h2>  
              <ul class="nav nav-tabs">     
                <li ng:repeat="cat in categorias">
                  <a ng-class='{active:$first}' href="#pizza{{cat.nome}}" onclick="return false;" data-toggle="tab">{{cat.nome}}</a>
                </li>
              </ul>
              <div id="myTabContent" class="tab-content"> 
                <div ng-class='{active:$first}' ng:repeat="cat in categorias" class="tab-pane" id="pizza{{cat.nome}}" style="padding-top: 20px;">
                    <div class='alert alert-info'>Selecione o sabor na lista abaixo, clique sobre o sabor desejado e em seguida no botão Adicionar. Você também pode alternar entre as categorias no menu acima.</div>
                    <h4>Categoria: <strong>{{cat.nome}}</strong></h4>
                    <div class="form-group form-group-lg label-floating is-empty">
                      <label for='busca' class="control-label">Pesquisar...</label>
                      <input class="form-control busca_sabores_in" width="100%"  name='busca' ng-model="busca">
                    </div>
                    <div class="box_sabores_select">
                      <div ng:repeat="sab in lista_sabores[$index] | filter:busca"> 
                        <div  ng-dblclick="ProcessaAddSabor()" ng-click='selecionaSabor(sab.id)'  class='item_sabor sabor-lista sabor{{sab.id}}'> 
                          <strong>{{sab.nome}}</strong>
                          <p ng-show='sab.descricao' >{{sab.descricao}}</p>
                        </div>
                      </div>
                    </div>
                    <div ng-disabled="!id_sabor || unable_add_sabor" ng-click="ProcessaAddSabor()" class="pull-right btn btn-raised btn-primary">Adicionar <i class="fa fa-check"></i></div><br><br><hr>
                </div>
              </div>
            </div>
          </div>
          <div class='addPizzaModalForm'>
          
              <div class='row'>
                <div class="form-group form-group-lg label-floating is-empty col-md-6">
                  <select ng-change="atualizaOpcoesPizza()" id="tamanho" name="tamanho" class="form-control"  ng-model="pizza.tamanho"> 
                    <option value="">-- Clique aqui para selecionar o tamanho</option>                               
                      <option ng:repeat="tam in tamanhos" value="{{tam.id}}">
                          {{tam.nome}} {{tam.tamanho_cm}}cm / {{tam.num_sabores}} sabor(es) 
                      </option>
                  </select>
                </div>
                <div class="col-md-6">
                  <div ng-show='valor_pizza' style="font-size: 40px; text-align: right; font-weight: bolder;">
                     R$ {{valor_pizza}}
                     <p style="text-transform: uppercase; font-size: 12px; color: #aaa;"><small>valor unitário R$ {{valor_pizza_base}}</small></p>
                  </div>
                </div>
              </div>
              <input id="qnt_sabores" type='hidden' value="{{qnt_sabores}}"></input>
              <div ng-show='valor_pizza' id='detalhesPizza'>
                <div class='row'>
                  <div class='col-md-8'>
                    <div class="panel panel-primary box-sabores">
                        <div class="panel-heading">
                          <h3 class="panel-title">SABORES</h3>
                        </div>
                        <div id='sabores' class="panel-body">
                          <div class='lista_sabores'>
                            <!-- lista de sabores -->
                            <div  ng-repeat='sa in current_item_pizza.sabores'>
                              <div  class='box_lista_sabores_item'>
                                <div class='container'>
                                  <div class='col-md-10'>
                                    <strong>{{sa.nome}}</strong><br>
                                    <p style='font-size: 10px;'>{{sa.descricao}}</p>
                                  </div>
                                  <div ng-click="removerSabor(sa.id)" class="col-md-2 removeSabor"  title='Remover este sabor'>
                                    <i class='fa fa-remove' style="font-size: 20px; text-align: center;vertical-align: middle;"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <button ng-disabled='liberaCadastroNovoSabor()' ng-click='addSaborPizza()' class='btn btn-primary btn-raised pull-right'>Novo sabor <i class='fa fa-plus'></i></button>
                        </div>
                    </div>
                  </div>
                  <div class='col-md-4'>
                    <div class="panel panel-primary box-quantidade">
                        <div class="panel-heading">
                          <h3 class="panel-title">QUANTIDADE</h3>
                        </div>
                        <div id='quantidade' class="panel-body">
                          <center><input ng-change="calculaTotal()" type="number" maxlength="3" ng-model='qnt_field' id='pizzaQuantidade' name='pizza_quantidade' value='1'></center>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <button ng-click='addPizza()' ng-disabled="addPizzaForm.$invalid || !valor_pizza || adicionandoAoPedido " ng-hide="regedit" type="submit" class="btn btn-primary btn-block btn-raised">Adicionar ao pedido</button>
          </div>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
         
        </div>
      </div>
    </div>
    <!-- fim add pizza -->
  </div>

  <div class="modal modalCalzone">
    <!-- Add calzone -->
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 ng-hide='regedit' class="modal-title">Adicionar Calzone</h4>
        </div>
        <div class="modal-body bodyEscolhaCalzone ">
          <div class="saborCalzoneSelect no-show">
            <div class='saborCalzoneSelect'>
              <div onclick='returnToaddCalzoneModalForm()' class='btn btn-default'> <i class='fa fa-chevron-left'> Voltar </i></div>
              <h2>Selecione o sabor</h2>  
              <ul class="nav nav-tabs">     
                <li ng:repeat="cat in categoriasCalzone">
                  <a ng-class='{active:$first}' href="#calzone{{cat.nome}}" onclick="return false;" data-toggle="tab">{{cat.nome}}</a>
                </li>
              </ul>
              <div id="myTabContent" class="tab-content"> 
                <div ng-class='{active:$first}' ng:repeat="cat in categoriasCalzone" class="tab-pane" id="calzone{{cat.nome}}" style="padding-top: 20px;">
                    <div class='alert alert-info'>Selecione o sabor na lista abaixo, clique sobre o sabor desejado e em seguida no botão Adicionar. Você também pode alternar entre as categorias no menu acima.</div>
                    <h4>Categoria: <strong>{{cat.nome}}</strong></h4>
                    <div class="form-group form-group-lg label-floating is-empty">
                      <label for='busca' class="control-label">Pesquisar...</label>
                      <input class="form-control busca_sabores_in" width="100%"  name='busca' ng-model="busca">
                    </div>
                    <div class="box_sabores_select">
                      <div ng:repeat="sab in lista_sabores_calzone[$index] | filter:busca"> 
                        <div  ng-dblclick="ProcessaAddSaborCalzone()" ng-click='selecionaSabor(sab.id)'  class='item_sabor sabor-lista sabor{{sab.id}}'> 
                          <strong>{{sab.nome}}</strong>
                          <p ng-show='sab.descricao' >{{sab.descricao}}</p>
                        </div>
                      </div>
                    </div>
                    <div ng-disabled="!id_sabor || unable_add_sabor_calzone" ng-click="ProcessaAddSaborCalzone()" class="pull-right btn btn-raised btn-primary">Adicionar <i class="fa fa-check"></i></div><br><br><hr>
                </div>
              </div>
            </div>
          </div>
          <div class='addCalzoneModalForm'>
          
              <div class='row'>
                <div class="form-group form-group-lg label-floating is-empty col-md-6">
                  <select ng-change="atualizaOpcoesCalzone()" id="tamanhoCalzone" name="tamanhoCalzone" class="form-control"  ng-model="calzone.tamanhoCalzone"> 
                      <option value="">--Clique aqui para selecionar o tamanho</option>
                      <option ng:repeat="tam in tamanhosCalzone" value="{{tam.id}}">
                          {{tam.nome}} 
                      </option>
                  </select>
                </div>
                <div class="col-md-6">
                  <div ng-show='valor_calzone' style="font-size: 40px; text-align: right; font-weight: bolder;">
                     R$ {{valor_calzone}}
                     <p style="text-transform: uppercase; font-size: 12px; color: #aaa;"><small>valor unitário R$ {{valor_pizza_base}}</small></p>
                  </div>
                </div>
              </div>
              <input id="qnt_sabores" type='hidden' value="{{qnt_sabores}}"></input>
              <div ng-show='valor_calzone' id='detalhesCalzone'>
                <div class='row'>
                  <div class='col-md-8'>
                    <div class="panel panel-primary box-sabores">
                        <div class="panel-heading">
                          <h3 class="panel-title">SABORES</h3>
                        </div>
                        <div id='sabores' class="panel-body">
                          <div class='lista_sabores'>
                            <!-- lista de sabores -->
                            <div  ng-repeat='sa in current_item_calzone.sabores'>
                              <div  class='box_lista_sabores_item_calzone'>
                                <div class='container'>
                                  <div class='col-md-10'>
                                    <strong>{{sa.nome}}</strong><br>
                                    <p style='font-size: 10px;'>{{sa.descricao}}</p>
                                  </div>
                                  <div ng-click="removerSabor(sa.id)" class="col-md-2 removeSabor"  title='Remover este sabor'>
                                    <i class='fa fa-remove' style="font-size: 20px; text-align: center;vertical-align: middle;"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <button ng-disabled='liberaCadastroNovoSaborCalzone()' ng-click='addSaborCalzone()' class='btn btn-primary btn-raised pull-right'>Novo sabor <i class='fa fa-plus'></i></button>
                        </div>
                    </div>
                  </div>
                  <div class='col-md-4'>
                    <div class="panel panel-primary box-quantidade">
                        <div class="panel-heading">
                          <h3 class="panel-title">QUANTIDADE</h3>
                        </div>
                        <div id='quantidade' class="panel-body">
                          <center><input ng-change="calculaTotalCalzone()" type="number" maxlength="3" id='calzoneQuantidade' ng-model='qnt_field_calzone' name='calzone_quantidade' value='1'></center>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <button ng-click='addCalzone()' ng-disabled="addCalzoneForm.$invalid || !valor_calzone || adicionandoAoPedido" ng-hide="regedit" type="submit" class="btn btn-primary btn-block btn-raised">Adicionar ao pedido</button>
          </div>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
         
        </div>
      </div>
    </div>
    <!-- Fim add calzone -->
  </div>

  <div class="modal modalBebida">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Adicionar Bebida</h4>
        </div>
        <div class="modal-body">
          <center ng-hide="!total_bebida">
            <h1 >R${{total_bebida}}</h1>
          </center>
          <div class='col-md-10'>
            <p style="color: #666;">Bebida:</p>
            <select class="form-control lista_bebidas" ng-change='seleciona_bebida()' ng-model='bebida_select' ng-options="b.nome_bebida group by b.categoria for b in lista_bebidas">
              <option value="">--Selecione a bebida</option>
            </select>
          </div>
          <div class='col-md-2'>
            <p style="color: #666;">Quantidade:</p>
            <input type="number" ng-change='calculaTotalBebida()' ng-model='bebidas_quantidade' name='bebidas_quantidade' id='bebidas_quantidade' min='1' maxlength="4" name="bebidas_quantidade" class="form-control" value="{{bebidas_quantidade}}" only-num>
          </div>
          <div class='col-md-12'>
            <button ng-click='addBebida()' ng-disabled='!total_bebida || adicionandoAoPedido' type="submit" class="btn btn-primary btn-block btn-raised">Adicionar ao pedido</button>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button"  class="btn btn-default" data-dismiss="modal">Cancelar</button>
          
        </div>
      </div>
    </div>
  </div>


  <div class="modal modalFinaliza" id='modalFinaliza'>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">FINALIZAR PEDIDO</h4>
        </div>
        <div class="modal-body">
          <center>
              <i class="fa fa-check-circle" style="font-size: 120px; color: #666;"></i>
              <h3>Tem certeza que deseja finalizar o pedido?</h3>
              <hr>
              <p>Após a finalização não será possível editá-lo</p>
          </center>
          <div class="panel panel-default">
            <div class="panel-heading">
              Entrega (selecione a opção desejada)
            </div>
            <div class="panel-body">
              <div class='row'>
                <div class="col-md-6" style="border-right: 1px solid #ddd;">
                  <center>Entregar no meu endereço</center>
                  <center><button ng-click="entrega('entregar')" ng-class="{'btn-info':entregar}" class="btn  btn-default btn-raised">
                    <i class='fa fa-home'></i>
                  </button></center>
                </div>
                <div class="col-md-6">
                  <center>Retirar na <?=$empresa[0]->nome?></center>
                  <center><button ng-click="entrega('retirar')" ng-class="{'btn-info':retirar}" class="btn  btn-default btn-raised">
                    <i class='fa fa-car'></i>
                  </button></center>
                </div>
              </div>
              
              <div ng-hide='!entregar && !retirar' style='border-top: 1px solid #ddd; padding-top: 12px;' class='entregaInfo'>
                <div ng-hide="!entregar">
                  <h4 style='padding-bottom: 10px; border-bottom: 3px solid #009688;'>Dados da entrega</h4>
                  <br>
                  <div class='row'>
                    <div class='dados-endereco col-md-6'>
                      <p><strong>Nome:</strong>{{meus_dados.nome}}</p>
                      <p><strong>Endereço:</strong>{{meus_dados.rua}}, {{meus_dados.numero}}</p>
                      <p><strong>CEP:</strong>{{meus_dados.cep}}</p>
                    </div>
                    <div class='dados-endereco col-md-6'>
                      <p><strong>Cidade:</strong>{{meus_dados.cidade}}</p>
                      <p><strong>Bairro:</strong>{{meus_dados.bairro}}</p>
                    </div>
                    <span class='label label-info'>Tempo médio de espera: {{pizzaria_dados.tempo_entrega}} minutos</span>
                  </div>
                  <a href="<?=base_url('/#/user')?>">Este não é meu endereço</a>
                  <h5 style='padding-bottom: 10px; border-bottom: 3px solid #009688;'>Outras Opções</h5>
                  <p style="font-stretch: condensed; ">Pagamento</p>
                    <div class='row'>  
                      <div class="col-md-6">
                        <div>
                        <input type="radio" ng-click='trocoPara("dinheiro")''' ng-model="dados_entrega.tipo" name="tipo"  value='Dinheiro'>
                        Dinheiro
                      </div>
                      <div>
                        <input type="radio" ng-click='trocoPara("cartao")' ng-model="dados_entrega.tipo" name="tipo"  value='Cartão'>
                        Cartão
                      </div>
                    </div>
                    <div ng-hide='!trocopara' class='col-md-6' style="vertical-align: middle;">
                        <p>Troco para:</p>
                        <input class="form-control" type="number" ng-model='dados_entrega.troco_para' name="troco_para" min="{{dados_entrega.troco_para}}" value='{{dados_entrega.troco_para}}' only-num>
                    </div>

                    <div class='col-md-12'>
                      <span class="label label-danger">Valor da entrega: R${{dados_entrega.valor_entrega}}</span>
                      <span class="label label-warning">Valor total: R${{total_pedido + dados_entrega.valor_entrega}}</span>
                    </div>
                  </div>
                </div>
                <div ng-hide='!retirar'>
                  <h4 style='padding-bottom: 10px; border-bottom: 3px solid #009688;'>Dados da retirada</h4>
                  <div class='dados-endereco col-md-6'>
                      <p><strong>Endereço:</strong>{{pizzaria_dados.rua}}, {{pizzaria_dados.numero}}</p>
                      <p><strong>CEP:</strong>{{pizzaria_dados.cep}}</p>
                    </div>
                    <div class='dados-endereco col-md-6'>
                      <p><strong>Cidade:</strong>{{pizzaria_dados.cidade}}/{{pizzaria_dados.uf}}</p>
                      <p><strong>Bairro:</strong>{{pizzaria_dados.bairro}}</p>
                  </div>

                  <span class='label label-info'>Tempo médio de espera: {{pizzaria_dados.tempo_entrega}} minutos</span>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">

          <button type="button"  class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button ng-disabled='!entregar && !retirar' ng-click="finalizaPedido()" ng-disabled="finalizando" class="btn btn-raised btn-primary"  id='botaoFinaliza'>Finalizar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--<a href="javascript:void(0)" class="btn btn-primary btn-fab" style="position: fixed; right: 40px; bottom: 40px;"><i class="fa fa-plus" style="vertical-align: middle; margin-top: 15px;"></i></a>-->