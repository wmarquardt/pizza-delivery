<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if( ($this->uri->segment(2) != "login" && $this->uri->segment(2) != "auth") && ($this->session->is_logged == false || !$this->session->is_logged) ){
			redirect('admin/login','refresh');
		}
	}
	public function index()
	{
		$this->load->view('dashboard');  
	}
	public function login(){
		$dados = "";
		if( $this->session->is_logged || $this->session->is_logged != "" )
			redirect('admin','refresh');
		$this->load->view('admin_login');
	}
	public function auth(){
		$this->load->model('crud_model', 'cr');
		if( $this->session->is_logged || $this->session->is_logged != "" )
			redirect('admin','refresh');
		$d = array();
		//do login
		if ( $this->input->post('username') != "" ){
			//verify user exists
			$userdata = $this->cr->get('admin', array( 'login' => $this->input->post('username'), 'senha' => sha1( $this->input->post('password') ) ));
			if( count($userdata) > 0 ){
				$this->session->set_userdata('id',$userdata[0]->id);
				$this->session->set_userdata('is_logged',true);
				redirect('admin', 'refresh');
			}else{
				$d['error'] = "Dados incorretos - tente novamente.";
			}
		}
		$this->load->view('admin_login', $d);
	}
	public function sair(){
		$this->session->sess_destroy();
		redirect('admin/login', 'refresh');
	}

    public function pedidos(){
        $this->load->view('admin_pedidos');
    }
    public function bebidas_categorias()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Categorias - Bebidas');
        $crud->set_table('bebidas_categorias');
        //campos obrigatorios
        $crud->required_fields('nome');
        $output = $crud->render();
        $this->_show_view($output);                
    }
	public function bebidas()
    {
        $crud = new grocery_CRUD();
 		$crud->set_subject('Bebidas');
        $crud->set_table('bebidas');
        //campos obrigatorios
        $crud->required_fields('nome','valor', 'ativo');
        
        //alteração de labels (se necessário)
        $crud->display_as('id_categoria', 'Categoria');

		$crud->set_rules('valor','Valor','required');
        $crud->set_relation('id_categoria','bebidas_categorias','nome');
        //$crud->unset_jquery();
        //$crud->unset_javascript();
        $output = $crud->render();
        $this->_show_view($output);                
    }

    public function cal_categorias()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Categorias - Calzones');
        $crud->set_table('calzones_categorias');

        //campos obrigatorios
        $crud->required_fields('nome');
        $output = $crud->render();

        $this->_show_view($output);                
    }
    public function cal_tamanhos()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Tamanhos - Calzones');
        $crud->set_table('calzones_tamanhos');
        //campos obrigatorios
        $crud->required_fields('nome', 'num_sabores', 'valor');
        //validacao
        $crud->set_rules('nome','Nome','required');
        $crud->set_rules('num_sabores','Número de sabores','required|integer');
        ///labels
        $crud->display_as('num_sabores', 'Qnt. Sabores');
        $output = $crud->render();
        $this->_show_view($output);                
    }


    public function cal_sabores()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Sabores - Calzones');
        $crud->set_table('calzones_sabores');
        //campos obrigatorios
        $crud->required_fields('nome','id_categoria');
        //alteração de labels (se necessário)
        $crud->display_as('descricao', 'Descrição');
        $crud->unset_texteditor('descricao');
        $crud->display_as('id_categoria', 'Categoria');
        $crud->set_rules('valor','Valor','required');
        $crud->set_relation('id_categoria','calzones_categorias','nome');
        $output = $crud->render();
        $this->_show_view($output);                
    }

    public function sabores()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Sabores - Pizzas');
        $crud->set_table('pizzas_sabores');
        //campos obrigatorios
        $crud->required_fields('nome','id_categoria');
        //alteração de labels (se necessário)
        $crud->display_as('descricao', 'Descrição');
        $crud->unset_texteditor('descricao');
        $crud->display_as('id_categoria', 'Categoria');
        $crud->set_rules('valor','Valor','required');
        $crud->set_relation('id_categoria','pizzas_categorias','nome');
        $output = $crud->render();
        $this->_show_view($output);                
    }
    
 	public function categorias()
    {
        $crud = new grocery_CRUD();
 		$crud->set_subject('Categorias Pizzas');
        $crud->set_table('pizzas_categorias');
        //campos obrigatorios
        $crud->required_fields('nome');
        $output = $crud->render();
        $this->_show_view($output);                
    }
    public function tamanhos()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Tamanhos Pizzas');
        $crud->set_table('pizzas_tamanhos');
        //campos obrigatorios
        $crud->required_fields('nome', 'fatias', 'num_sabores', 'valor');
        //validacao
        $crud->set_rules('nome','Nome','required');
        $crud->set_rules('tamanho_cm','Tamanho','integer');
        $crud->set_rules('num_sabores','Número de sabores','required|integer');
        $crud->set_rules('fatias','Fatias','required|integer');
        ///labels
        $crud->display_as('tamanho_cm', 'Tamanho (cm)');
        $crud->display_as('num_sabores', 'Qnt. Sabores');
        $crud->display_as('fatias', 'Qnt. Fatias');
        $output = $crud->render();
        $this->_show_view($output);                
    }

    public function meus_dados()
    {
        $crud = new grocery_CRUD();
        $crud->set_subject('Meus Dados');
        $crud->set_table('pizzaria');
        //campos obrigatorios
        $crud->required_fields('nome', 'bairro', 'rua', 'numero', 'tempo_entrega');
        //validacao
        $crud->set_rules('nome','Nome','required');
        $crud->set_rules('rua','Rua','required');
        $crud->set_rules('tempo_entrega','Tempo de entrega','required|integer');
        $crud->set_rules('numero','Número','required');
        $crud->set_rules('bairro','Bairro','required');
        ///labels
        $crud->display_as('tempo_entrega', 'Tempo de entrega (minutos)');
        $crud->unset_add();
        $crud->unset_fields('disp_horas','disp_dias');
        $crud->unset_columns('disp_horas','disp_dias');
        $output = $crud->render();
        $this->_show_view($output);                
    }


    public function clientes()
    {
        $crud = new grocery_CRUD();
 		$crud->set_subject('Clientes');
        $crud->set_table('usuarios');
        //campos obrigatorios
        $crud->required_fields('nome');
        $crud->unset_add();
        $crud->unset_fields('senha','data_add', 'hash');
        $crud->unset_columns('senha','data_add', 'hash');
        $output = $crud->render();
        $this->_show_view($output);                
    }
    public function ruas(){
    	$crud = new grocery_CRUD();
 		$crud->set_subject('Ruas');
        $crud->set_table('rua_refs');
        //campos obrigatorios
        $crud->required_fields('nome', 'valor');        
        $output = $crud->render();
        $this->_show_view($output);  
    }

    /**
     * controllers ajax
     */
    /**
     * Obtem os últimos 100 pedidos
     * @date   2016-06-02T00:26:46-0300
     * @author MARQUARDT, William <williammqt@gmail.com>
     */
    public function get_last_pedidos($num = 100){
        $num = intval($num);
        $join['usuarios u'] = "u.id=p.id_usuario";
        $campos = "p.*, u.nome, (p.valor_entrega + p.valor_total)total_total, get_status(status) as nome_status, u.confirmado";
        $sel = $this->cm->getJoined('pedidos p', $campos, $join, array(), "", array('id' => 'DESC'), $num);
        
        foreach ($sel as $s) {
            $s->data = date("d/m/Y", strtotime( $s->data_pedido ));
            $s->hora = date("H:i:s", strtotime( $s->data_pedido ));
            $s->tipo_entrega = ucwords($s->tipo_entrega);
        }
        echo json_encode($sel);
    }
    /**
     * Fim controllers ajax
     */
    
    private function _show_view($output = null)
    {
    	//print_r($output);
        $this->load->view('default',$output);    
    }  

    public function get_pedidos_detalhes($id_pedido = NULL){
        $r = "";
        $peds = $this->cm->count('pedidos', array( 'id' => intval($id_pedido)));
        //faz o loop nos pedidos para pegar os itens.
        if( $peds > 0 ):
            //verificar aqui
            //se ficar lento - pegar os dados de outra maneira.
            //em um select só
            $rres = $this->cm->get('pedidos', array( 'id' => intval($id_pedido))    );
            $r = get_object_vars($rres[0]);

            $r['bebidas'] = $this->cm->getJoined('pedidos_bebidas pb', "pb.*, b.nome as nome, bc.nome as categoria" ,  array( 'bebidas b' => 'pb.id_bebida=b.id', 'bebidas_categorias bc' => 'b.id_categoria=bc.id' ) ,array('pb.id_pedido' => intval($id_pedido)));
            $r['calzones'] = $this->cm->getJoined('pedidos_calzones pc',"pc.*, ct.nome as tamanho_desc",array("calzones_tamanhos ct" => 'ct.id=pc.id_tamanho'), array('pc.id_pedido' => intval($id_pedido)));
            $r['pizzas'] = $this->cm->getJoined('pedidos_pizzas pp',"pp.*, pt.nome as tamanho_desc",array("pizzas_tamanhos pt" => 'pt.id=pp.id_tamanho'),  array('pp.id_pedido' => intval($id_pedido)));
        endif;
        echo json_encode($r);
    }

    public function altera_status($id_pedido = NULL, $id_status = NULL){
        if( $id_pedido != NULL && $id_status != NULL ){
            $id_pedido = intval($id_pedido);
            $id_status = intval($id_status);
            $this->cm->update( 'pedidos', array( 'status' => $id_status ) , array('id' => $id_pedido) );
        }
    }
}
/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */