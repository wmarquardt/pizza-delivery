<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pedidos extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//se nao estiver logado apenas retorna 401 (Angular faz a verificacao)
		if( !$this->session->user_logged ){
			$this->output->set_status_header(401); 
			exit();
		}
	}
	public function index()
	{
	}
	/**
	 * obtem o pedido temporário
	 * @date   2016-04-29T10:00:50-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	public function get_tmp(){
		$id = $this->cria_tmp(); 
		$pt = $this->cm->get( 'pedidos_tmp', array('id' => $id) );
		/*//verifica se pedido está em aberto mas sem itens.
		$ptemp =json_decode( $pt[0]->pedido);
		if( is_object($ptemp) ):
			$ptemp = get_object_vars($ptemp);
			if(array_key_exists( 'itens', $ptemp )){
				if( count( $ptemp['itens']) == 0  ){
					$ptemp = "";
					$pt[0]->pedido = json_encode($ptemp);
					//atualiza no bd
					$this->cm->update('pedidos_tmp', array('pedido' => json_encode($ptemp)) , array('id' => $id));
				}
			}
		endif;*/
		echo json_encode( $pt );
	}
	///metodos privados
	/**
	 * Cria um pedido temporario com base no codigo do user (apenas se nao tiver nenhum registro nas últimas 48h)
	 * @date   2016-04-29T09:11:32-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	private function cria_tmp(){
		$id = 0;
		if (intval($this->session->user_id) == 0)
			return 0;
		//array de inserção
		$ins = array(
			'id_usuario' => $this->session->user_id,   
			'pedido' => json_encode("")
		);
		//insere um novo
		if( $this->cm->count( 'pedidos_tmp', array('id_usuario' => $this->session->user_id) ) == 0 ): 
			$id = $this->cm->insert('pedidos_tmp', $ins);
		else: 
			//verifica se tem algum item - se tiver apenas
			$d_ped = $this->cm->get('pedidos_tmp', array('id_usuario' => $this->session->user_id ));
			//verifica se o pedido é referente as últimas 24h
			//se nao for, apaga os dados do pedido e retorna o id.
			$data1 = new DateTime( ( $d_ped[0]->data_pedido ) );
			$data2 = new DateTime();
			$diferenca = $data2->diff($data1);
			//se a diferenca de dias for maior que 2 exclui o que já tem feito.
			if( $diferenca->d > 2 )
			{
				$this->cm->delete( 'pedidos_tmp', array('id' => $d_ped[0]->id) );
				$id = $this->cm->insert( 'pedidos_tmp', $ins );
			}
			else
			{
				//retorna apenas o id que já existe.
				$id = $d_ped[0]->id;
			}
		endif;
		return $id;
	}
	function get_pizza_tamanhos(){
		echo json_encode($this->cm->get( 'pizzas_tamanhos', NULL, 0, array('nome' => 'ASC') ));
	}
	function get_pizza_categorias(){
		echo json_encode($this->cm->get( 'pizzas_categorias', NULL, 0, array('nome' => 'DESC') ));
	}
	function get_calzone_tamanhos(){
		echo json_encode($this->cm->get( 'calzones_tamanhos', NULL, 0, array('nome' => 'ASC') ));
	}
	function get_calzone_categorias(){
		echo json_encode($this->cm->get( 'calzones_categorias', NULL, 0, array('nome' => 'DESC') ));
	}
	function get_bebidas(){
		$campos = "b.id as id, b.nome as nome_bebida, bc.nome as categoria, bc.id as id_categoria, b.valor";
		$join['bebidas_categorias bc'] = 'b.id_categoria=bc.id';
		echo json_encode($this->cm->getJoined( 'bebidas b', $campos, $join, array('b.ativo' => 1), "", array("bc.nome" => "ASC", "b.nome" => "ASC") ));
	}
	function get_cadastro(){
		$campos = "u.*, rr.valor as valor_entrega";
		$join['rua_refs rr'] = array("rr.id = u.rua_ref", 'left');
		$get = $this->cm->getJoined('usuarios u', $campos , $join , array('u.id' => $this->session->user_id));
		$get = $get[0];
		unset($get->senha);
		echo json_encode($get);
	}
	/**
	 * obtem o item do pedido temporário por indice
	 * @date   2016-05-12T14:32:14-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @param  int $in 
	 */
	function get_item_pedido_tmp($in = NULL){
		$item = "";
		if( $in != NULL ):
			//obtem o item
			$in = intval($in);
			$id = $this->cria_tmp(); 
			$pedido =  $this->cm->get( 'pedidos_tmp', array('id' => $id));
			$itens = json_decode($pedido[0]->pedido);
			$itens = get_object_vars($itens);
			//verifica se o indice é valido.
			if( $in < count($itens['itens']) ){
				//remove item temporario da sessao.
				$this->session->unset_userdata('tmp_pedido_item');
				$tmpItem = $itens['itens'][$in];
				$tmpItem->id_edicao = $in;
				$this->session->tmp_pedido_item = json_encode($tmpItem);
				$item = $tmpItem;
			}
		endif;
		echo json_encode($item);
	}
	/**
	 * Adiciona a pizza ao pedido temporário
	 * do banco de dados a partir dos dados que estão
	 * na sessao
	 * @date   2016-05-06T16:15:35-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function addItemToPedidoTemp(){
		$pizza = $this->session->tmp_pedido_item;
		//obtem pedido atual
		$at = $this->cria_tmp();
		$item = $this->cm->get('pedidos_tmp', array('id' => $at));
		$item = get_object_vars(json_decode($item[0]->pedido));
		//print_r($item);
		if( is_array( $item ) ){
			if( !array_key_exists('itens', $item) ){
				$item['itens'] = array();
			}
		}else{
			$item = array();
			$item['itens'] = array();
		}
		//print_r("sel:".$item);
		$item['itens'] = array_values($item['itens']);
		$item['itens'][count($item['itens'])] = $pizza;
		$this->cm->update('pedidos_tmp', array('pedido' => json_encode($item)), array('id' => $at));
		//zera o item temporario da sessao.
		$this->session->unset_userdata('tmp_pedido_item');
		return true;
	}	
	/**
	 * Adiciona a bebida ao pedido temporario
	 * diferente do metodo que adiciona o calzone e a pizza
	 * pois nao usa sessao temporaria e cria instantaneamente o item 
	 * diretamente no pedido em aberto.
	 * @date   2016-05-18T20:19:51-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function addBebidaToPedidoTemp(){
		$bebida =  json_decode(file_get_contents('php://input'), true);
		//monta array
		$b = array(
			'tamanho' => $bebida['item']['id_categoria'],
			'tipo' => 'bebida',
			'nome' => $bebida['item']['categoria'],
			'fatias' => '',
			'tamanho_cm' => '',
			'num_sabores' => 1,
			'valor' => $bebida['item']['valor'],
			'sabores' => array(
				array(
					'id' => $bebida['item']['id'],
					'nome' => $bebida['item']['nome_bebida']
				),
			),
			'quantidade' => $bebida['quantidade']
		);
		//obtem pedido atual
		$at = $this->cria_tmp();
		$item = $this->cm->get('pedidos_tmp', array('id' => $at));
		$item = get_object_vars(json_decode($item[0]->pedido));
		//print_r($item);
		if( is_array( $item ) ){
			if( !array_key_exists('itens', $item) ){
				$item['itens'] = array();
			}
		}else{
			$item = array();
			$item['itens'] = array();
		}
		//print_r("sel:".$item);
		$item['itens'] = array_values($item['itens']);
		$item['itens'][count($item['itens'])] = $b;
		$this->cm->update('pedidos_tmp', array('pedido' => json_encode($item)), array('id' => $at));
		//print_r($b);
	}
	/**
	 * obtem os sabores das pizzas agrupado por categoria
	 * @date   2016-05-03T15:59:32-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function get_pizza_sabores_por_categorias(){
		$categorias = $this->cm->get( 'pizzas_categorias', NULL, 0, array('nome' => 'DESC'));
		$r = array();
		$i =0;
		foreach ($categorias as $c) {
			$r[$i] = $this->cm->get('pizzas_sabores s', array('id_categoria' => $c->id),0,array('s.nome' => 'ASC'), "id,nome,descricao");
			$i ++;
		}
		echo json_encode($r);
	}
	/**
	 * obtem os sabores dos calzones agrupado por categoria
	 * @date   2016-05-03T15:59:32-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function get_calzone_sabores_por_categorias(){
		$categorias = $this->cm->get( 'calzones_categorias', NULL, 0, array('nome' => 'DESC'));
		$r = array();
		$i =0;
		foreach ($categorias as $c) {
			$r[$i] = $this->cm->get('calzones_sabores s', array('id_categoria' => $c->id),0,array('s.nome' => 'ASC'), "id,nome,descricao");
			$i ++;
		}
		echo json_encode($r);
	}	
	/**
	 * Grava alguns dados importantes referentes ao item na
	 * sessao do PHP para ficar mais seguro do que deixar no js
	 * @date   2016-05-02T19:59:09-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @return void
	 */
	function registra_item(){
		$item =  json_decode(file_get_contents('php://input'), true);
		if( count($item) > 0 ){
			//remove qualquer registro referente a itens da sessao.
			$this->session->unset_userdata('tmp_pedido_item');
			//faz a verificação por tipo
			switch ($item['tipo']) {
				case 'pizza':
					$det = $this->cm->get( 'pizzas_tamanhos' , array('id' => $item['tamanho']) );
					break;
				default:
					$det = $this->cm->get( 'calzones_tamanhos' , array('id' => $item['tamanho']) );
					break;
			}
			if( count($det) > 0 ){
				$det = $det[0];
				$det = get_object_vars($det);
				//nao precisa passar o id junto
				unset($det['id']);
				//coloca quantidade 1
				$det['quantidade'] = 1;
				$item = array_merge($item,$det);
				$this->session->set_userdata('tmp_pedido_item', $item);
			}
		}
		echo json_encode($this->session->tmp_pedido_item);
	}
	/**
	 * registra os sabores do registro temporário
	 * @date   2016-05-05T13:19:32-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function registra_item_sabores(){
		$item =  json_decode(file_get_contents('php://input'), true);
		if( count($item) > 0 ){
			//remove qualquer registro referente a itens da sessao.
			//$this->session->unset_userdata('tmp_pedido_item');
			//obtem a array temporaria da sessao
			//verifica se ha um item temporario
			if(!$this->session->tmp_pedido_item){
				echo "ERRO";
				exit();
			}else{
				$sess = $this->session->tmp_pedido_item;
				//verifica se existe o indice de sabores na array
				if( !array_key_exists('sabores', $sess) ){
					//cria o indice.
					$sess['sabores'] = array();
				}
				//verifica se o sabor já foi adicionado.
				/*
				removido por enquanto.
				foreach ($sess['sabores'] as $s) {
					if( $s['id'] == $item['id_sabor'] ){
						echo "JA_ADD"; 
						exit();
					}
				}*/
				//manipula o indice.
				//verifica o numero de sabores que o tamanho suporta.
				if( count($sess['sabores']) < $sess['num_sabores'] ){
					//adiciona o sabor.
					//verifica se sabor existe no bd
					$sab_c = $this->cm->get( 'pizzas_sabores', array('id' => $item['id_sabor']) );
					if( count($sab_c) > 0)
					{	
						$sess['sabores'][count($sess['sabores'])] = get_object_vars($sab_c[0]);
					}
				}else{
					echo "MAXIMO";
					exit();
				}
				$this->session->set_userdata('tmp_pedido_item', $sess);
			}
		}
		echo json_encode($this->session->tmp_pedido_item);
	}
	/**
	 * remove o sabor do item (ficou estranho isso) temporario da sessao
	 * @date   2016-05-05T13:22:15-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function remove_itens_sabores(){
		$item =  json_decode(file_get_contents('php://input'), true);
		if( array_key_exists('id_remove', $item) ):
			$rem = intval($item['id_remove']);
			$ti = $this->session->tmp_pedido_item;
			if( array_key_exists('sabores', $ti) ):
				for($i = 0 ; $i < count($ti['sabores']) ; $i++) {
					if( $ti['sabores'][$i]['id'] == $rem ):
						unset($ti['sabores'][$i]);
					endif;
				}
				$ti['sabores'] = array_values($ti['sabores']);
				//atualiza o indice
				$this->session->set_userdata('tmp_pedido_item', $ti);
			endif;
		endif;
		echo "ok";
	}
	/**
	 * recupera os dados referentes ao item que está sendo adicionado
	 * @date   2016-05-02T20:11:45-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @return json 		dados da sessao
	 */
	function get_current_item(){
		echo json_encode($this->session->tmp_pedido_item);
	}
	/**
	 * Atualiza o preço do produto temporario
	 * @date   2016-05-06T13:38:52-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function updatePrecoTmp($quant = NULL){
		try 
		{
			if( $this->session->tmp_pedido_item && $quant != NULL ):
				if( intval($quant) <= 0 )
					$quant = 1;
				$sess = $this->session->tmp_pedido_item;
				$sess['quantidade'] = $quant;
				$this->session->set_userdata('tmp_pedido_item', $sess);
			endif;	
		} catch (Exception $e) {
			return $e;
		}
	}
	/**
	 * Remove o item do pedido temporario aberto
	 * @date   2016-05-11T20:59:34-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @param  id $id_remove - id do item a ser removido
	 */
	function remove_item_lista_pedido_tmp($id_remove = NULL){
		if( $id_remove != NULL ):
			$id_remove = intval($id_remove);
			//obtem o pedido atual.
			$id = $this->cria_tmp(); 
			$pedido =  $this->cm->get( 'pedidos_tmp', array('id' => $id));
			$itens = json_decode($pedido[0]->pedido);
			//manipula a array.
			$itens = get_object_vars($itens);
			//echo count($itens['itens']);
			if( $id_remove <= count($itens['itens'])){
				//ok - remove o item da contagem e reconta.
				unset($itens['itens'][$id_remove]);
				if( count($itens['itens']) == 0 ){
					//recontagem
					$itens['itens'] = "";
					//gera json.
					$itens = json_encode("");
				}else{
					//recontagem
					$itens['itens'] = array_values($itens['itens']);
					//gera json.
					$itens = json_encode($itens);
				}
				$this->cm->update('pedidos_tmp', array('pedido' => $itens) ,array('id' => $id));
			}
		endif;
	}
	/**
	 * Edita item temporário do pedido.
	 * @date   2016-05-17T10:32:18-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function atualiza_quantidade_item(){
		$item =  json_decode(file_get_contents('php://input'));
		$quantidade = intval( $item->quantidade );
		$linha = intval($item->linha);
		//obtem o pedido atual.
		$id = $this->cria_tmp(); 
		$pedido =  $this->cm->get( 'pedidos_tmp', array('id' => $id));
		$ped = json_decode($pedido[0]->pedido);
		//$ped = get_object_vars($ped);
		//edita o pedido.
		$ped->itens[$linha]->quantidade = $quantidade;
		$ped = json_encode($ped);
		$this->cm->update('pedidos_tmp', array('pedido' => $ped) ,array('id' => $id));
	}
	/**
	 * Gera o pedido
	 * @date   2016-05-19T15:43:57-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function gera_pedido(){
		//dados referentes a entrega
		$item =  json_decode(file_get_contents('php://input'));
		$item = get_object_vars($item->entrega);
		//dados do pedido
		$id = $this->cria_tmp(); 
		$pedido_temp =  $this->cm->get( 'pedidos_tmp', array('id' => $id));
		//converte obj para array (melhor para manipular)
		try {
			if( is_object($pedido_temp[0]) ):
				$pedido_temp = get_object_vars($pedido_temp[0]);
			else:
				echo "erro"; 
				return;
			endif;
		} catch (Exception $e) {
			echo 'erro';
			return $e;
		}
		//inicia a geração do pedido
		$add = array(
			'id_usuario' => $this->session->user_id,
			'data_pedido' => $pedido_temp['data_pedido'],
			'tipo_entrega' => $item['metodo']
		);
		//dados da entrega.
		if( strtolower($item['metodo']) == "entregar" ){
			//obtem os dados do valor para entrega na rua do cliente
			if( array_key_exists('valor_entrega', $item) )
				if( intval($item['valor_entrega']) > 0 )
					$add['valor_entrega'] = intval($item['valor_entrega']);
			if( array_key_exists('troco_para', $item) )
				if( intval($item['troco_para']) > 0 )
					$add['troco_para'] = intval($item['troco_para']);
			if( array_key_exists('tipo', $item) )
				$add['tipo_pagamento'] = $item['tipo'];
		}	
		$id_pedido = $this->cm->insert('pedidos', $add);
		
		
		$itens_pedido = (json_decode($pedido_temp['pedido']));
		$itens_pedido = get_object_vars($itens_pedido);
		$itens_pedido = $itens_pedido['itens'];
		//totalizador.
		$total_pedido = 0;
		//passa por este loop para fazer a conferencia de valores
		//e ir adicionando item a item.
		for( $i =0 ; $i < count($itens_pedido) ; $i++ ){
			//atualiza o preco na array.
			$valor = 0; // zera valor antes de cair nos filtros
			switch (strtolower($itens_pedido[$i]->tipo)) {
				case 'pizza':
					$valor = $this->cm->get('pizzas_tamanhos', array('id' => $itens_pedido[$i]->tamanho));
					//se um dos tamanhs nao existir cancela o pedido.
					if( count($valor) == 0 ){
						echo "erro";
						$this->cm->delete('pedidos', array('id' => $id_pedido));
						return;
					}
					$valor = $valor[0]->valor;

					$add = array(
						'id_pedido' => $id_pedido,
						'id_tamanho' => $itens_pedido[$i]->tamanho,
						'sabores' => json_encode($itens_pedido[$i]->sabores),
						'quantidade' => $itens_pedido[$i]->quantidade,
						'valor' => $valor,
						'subtotal' => $valor * $itens_pedido[$i]->quantidade
					);
					$this->cm->insert('pedidos_pizzas', $add);
					break;
				case 'calzone':
					$valor = $this->cm->get('calzones_tamanhos', array('id' => $itens_pedido[$i]->tamanho));
					//se um dos tamanhs nao existir cancela o pedido.
					if( count($valor) == 0 ){
						echo "erro";
						$this->cm->delete('pedidos', array('id' => $id_pedido));
						return;
					}
					$valor = $valor[0]->valor;

					$add = array(
						'id_pedido' => $id_pedido,
						'id_tamanho' => $itens_pedido[$i]->tamanho,
						'sabores' => json_encode($itens_pedido[$i]->sabores),
						'quantidade' => $itens_pedido[$i]->quantidade,
						'valor' => $valor,
						'subtotal' => $valor * $itens_pedido[$i]->quantidade
					);
					$this->cm->insert('pedidos_calzones', $add);
					break;
				case 'bebida':
					$valor = $this->cm->get('bebidas', array('id' => $itens_pedido[$i]->sabores[0]->id));
					//se um dos tamanhs nao existir cancela o pedido.
					if( count($valor) == 0 ){
						echo "erro";
						$this->cm->delete('pedidos', array('id' => $id_pedido));
						return;
					}
					$valor = $valor[0]->valor;

					$add = array(
						'id_pedido' => $id_pedido,
						'id_bebida' => $itens_pedido[$i]->sabores[0]->id,
						'quantidade' => $itens_pedido[$i]->quantidade,
						'valor' => $valor,
						'subtotal' => $valor * $itens_pedido[$i]->quantidade
					);
					$this->cm->insert('pedidos_bebidas', $add);
					break;
			}
			$total_pedido = $total_pedido + ($valor * $itens_pedido[$i]->quantidade);
		}

		//atualiza total do pedido.
		$at['valor_total'] = $total_pedido;
		$this->cm->update('pedidos', $at, array('id' => $id_pedido));

		//remove pedido da sessao
		$this->session->unset_userdata('tmp_pedido_item');
		//remove pedido temporario do bd.
		$this->remove_pedido_tmp_bd();
		//mensagem
		$mensagem = "Seu pedido foi gerado pelo nosso sistema, e encontra-se agora em processamento.";
		$mensagem .= "<br>Veja abaixo alguns dados referentes ao seu pedido:<br><br>";

		//envia email informando gravacao do pedido
		$dd['titulo'] = "Pedido realizado.";
		$dd['mensagem'] = $mensagem;

		//envia o email
		$this->email->clear();
		$this->email->to($this->session->user_email);
		$this->email->from($this->config->item('system_email'),$this->config->item('empresa'));
		$this->email->subject('Pedido realizado com sucesso! #000'.$id_pedido);
		$this->email->message($this->load->view('emails/default', $dd, TRUE));
		$this->email->send();
	}

	/**
	 * Remove pedido temporario do bd
	 * @date   2016-05-23T10:35:38-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	public function remove_pedido_tmp_bd(){
		$this->cm->delete('pedidos_tmp', array('id_usuario' => $this->session->user_id));
	}

	/**
	 * obtem uma listagem completa dos pedidos efetuados
	 * @date   2016-05-23T14:16:52-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	public function get_pedidos(){
		$r = "";
		$r = $this->cm->get('pedidos p', array('id_usuario' => $this->session->user_id),0, array('id' => 'DESC'), "*, DATE_FORMAT(data_pedido,'%d/%m/%Y') as dt_ped, (valor_total + valor_entrega) as total_total, get_status(status) as ped_st");
		echo json_encode($r);
	}

	public function get_pedidos_detalhes($id_pedido = NULL){
		$r = "";
		$peds = $this->cm->count('pedidos', array('id_usuario' => $this->session->user_id, 'id' => intval($id_pedido)));
		//faz o loop nos pedidos para pegar os itens.
		if( $peds > 0 ):
			//verificar aqui
			//se ficar lento - pegar os dados de outra maneira.
			//em um select só
			$rres = $this->cm->get('pedidos', array('id_usuario' => $this->session->user_id, 'id' => intval($id_pedido))	);
			$r = get_object_vars($rres[0]);

			$r['bebidas'] = $this->cm->getJoined('pedidos_bebidas pb', "pb.*, b.nome as nome, bc.nome as categoria" ,  array( 'bebidas b' => 'pb.id_bebida=b.id', 'bebidas_categorias bc' => 'b.id_categoria=bc.id' ) ,array('pb.id_pedido' => intval($id_pedido)));
			$r['calzones'] = $this->cm->getJoined('pedidos_calzones pc',"pc.*, ct.nome as tamanho_desc",array("calzones_tamanhos ct" => 'ct.id=pc.id_tamanho'), array('pc.id_pedido' => intval($id_pedido)));
			$r['pizzas'] = $this->cm->getJoined('pedidos_pizzas pp',"pp.*, pt.nome as tamanho_desc",array("pizzas_tamanhos pt" => 'pt.id=pp.id_tamanho'),  array('pp.id_pedido' => intval($id_pedido)));
		endif;
		echo json_encode($r);
	}
}
/* End of file Pedidos.php */
/* Location: ./application/controllers/Pedidos.php */