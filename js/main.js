var DEBUG = true;//programa esta em modo debug
$(document).ready(function(){
	_log('iniciando interface...');
	$.material.init();
	var links = [
	{
	  "bgcolor":"#009688",
	  "icon":"<i class='fa fa-plus'></i>"
	},
	{
	  "url" : "#/cadastro",
	  "bgcolor":"#DB4A39",
	  "color":"#fffff",
	  "icon":"P",
	  "id" : "add_pizza",  
	  "title" : "Adicionar Pizza",
	  "dataTarget" : ".modalPizza"
	},

	{
	  "title": "Adicionar Calzone",	
	  "bgcolor":"#00ACEE",
	  "color":"#fffff",
	  "icon":"C",
	  "target":"_blank",
	  "id":"add_calzone", 
	  "dataTarget" : ".modalCalzone"
	},
	{
	  "url":"http://www.jqueryscript.net",
	  "bgcolor":"#263238",
	  "color":"white",
	  "icon":"B",
	  "title": "Adicionar Bebida",
	  "dataTarget" : ".modalBebida"
	}
	];
	$('.kc_fab_wrapper').kc_fab(links);
	var el = document.getElementById("add_calzone");
	el.addEventListener("click", novoCalzone);
	var novaPizza = function(){
		alert('lixo');
		console.log('test');
		return false;
	}
	var novoCalzone = function(){
		alert('funciona');
	}
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": true,
	  "positionClass": "toast-bottom-left",
	  "preventDuplicates": true,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	} 
});
function _log(msg){
	if( typeof(DEBUG) != 'undefined' ){
		if( DEBUG ){
			console.log(msg);
		}
	}
}
/**
 * Seleciona o menu na nav principal (quando logado)
 * @date   2016-05-04T11:33:06-0300
 * @author MARQUARDT, William <williammqt@gmail.com>
 */
function selecionaMenu(m){
	$('.active').removeClass('active');
	$('.menu_'+m).addClass('active');
}
returnToaddPizzaModalForm = function(){
  $('.saborPizzaSelect').hide(200);
  $('.addPizzaModalForm').show(200);
}

returnToaddCalzoneModalForm = function(){
  $('.saborCalzoneSelect').hide(200);
  $('.addCalzoneModalForm').show(200);
}
/*$(document).on('hide.bs.modal','.modal', function () {
 	//Do stuff here
 	_log('fechou modal '+$(this).attr('class'));
 	//cria uma instancia do controller do angular
 	//para remover qualquer item sendo editado.
 	var scope = angular.element($("#cont_compra")).scope();
    scope.$apply(function(){
        scope.regedit = '';
        scope.current_item_pizza = '';
        scope.pizza = {
            'tamanho' : ''
        }
        scope.quant
        scope.valor_pizza_base = '';
		scope.valor_pizza = '';
    });
    $('#pizzaQuantidade').val('1');
});*/