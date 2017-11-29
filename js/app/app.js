//inicia o app
angular.module('compraPizzas', ["ngRoute", "ngCookies"]);
angular.module('compraPizzas').config(function($routeProvider) {
  $routeProvider.when("/",{
    templateUrl: BASE_URL+"user/home"
  });
  $routeProvider.when("/cadastro",{
    templateUrl: BASE_URL+"user/cadastro"
  });
  $routeProvider.when("/compra",{
    templateUrl: BASE_URL+"user/compra",
  });
  $routeProvider.when("/pedidos",{
    templateUrl: BASE_URL+"user/pedidos",
  });
  $routeProvider.when("/user",{
    templateUrl: BASE_URL+"user/dados",
  });
});
angular.module('compraPizzas').directive('onlyNum', function() {
      return function(scope, element, attrs) {
         var keyCode = [8,9,37,39,48,49,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105,110];
          element.bind("keydown", function(event) {
            console.log($.inArray(event.which,keyCode));
            if($.inArray(event.which,keyCode) == -1) {
                scope.$apply(function(){
                    scope.$eval(attrs.onlyNum);
                    event.preventDefault();
                });
                event.preventDefault();
            }
        });
     };
  });
//registra o servico de mensagens (mensagens entre controllers)
angular.module('compraPizzas').factory('sharedMessages', function () {
    var message = {};
    return {
        getData: function () {
            //You could also return specific attribute of the form data instead
            //of the entire data
            return message;
        },
        setData: function (newMessage) {
            //You could also set specific attribute of the form data instead
            message = newMessage
        },
        resetData: function () {  
            //To be called when the data stored needs to be discarded
            message = {};
        }
    };
});
//controller inicial
angular.module('compraPizzas').controller('inicialCtrl',function($scope,$http, $location, $cookies, sharedMessages, $route, $templateCache){
  inner(false);
  //testa a rota atual
  $scope.isActive = function (path) {
      if ($route.current && $route.current.regexp) {
          return $route.current.regexp.test(path);
      }
      return false;
  };
  $scope.loginDisabled = false;
  $scope.loginOk = "";
  $scope.erroLogin = "";
  $scope.login = {};
  //obtem a mensagem de cadastro sando o servico de mensagens compartilhadas
  ///_log(sharedMessages.getData());
  var msg = sharedMessages.getData();
  $scope.mensagemCadastro = msg['cadastro_ok'];
  sharedMessages.resetData();
  //apaga o cookie
  //$cookies.put('cadastro_ok');
  //faz o login no sistema.
  $scope.doLogin = function(){
    $scope.loginOk = "";
    $scope.erroLogin = "";
    $scope.loginDisabled = true;
    $http({
    method  : 'POST',
    url     : BASE_URL+'ajax/login',
    data    : $scope.login, //forms cadastro object
    headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
   })
    .success(function(data) {
      //aplica mensagens.
      $scope.errorNome = data.errorNome;
      $scope.errorEmail = data.errorEmail;
      //_log(data);
      if(data == "1")
      {
        //faz o redirecionamento
        $scope.loginOk = "1";
        //if( !$scope.isActive('/compra') ){
        //  $location.path('/compra');
        //}else if( !$scope.isActive('/pedidos') ){
        //  $location.path('/pedidos');
        //}else{
          //apenas atualiza
          //depois de enviar o e-mail de confirmacao de cadastro.
          $http.get(BASE_URL+'user/confirma_email/ok').success(function($data){ 
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
          });
          
        //}
      }
      else
      {
        $scope.erroLogin = "Erro ao fazer login. Verifique usuÃ¡rio e senha e tente novamente.";
        $scope.loginDisabled = false;
      }
      _log(data);
    });
  }

  $scope.solicitaAlteracaoSenha = function(){
    _log('Solicitando alteraÃ§Ã£o de senha para: '+$scope.esqueci);
    $scope.isDisabled = true;
    $http({
      method  : 'POST',
      url     : BASE_URL+'ajax/geraTokenAltSenha',
      data    : {'dados' : $scope.esqueci}, //forms cadastro object
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        $scope.isDisabled = false;
        //aplica mensagens.
        if(data == "ok")
        {
          toastr.info('Enviamos um e-mail para vocÃª com instruÃ§Ãµes para alterar a sua senha.');
          $('#modalEsqueciASenha').modal('hide');
        }
        else
        {
          toastr.error('E-mail nÃ£o existe em nosso cadastro');
          $scope.esqueci  = '';
        }
      });
  }
});
//fim controller inicial
//controller de cadastro
angular.module('compraPizzas').controller('cadastroCtrl', function($scope, $http, $location, sharedMessages){
  inner(false);
  $scope.isDisabled = false;
  $scope.cidades = [];
  //obtem o nome das cidades disponiveis.
  $http.get(BASE_URL+'ajax/get_cidades').success(function($data){ 
    $scope.cidades=$data; 
  });
  //faz o cadastro
  $scope.cadastro = {};
  // calling our submit function.
  $scope.submitForm = function() {
    $scope.isDisabled = true;
    $scope.errorNome = "";
    // Posting data to php file
    $http({
      method  : 'POST',
      url     : BASE_URL+'ajax/cadastraUser',
      data    : $scope.cadastro, //forms cadastro object
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        //aplica mensagens.
        $scope.errorNome = data.errorNome;
        $scope.errorEmail = data.errorEmail;
        $scope.errorSenha = data.errorSenha;
        $scope.errorFone = data.errorFone;
        $scope.errorCPF = data.errorCPF;
        $scope.errorRua = data.errorRua;
        $scope.errorBairro = data.errorBairro;
        $scope.errorCidade = data.errorCidade;
        $scope.errorNumero = data.errorNumero;
        $scope.errorCEP = data.errorCEP;
        $scope.errorForm = "";
        $scope.removendoItemListaPedido = false;
        if(data == "ok")
        {
          //faz o redirecionamento
          var msg = { cadastro_ok : "Cadastro efetuado com sucesso, faÃ§a login abaixo." };
          sharedMessages.setData(msg);
          $location.path('/');
        }
        else
        {
          $scope.errorForm = "Corrija os erros e tente novamente";
          $scope.isDisabled = false;
        }
        _log(data);
      });
  };
});
//fim controller inicial
//
//controllers de compra
angular.module('compraPizzas').controller('compraCtrl', function($scope, $http, $location, sharedMessages){
  inner();
  $('.modal').modal('hide');//oculta modal se estiver aberto
  _log('habilitando compra');
  $scope.inicializa = function(){
    //declaracoes de variaveis de scope
    $scope.id_sabor = '';
    $scope.qnt_field = 1; 
    $scope.qnt_field_calzone = 1; 
    $scope.valor_pizza = '';
    $scope.valor_pizza_base = '';
    $scope.current_item_calzone = '';
    $scope.current_item_pizza = '';
    $scope.qnt_sabores = '';
    $scope.adicionandoAoPedido = false;
    $scope.carregandoItemEdicao = false;
    $scope.regedit = '';
    $scope.emEdicao = '';
    $scope.id_edicao = '';
    $scope.bebida_select = '';
    $scope.bebidas_quantidade = 1;
    $scope.total_bebida = '';
    $scope.finalizando = false;
    $scope.entregar = false;
    $scope.retirar = false;
    $scope.dados_entrega = {};
    $scope.trocopara = false;
    $scope.mensagemSucessoPedido = false;
    $scope.item_edt = {
      'quantidade' : 1
    }
    //variaveis de form
    $scope.calzone = {
      'tamanhoCalzone' : ''
    }
    $scope.pizza = {
      'tamanho' : ''
    }
  }
  $http.get(BASE_URL+'pedidos/get_pizza_tamanhos').success(function($data){ 
    $scope.tamanhos=$data; 
  });
  $http.get(BASE_URL+'pedidos/get_calzone_tamanhos').success(function($data){ 
    $scope.tamanhosCalzone=$data; 
  });
  //obtem a lista de categorias
  $http.get(BASE_URL+'pedidos/get_pizza_categorias').success(function($data){ 
    $scope.categorias=$data; 
  });
  //lista de categorias dos calzones
  $http.get(BASE_URL+'pedidos/get_calzone_categorias').success(function($data){ 
    $scope.categoriasCalzone=$data; 
  });
  //lista de sabores agrupados por categoria
  $http.get(BASE_URL+'pedidos/get_pizza_sabores_por_categorias').success(function($data){ 
    $scope.lista_sabores=$data; 
  });
  $http.get(BASE_URL+'pedidos/get_calzone_sabores_por_categorias').success(function($data){ 
    $scope.lista_sabores_calzone=$data; 
  });
  $http.get(BASE_URL+'pedidos/get_bebidas').success(function($data){ 
    $scope.lista_bebidas=$data; 
  });
  $http.get(BASE_URL+'pedidos/get_cadastro').success(function($data){ 
    $scope.meus_dados=$data; 
  });
  $http.get(BASE_URL+'ajax/get_pizzaria_dados').success(function($data){ 
    $scope.pizzaria_dados=$data; 
  });
  selecionaMenu('compra');
  carregandoPedido();
  /**
   * Seleciona a bebida na select
   * @date   2016-05-18T17:52:03-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.seleciona_bebida = function(){
    _log('Seleciona bebida'+$scope.bebida_select);
    $scope.calculaTotalBebida();
  }
  /**
   * Calcula valor total da bebida a ser adicionada
   * @date   2016-05-18T17:52:19-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.calculaTotalBebida = function(){
    _log('calculando total bebida '+$scope.bebidas_quantidade);
    if( $scope.bebidas_quantidade == null || $scope.bebidas_quantidade == "" )
      $scope.bebidas_quantidade = 1;
    //faz o calculo
    if( $scope.bebida_select != '' )
      $scope.total_bebida = $scope.bebida_select.valor * $scope.bebidas_quantidade;
    else
      $scope.total_bebida = '';
  }
  /**
   * Adiciona bebida no pedido
   * @date   2016-05-18T17:52:42-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.addBebida = function(){
    $scope.adicionandoAoPedido = true;
    //adiciona o pedido temporÃ¡rio ao bd
    $http({
      method  : 'POST',
      url     : BASE_URL+'pedidos/addBebidaToPedidoTemp',
      data   : {'item' : $scope.bebida_select, 'quantidade' : $scope.bebidas_quantidade},
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        $scope.atualizaPedido();
        $('.modalCalzone').modal('hide');
        $scope.adicionandoAoPedido = false;
        toastr.info('Item adicionado com sucesso.');
        //zera o item
        $scope.bebida_select = '';
        $scope.bebidas_quantidade = '';
        $scope.total_bebida = '';
        $('.modalBebida').modal('hide');
      }).error(function(){
        $scope.adicionandoAoPedido = false;
        toastr.error('erro ao processar carregamento do item no pedido');
      });
  }
  $scope.atualizaPedido = function(){
    $scope.itens_pedido = '';
    $http({
      method  : 'POST',
      url     : BASE_URL+'pedidos/get_tmp',
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        pedido_proc = JSON.parse(data[0].pedido);
        if(pedido_proc.length == 0)
        {
          //faz o redirecionamento
          _log('Nenhum item add');
          carregandoPedido(false);
          $('.pedidosWelcome').show(200);
        }
        else
        {
          //processa o pedido.
          $scope.itens_pedido = pedido_proc.itens;//informacoes de itens
          //calcula o total.
          valTot = 0;
          for(var i =0; i<$scope.itens_pedido.length; i++){
            valTot = valTot + ($scope.itens_pedido[i].quantidade * $scope.itens_pedido[i].valor);
          }
          $scope.total_pedido = valTot;
          _log($scope.itens_pedido);
          $('.pedidosWelcome').hide(300);
          carregandoPedido(false);
        }
      }).error(function(){
        _log('erro ao processar carregamento do pedido');
        carregandoPedido(false);
      });
  }
  /**
   * Atualiza o item de acordo com os registros da sessao
   * @date   2016-05-05T10:32:24-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.atualizaItemPizza = function(){
    //zera calzone
    $scope.current_item_calzone = '';
    $('.lista_sabores').append('<div class="lddtit"><center><img src="'+BASE_URL+'images/loading.gif"></center></div>');
    $http.get(BASE_URL+'pedidos/get_current_item').success(function($data){ 
      $scope.current_item_pizza=$data; 
      _log('current Pizza : '+$scope.current_item_pizza);
      //esconde carregamento
      $('.lddtit').remove();
    });
  } 
  /**
   * Atualiza o item de acordo com os registros da sessao
   * @date   2016-05-05T10:32:24-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.atualizaItemCalzone = function(){
    //zera pizza
    $scope.current_item_pizza = '';
    $('.lista_sabores').append('<div class="lddtit"><center><img src="'+BASE_URL+'images/loading.gif"></center></div>');
    $http.get(BASE_URL+'pedidos/get_current_item').success(function($data){ 
      $scope.current_item_calzone=$data; 
      _log('current calzone : '+$scope.current_item_calzone);
      //esconde carregamento
      $('.lddtit').remove();
    });
  } 
  $scope.removerSabor = function(id_remove){
    _log('Removendo sabor : '+id_remove);
    $http({
      method  : 'POST',
      url     : BASE_URL+'pedidos/remove_itens_sabores',
      data    : { 'id_remove' : id_remove },
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        _log("ret rem item dbg : "+data);
        if( data == "ok" ){
          toastr.success('Sabor removido com sucesso');
        }else{
          toastr.error('Erro ao remover sabor');
        }
        //verifica o tipo para definir o metodo
        //de atualizaÃ§Ã£o correto.
        if( $scope.current_item_calzone != '' ){
          _log('Remover sabor atualizando calzone');
          $scope.atualizaItemCalzone();
        }
        else if( $scope.current_item_calzone != '' ){
          _log('Remover sabor atualizando pizza');
          $scope.atualizaItemPizza();
        }
      }).error(function(){
        $scope.atualizaItem();
        toastr.error('Erro ao remover sabor');
      });
  }
  $scope.selecionaSabor = function(id_sab){
    _log("Sabor selecionado : "+id_sab);
    if( $scope.id_sabor == id_sab ){
      $('.sabor'+id_sab).removeClass('sabor-lista-selecionado');
      $scope.id_sabor = '';
    }else{
      $scope.id_sabor = id_sab;
      $('.item_sabor').removeClass('sabor-lista-selecionado');
      $('.sabor'+id_sab).addClass('sabor-lista-selecionado');
    }
  }
  $scope.ProcessaAddSabor = function(){
    if($scope.id_sabor != ''){
      $scope.unable_add_sabor = true;
      _log('Adicionando sabor...:' + $scope.id_sabor);
      //adiciona o sabor ao produto temporario
      $http({
        method  : 'POST',
        url     : BASE_URL+'pedidos/registra_item_sabores',
        data    : { 'id_sabor' : $scope.id_sabor },
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
       })
      .success(function(data) {
        $scope.id_sabor = '';
        if(data == 'MAXIMO')
        {
          //faz o redirecionamento
          $scope.atualizaItemPizza();
          returnToaddPizzaModalForm();
          $scope.id_sabor = '';
          toastr.error('VocÃª jÃ¡ adicionou o nÃºmero mÃ¡ximo de sabores para o tipo de pizza');
          carregandoPedido(false);
        }
        /*else if(data == 'JA_ADD') //removido
        {
          //faz o redirecionamento
          $scope.atualizaItemPizza();
          $scope.id_sabor = '';
          toastr.error('Este sabor jÃ¡ foi adicionado para esta pizza');
          carregandoPedido(false);
        }*/
        else{
          //faz o redirecionamento
          $scope.atualizaItemPizza();
          returnToaddPizzaModalForm();
          $scope.id_sabor = '';
          toastr.success('Sabor adicionado com sucesso!');
          carregandoPedido(false);
        }
        $scope.unable_add_sabor = false;
        $('.sabor-lista-selecionado').removeClass('sabor-lista-selecionado');
        $('.busca_sabores_in').val('');
      }).error(function(){
        $scope.atualizaItemPizza();
        $('.sabor-lista-selecionado').removeClass('sabor-lista-selecionado');
        $('.busca_sabores_in').val('');
        returnToaddPizzaModalForm();
        $scope.id_sabor = '';
        toastr.error('erro ao adicionar sabor');
        $scope.unable_add_sabor = false;
      });
    }
  }
  $scope.ProcessaAddSaborCalzone = function(){
    if($scope.id_sabor != ''){
      $scope.unable_add_sabor_calzone = true;
      _log('Adicionando sabor...:' + $scope.id_sabor);
      //adiciona o sabor ao produto temporario
      $http({
        method  : 'POST',
        url     : BASE_URL+'pedidos/registra_item_sabores',
        data    : { 'id_sabor' : $scope.id_sabor },
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
       })
      .success(function(data) {
        $scope.id_sabor = '';
        if(data == 'MAXIMO')
        {
          //faz o redirecionamento
          $scope.atualizaItemCalzone();
          returnToaddCalzoneModalForm();
          $scope.id_sabor = '';
          toastr.error('VocÃª jÃ¡ adicionou o nÃºmero mÃ¡ximo de sabores para o tipo de pizza');
          carregandoPedido(false);
        }
        /*else if(data == 'JA_ADD') //removido
        {
          //faz o redirecionamento
          $scope.atualizaItemCalzone();
          $scope.id_sabor = '';
          toastr.error('Este sabor jÃ¡ foi adicionado para esta pizza');
          carregandoPedido(false);
        }*/
        else{
          //faz o redirecionamento
          $scope.atualizaItemCalzone();
          returnToaddCalzoneModalForm();
          $scope.id_sabor = '';
          toastr.success('Sabor adicionado com sucesso!');
          carregandoPedido(false);
        }
        $scope.unable_add_sabor_calzone = false;
        $('.sabor-lista-selecionado').removeClass('sabor-lista-selecionado');
        $('.busca_sabores_in').val('');
      }).error(function(){
        $scope.atualizaItemCalzone();
        $('.sabor-lista-selecionado').removeClass('sabor-lista-selecionado');
        $('.busca_sabores_in').val('');
        returnToaddCalzoneModalForm();
        $scope.id_sabor = '';
        toastr.error('erro ao adicionar sabor');
        $scope.unable_add_sabor_calzone = false;
      });
    }
  }
  /**
   * Faz o calculo usando o valor base
   * @date   2016-05-04T10:54:43-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.calculaTotal = function(){
    _log($scope.qnt_field);
    if( $scope.qnt_field == null || $scope.qnt_field == "" )
      $scope.qnt_field = 1;
    var valor = $scope.valor_pizza_base;
    var quant = $scope.qnt_field;
    $scope.valor_pizza = valor * quant; 
    $http.get(BASE_URL+'pedidos/updatePrecoTmp/'+$scope.qnt_field);
  }
  /**
   * Faz o calculo do calzone usando o valor base
   * @date   2016-05-04T10:54:43-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.calculaTotalCalzone = function(){
    _log($scope.qnt_field_calzone);
    if( $scope.qnt_field_calzone == null || $scope.qnt_field_calzone == "" )
      $scope.qnt_field_calzone = 1;
    var valor = $scope.valor_calzone_base;
    var quant = $scope.qnt_field_calzone;
    $scope.valor_calzone = valor * quant; 
    $http.get(BASE_URL+'pedidos/updatePrecoTmp/'+$scope.qnt_field_calzone);
  }
  /**
   * Adiciona Calzone ao pedido
   * @date   2016-05-11T11:35:36-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.addCalzone = function(){
    _log('Adicionando Calzone');
    //verifica se tem mais de 1 sabor
    if( $('.box_lista_sabores_item_calzone').size() == 0 ){
      toastr.error('Adicione ao menos um sabor');
      return;
    }
    $scope.adicionandoAoPedido = true;
    //adiciona o pedido temporÃ¡rio ao bd
    $http({
      method  : 'POST',
      url     : BASE_URL+'pedidos/addItemToPedidoTemp',
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        $scope.atualizaPedido();
        $('.modalCalzone').modal('hide');
        $scope.adicionandoAoPedido = false;
        toastr.info('Item adicionado com sucesso.');
        //zera o tamanho
        $scope.zeraItens();
        $scope.valor_calzone = '';
        $scope.valor_pizza = '';
      }).error(function(){
        $scope.adicionandoAoPedido = false;
        _log('erro ao processar carregamento do item no pedido');
      });
  }
  //adiciona a pizza ao pedido.
  $scope.addPizza = function(){
    _log('Adicionando Pizza');
    //verifica se tem mais de 1 sabor
    if( $('.box_lista_sabores_item').size() == 0 ){
      toastr.error('Adicione ao menos um sabor');
      return;
    }
    $scope.adicionandoAoPedido = true;
    //adiciona o pedido temporÃ¡rio ao bd
    $http({
      method  : 'POST',
      url     : BASE_URL+'pedidos/addItemToPedidoTemp',
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        $scope.atualizaPedido();
        $('.modalPizza').modal('hide');
        $scope.adicionandoAoPedido = false;
        toastr.info('Item adicionado com sucesso.');
        //zera o tamanho
        $scope.zeraItens();
        $scope.valor_calzone = '';
        $scope.valor_pizza = '';
        //$scope.atualizaOpcoesPizza();
      }).error(function(){
        $scope.adicionandoAoPedido = false;
        _log('erro ao processar carregamento do item no pedido');
      });
  }
  /**
   * Zera os itens nos modais
   * @date   2016-05-12T09:48:25-0300
   * @author MARQUARDT, William <williammqt@gmail.com>
   */
  $scope.zeraItens = function(tipo){
    if( typeof(tipo) == 'undefined' )
      tipo = 'TODOS';
    _log('Zerando itens...:'+tipo );
    if( tipo == 'pizza' || tipo == 'TODOS' ){
      $scope.pizza = {
        'tamanho' : ''
      }
      $scope.qnt_field = 1; 
      $scope.valor_pizza_base = '';
      $scope.valor_pizza = '';
      //if( tipo == 'TODOS' )// apenas se for geral, para nÃ£o gerar loop
      //  $scope.atualizaOpcoesPizza();
    }else if( tipo == 'calzone' || tipo == 'TODOS' ){
      $scope.calzone = {
        'tamanhoCalzone' : ''
      }
      $scope.qnt_field_calzone = 1;
      $scope.valor_calzone_base = '';
      $scope.valor_calzone = '';
      //if( tipo == 'TODOS' )// apenas se for geral, para nÃ£o gerar loop
      //  $scope.atualizaOpcoesCalzone();
    }
  }
  $scope.addSaborPizza = function(){
    $('.addPizzaModalForm').hide(200);
    $('.saborPizzaSelect').show(200);      
    _log('novo sabor');
  }
  $scope.addSaborCalzone = function(){
    $('.addCalzoneModalForm').hide(200);
    $('.saborCalzoneSelect').show(200);      
    _log('novo sabor de calzone');
  }
  $scope.atualizaOpcoesCalzone = function(){
    _log('aplicando tamanho do calzone');
    ///zera pizza
    $scope.zeraItens('pizza');
    $('#detalhesCalzone').append('<div class="item_load"><center><img src="'+BASE_URL+'images/loading.gif"></center></div>');
    if($scope.calzone.tamanhoCalzone != ""){
      $http({
        method  : 'POST',
        url     : BASE_URL+'pedidos/registra_item',
        data    : {'tamanho' : $scope.calzone.tamanhoCalzone, 'tipo' : 'calzone'},
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
       })
        .success(function(data) {
          $('.item_load').remove();
          if(data.length == 0)
          {
            _log('erro ao processar item');
            toastr.error('Erro ao processar item');
            $('.modalCalzone').modal('hide');
          }
          else
          {
            _log(data);
            $scope.valor_calzone = data.valor;
            $scope.valor_calzone_base = data.valor;
            $scope.qnt_sabores = data.num_sabores;
            $scope.atualizaItemCalzone();
            $scope.calculaTotal();
          }
        }).error(function(){
          $('.item_load').remove();
          _log('erro ao processar carregamento do pedido');
          carregandoPedido(false);
        });
      }else{
        $scope.valor_calzone = '';
        $scope.valor_calzone_base = 0;
      }
  }
  $scope.atualizaOpcoesPizza = function(){
    _log('aplicando tamanho da pizza');
    ///zera calzone
    $scope.zeraItens('calzone');
    $scope.qnt_sabores = '';
    $('#detalhesPizza').append('<div class="item_load"><center><img src="'+BASE_URL+'images/loading.gif"></center></div>');
    //registra o item
    if($scope.pizza.tamanho != ""){
      $http({
        method  : 'POST',
        url     : BASE_URL+'pedidos/registra_item',
        data    : {'tamanho' : $scope.pizza.tamanho, 'tipo' : 'pizza'},
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
       })
        .success(function(data) {
          $('.item_load').remove();
          if(data.length == 0)
          {
            _log('erro ao processar item');
            toastr.error('Erro ao processar item');
            $('.modalPizza').modal('hide');
          }
          else
          {
            _log(data);
            $scope.valor_pizza = data.valor;
            $scope.valor_pizza_base = data.valor;
            $scope.qnt_sabores = data.num_sabores;
            confPizza(); //conferencia
            $scope.atualizaItemPizza();
            $scope.calculaTotal();
          }
        }).error(function(){
          $('.item_load').remove();
          _log('erro ao processar carregamento do pedido');
          carregandoPedido(false);
        });
      }else{
        $scope.valor_pizza = '';
        $scope.valor_pizza_base = 0;
      }
    }
    /**
     * Controla o botao de cadastro de novos tabores
     * @date   2016-05-05T15:47:14-0300
     * @author MARQUARDT, William <williammqt@gmail.com>
     * @return Boolean
     */
    $scope.liberaCadastroNovoSabor = function(){
      $n_sab = $('.box_lista_sabores_item').size();
      ///_log('Numero cadastrado: '+$n_sab+' - NÃºmero permitido: '+$scope.qnt_sabores);
      if( $n_sab < $scope.qnt_sabores )
        return false;
      else
        return true;
    } 
    /**
     * Controla o botao de cadastro de novos tabores
     * @date   2016-05-05T15:47:14-0300
     * @author MARQUARDT, William <williammqt@gmail.com>
     * @return Boolean
     */
    $scope.liberaCadastroNovoSaborCalzone = function(){
      $n_sab = $('.box_lista_sabores_item_calzone').size();
      ///_log('Numero cadastrado: '+$n_sab+' - NÃºmero permitido: '+$scope.qnt_sabores);
      if( $n_sab < $scope.qnt_sabores )
        return false;
      else
        return true;
    } 
    /**
     * Remove o item do pedido temporario
     * @date   2016-05-11T20:52:38-0300
     * @author MARQUARDT, William <williammqt@gmail.com>
     * @param  int                 id_item
     */
    $scope.removeItemListaPedido = function(id_remove){
     if( !$scope.removendoItemListaPedido ){
        _log('removendo item : '+id_remove);
        //desativa todos os botoes.
        $scope.removendoItemListaPedido = true;
        $http.get(BASE_URL+'pedidos/remove_item_lista_pedido_tmp/'+id_remove).success(function($data){ 
          $scope.removendoItemListaPedido = false;
          toastr.success('Item removido com sucesso');
          $scope.atualizaPedido();
        });
      }else{
        toastr.error('Aguarde a remoÃ§Ã£o do item atual');
      }
    }
    /**
     * Carrega modal para edicao de item
     * @date   2016-05-12T13:39:49-0300
     * @author MARQUARDT, William <williammqt@gmail.com>
     * @param  int                 id_edita id do item a ser editado
     */
    $scope.editaItemListaPedido = function( id_edita ){
      _log('Editando item '+id_edita+' quantidade : '+$scope.item_edt['quantidade']);
      $scope.emEdicao =  'ok';
      $scope.id_edicao = id_edita;
      $('.kc_fab_wrapper').hide(100);
      $scope.item_edt = {
        'quantidade' : parseInt($scope.itens_pedido[id_edita]['quantidade'])
      }
    }
    $scope.concluirEdicao = function(){
      _log('edicao ok');
      //obtem a quantidade total do item.
      $http({
        method  : 'POST',
        url     : BASE_URL+'pedidos/atualiza_quantidade_item',
        data    : {'quantidade' : $scope.item_edt['quantidade'], 'linha' : $scope.id_edicao},
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
       })
        .success(function(data) {
          $scope.emEdicao = '';
          $scope.id_edicao = '';
          $('.kc_fab_wrapper').show(500);
          toastr.success('Registro editado com sucesso');
          $scope.atualizaPedido();
          $scope.item_edt['quantidade'] = 1;
        }).error(function(){
          $scope.emEdicao = '';
          $scope.id_edicao = '';
          $('.kc_fab_wrapper').show(500);
          toastr.error('erro ao processar ediÃ§Ã£o do item');
          $scope.atualizaPedido();
          $scope.item_edt['quantidade'] = 1;
        });
    }
    $scope.alteraQuantEdtItem = function(){
      _log('alterando quantidade item');
      _log('item : '+$scope.id_edicao);
      _log( 'quantidade : ' +$scope.item_edt['quantidade'] );
      //atualiza o item
      $scope.itens_pedido[$scope.id_edicao]['quantidade'] = +$scope.item_edt['quantidade'];
    }
    /**
     * Finaliza o pedido
     * @date   2016-05-19T10:55:36-0300
     * @author MARQUARDT, William <williammqt@gmail.com>
     */
    $scope.finalizaPedido = function(){
      //verifica se foi definido um tipo de pagamento.
      if(  (!$scope.entregar && !$scope.retirar )  ){
        toastr.warning('Selecione a forma de entrega');
        return;
      }
      if( $scope.entregar && (typeof($scope.dados_entrega.tipo) == "undefined" || $scope.dados_entrega.tipo == '') ) {
        toastr.warning('Selecione a forma de pagamento');
        return;
      }
      if( $scope.entregar )
        $scope.dados_entrega.metodo = "entregar";
      else
        $scope.dados_entrega.metodo = "retirar";
      if($scope.itens_pedido != ''){
        $scope.finalizando = true;
        $('.overlay').show(1);
        var valBtn = $('#botaoFinaliza').html();
        $('#botaoFinaliza').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
        $http({
            method  : 'POST',
            url     : BASE_URL+'pedidos/gera_pedido',
            data    : {'entrega' : $scope.dados_entrega},
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
           })
            .success(function(data) {
              $scope.finalizando = false;
              $('.overlay').hide();
              $('#botaoFinaliza').html(valBtn);

              if( data != 'erro' ){
                toastr.success('Pedido gerado com suceeso');
                $scope.mensagemSucessoPedido = true;
                $scope.atualizaPedido();
                $('.modal').modal('hide');
              }else{
                //$('.modal').modal('hide');
                toastr.error('Erro crÃ­tico ao gerar pedido COD:#500');
              }
            }).error(function(){
              toastr.error('Erro ao gerar pedido');
            });
      }else{
        toastr.warning('Adicione ao menos um item ao pedido');
      }
    }
    $scope.trocoPara = function(tipo){
        if ( $scope.dados_entrega.troco_para == "" || $scope.dados_entrega.troco_para == null || typeof($scope.dados_entrega.troco_para) == "undefined" )
          $scope.dados_entrega.troco_para = parseInt($scope.total_pedido);
        if( tipo == "dinheiro" ){
          $scope.trocopara = true;
        }else{
          $scope.trocopara = false;
        }
    }
    $scope.entrega = function(tipo){
      console.log($scope.meus_dados);
      _log('alterando entrega : '+tipo);
      if( tipo == 'entregar'){
            $scope.entregar = true;
            $scope.retirar = false;
            if($scope.meus_dados.valor_entrega == "" || $scope.meus_dados.valor_entrega == null){
              $scope.dados_entrega.valor_entrega = 0;
              $scope.dados_entrega.troco_para = parseInt(parseInt($scope.total_pedido) + 1);
            
            }
            else{
              $scope.dados_entrega.valor_entrega = parseFloat($scope.meus_dados.valor_entrega);
              $scope.dados_entrega.troco_para = parseInt(parseInt($scope.total_pedido) + 1 + parseInt($scope.meus_dados.valor_entrega));
            }

      }else if(tipo == 'retirar'){
            $scope.entregar = false;
            $scope.retirar = true;
            $scope.dados_entrega.valor_entrega = '';
            $scope.dados_entrega.troco_para = parseInt($scope.total_pedido + 0.5);
      }
      else{
        $scope.entregar = false;
        $scope.retirar = false;
        $scope.dados_entrega = {};

      }

    }
    //inicializa variaveis
    $scope.inicializa();
    //faz a atualizaÃ§Ã£o do pedido
    //deixar este item por ultimo aqui no controller
    $scope.atualizaPedido();

    ///FIM  - PARE DE EDITAR
  });
  //fim controller de compras.
/**
 * Realiza algumas conferencias
 * para verificar se itens estao de acordo
 * @date   2016-05-03T10:01:19-0300
 * @author MARQUARDT, William <williammqt@gmail.com>
 * @return bool
 */
var confPizza = function(){
  var r = true;
  return r;
}
/**
 * Faz o processamento do pedido em json e retorna HTML
 * @date   2016-05-02T15:28:25-0300
 * @author MARQUARDT, William <williammqt@gmail.com>
 * @param  JSON                 j_p dados do pedido em JSON
 * @return HTML                     pedido processado
 */
//function processaPedido(j_p){
//  _log('processando pedido encontrado');
//  console.log(j_p);
//}
/**
 * Exibe caixa de carregamento de pedido
 * @date   2016-05-02T15:36:29-0300
 * @author MARQUARDT, William <williammqt@gmail.com>
 * @param  bool                 carregando
 * @return void
 */
var carregandoPedido = function(carregando){
  if(typeof(carregando) == "undefined")
    carregando = true;
  if(carregando){
    _log('carregando pedido');
    $('#divPedido').after('<div class="loadingPedidoBox"><hr><h1><center>Carregando pedido <img src="'+BASE_URL+'images/loading.gif"></center></h1></div>');
  }else{
    _log('pedido carregado');
    $('.loadingPedidoBox').remove();
  }
}
angular.module('compraPizzas').controller('pedidosCtrl', function($scope, $http, $location, sharedMessages){
  "use strict";
  inner('bar');
  $scope.lista_pedidos = {};
  $scope.id_pedido_detalhe = 0;

  selecionaMenu('pedidos');
  $http.get(BASE_URL+'pedidos/get_pedidos').success(function($data){ 
    $scope.lista_pedidos=$data; 
    if($scope.lista_pedidos.length > 0 && $scope.lista_pedidos != '""'){
      $scope.has_pedidos = true;
    }else{
      $scope.lista_pedidos = {};
      $scope.has_pedidos = false;
    }
  });

  $scope.carregaDetalhesPedido = function(id_ped){
    _log('Carregando detalhes do pedido : '+id_ped);
    $scope.id_pedido_detalhe = id_ped;
    $('#modalDetalhes').modal();
    $scope.detalhes = {};
    $http({
      method  : 'GET',
      url     : BASE_URL+'pedidos/get_pedidos_detalhes/'+id_ped,
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     })
      .success(function(data) {
        //cria os objetos
        for(var i=0; i<data.pizzas.length ; i++){
          data.pizzas[i].sabores = JSON.parse(data.pizzas[i].sabores);
        }
        for(var i=0; i<data.calzones.length ; i++){
          data.calzones[i].sabores = JSON.parse(data.calzones[i].sabores);
        }
        $scope.detalhes=data;
        $scope.detalhes.id = id_ped;
      }).error(function(){
        $scope.detalhes={};
        $scope.detalhes.id = '';
      });

  }
});
angular.module('compraPizzas').controller('dadosCtrl', function($scope, $location, $http, $location,$route, sharedMessages){
  inner('bar');
  _log('Exibindo dados do usuario');
  selecionaMenu('dados');
  $('.modal-backdrop').hide();
  $('.nav-tabs a[href="#dados_gerais"]').trigger('click');
  //obtem os dados.
  $http.get(BASE_URL+'pedidos/get_cadastro').success(function($data){ 
    $scope.meus_dados=$data; 
  });
  $http.get(BASE_URL+'ajax/get_cidades').success(function($data){ 
    $scope.cidades=$data; 
  });

  $scope.gravaDadosGerais = function(){
    _log('Gravando dados gerais....');
    $scope.isDisabled = true;
    $http({
      method  : 'POST',
      url     : BASE_URL+'ajax/grava_dados_cadastro',
      data    : {'dados' : $scope.meus_dados},
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     }).success(function(data) {
        $scope.isDisabled = false;

        if( data != 'erro' ){
          toastr.success('Dados gravados com sucesso');
        }else{
          toastr.error('Erro crÃ­tico ao gerar pedido COD:#500');
        }
      }).error(function(){
        $scope.isDisabled = false;
        toastr.error('Erro ao gerar pedido');
      });
  }

  $scope.gravaDadosSeguranca = function(){
    _log('Gravando dados de seguranca....');
    $scope.isDisabled = true;
    $http({
      method  : 'POST',
      url     : BASE_URL+'ajax/grava_dados_seguranca',
      data    : {'dados' : $scope.sec_dados},
      headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
     }).success(function(data) {
        $scope.isDisabled = false;
        if( data == 'senha_incorreta' ){
          toastr.error('Senha incorreta');
          $scope.sec_dados.senha = '';
        }else if( data == 'minimo_caracteres' ){
          toastr.error('A nova senha deve conter no mÃ­nimo 6 caracteres');
          $scope.sec_dados.nova_senha = '';
          $scope.sec_dados.nova_senha_confirma = '';
        }else if( data == 'senhas_nao_coincidem' ){
          toastr.error('Senhas nÃ£o coincidem');
          $scope.sec_dados.nova_senha = '';
          $scope.sec_dados.nova_senha_confirma = '';
        }else{
          $scope.sec_dados = {};
          $location.path('/');
          toastr.success('Senha alterada com sucesso');
        }
      }).error(function(){
        $scope.isDisabled = false;
        toastr.error('Erro ao gerar pedido');
      });
  }
});
//fim controllers compra
///validacao cpf cnpj
/* global angular, CPF, CNPJ */
(function(window){
  'use strict';
  var module = angular.module('ngCpfCnpj', []);
  function applyValidator(validator, validatorName, ctrl) {
    if( ctrl.$validators ) {
      // Angular >= 1.3
      ctrl.$validators[validatorName] = function(modelValue, viewValue) {
        var value = modelValue || viewValue;
        return (validator.isValid(value) || !value);
      };
    } else {
      // Angular <= 1.2
      ctrl.$parsers.unshift(function (viewValue) {
        var value = viewValue.replace(/\D/g, "");
        var valid = validator.isValid(value) || !value;
        ctrl.$setValidity(validatorName, valid);
        return (valid ? viewValue : undefined);
      });
    }
  }
  if( window.CPF ) {
    module.directive('ngCpf', function() {
      return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
          applyValidator(CPF, "cpf", ctrl);
        }
      };
    });
  }
  if( window.CNPJ ) {
    module.directive('ngCnpj', function() {
      return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
          applyValidator(CNPJ, "cnpj", ctrl);
        }
      };
    });
  }
})(this);
/**
 * Define se uma pÃ¡gina Ã© interna ou nao
 * @date   2016-04-28T09:29:34-0300
 * @author MARQUARDT, William <williammqt@gmail.com>
 * @param  bool show - mostrar itens internos 
 * @return void
 */
function inner( show ){
  if( typeof(show) == "undefined" )
    show = 'all';
  if(show == 'all'){
    $('.inner_element').removeClass("no-show");
  }else if(show == 'bar'){ 
     $('.inner_element').addClass('no-show');
     $('.bar_t').removeClass("no-show");
  }
  else{
    $('.inner_element').addClass('no-show');
  }
}