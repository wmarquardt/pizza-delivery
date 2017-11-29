<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		//se a sessao nao estiver aberta - vai para a tela de login.
		if( (!$this->session->user_logged || !isset($this->session->user_logged)) && $this->uri->segment(2) != 'home'  ){
			//redirect( base_url('#/') ,'refresh');
		}
		$this->dados = array();
		$this->dados['empresa'] = $this->cm->get('pizzaria');
	}

	public function index()
	{
		//cria o app
		$this->load->view('user/main', $this->dados);

	}
	

	public function home()
	{

		//se a sessao já estiver aberta - vai para a tela de compra.
		if( $this->session->user_logged ):  
			$this->load->view('user/compra', $this->dados);
		else: 
			//tela inicial
			$this->load->view('user/home', $this->dados);
		endif; 
		
	}

	public function cadastro()
	{
		//se a sessao já estiver aberta - vai para a tela de compra.
		if( $this->session->user_logged )
			$this->load->view('user/compra', $this->dados);
		else
			//tela de cadastro
			$this->load->view('user/cadastro', $this->dados);
	}

	public function compra(){
		if( $this->session->user_logged )
			$this->load->view('user/compra', $this->dados);
		else
			$this->load->view('user/home', $this->dados);
	}

	public function pedidos(){
		
		if( $this->session->user_logged )
			$this->load->view('user/pedidos', $this->dados);
		else
			$this->load->view('user/home', $this->dados);
	}

	public function dados(){
		if( $this->session->user_logged )
			$this->load->view('user/dados', $this->dados);
		else
			$this->load->view('user/home', $this->dados);
	}

	public function sair(){
		//encerra a sessao e volta para o inicio.
		$this->session->sess_destroy();
		redirect('','refresh');
	}

	/**
	 * envia e-mail para confirmacao de cadastro no sistema e redireciona para a pagina inicial
	 * @date   2016-05-31T16:34:27-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @param variable $dont_redirect caso seja passado sera uma chamada ajax, então só faz o envio do e-mail de confirmação e nao redireciona para nenhum lugar.
	 */
	public function confirma_email($dont_redirect = NULL){

		//obtem os dados do usuario
		$d = $this->cm->get( 'usuarios', array('id' => $this->session->user_id, 'confirmado' => 0), 0, array(), 'hash' );
		if( count( $d ) > 0 ){
			$d = $d['0'];
			//se por acaso o hash estiver em branco - cria um.
			if( $d->hash == ""){
				$hash = md5( uniqid() );
				$this->cm->update( 'usuarios', array( 'hash' => $hash ) , array('id' => $this->session->user_id) );
			}else{
				$hash = $d->hash;
			}

			$link = base_url("confirma_cadastro/".$hash);
			//envia email informando gravacao do pedido
			$mensagem = "Olá!<br><br>";
			$mensagem .= "Seja bem vindo ao sistema de encomenda on-line, para começar a usar e fazer seus pedidos você deverá acessar o link abaixo (apenas para confirmar seu e-mail): <br>";
			$mensagem .= "<a href='".$link."'>".$link."</a>";
			$mensagem .= "<br><br>Obrigado!";
			$dd['titulo'] = "Confirmação de cadastro";
			$dd['mensagem'] = $mensagem;

			//envia o email
			$this->email->clear();
			$this->email->to($this->session->user_email);
			$this->email->from($this->config->item('system_email'),$this->config->item('empresa'));
			$this->email->subject(config_item('empresa')." - Confirmação de cadastro");
			$this->email->message($this->load->view('emails/default', $dd, TRUE));
			$this->email->send();

			//faz o refirecionamento
			//nao funciona setando flashdata (angular maldito)
			$this->session->set_userdata('email_confirm_enviado', 'ok');
			if( $dont_redirect == NULL ){
				redirect('/#/compra','refresh');
			}

		}
		
	}
		
	/**
	 * Faz a confirmação do cadastro
	 * @date   2016-05-31T22:05:57-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @param  string $hash - hash da tabela de usuarios do bd
	 */
	public function confirma($hash){
		//verifica se existe no cadastro;
		if(strlen($hash) == 32){ 
			if( $this->cm->count( 'usuarios', array('hash' => $hash) ) > 0 ){
				//atualiza no bd
				$this->cm->update('usuarios', array('confirmado' => 1), array('hash' => $hash));
				$this->session->set_userdata('user_confirmado_sucesso', 'ok');
				//altera na sessao.
				$this->session->user_confirmado = true;
				redirect('/#/compra','refresh');
			}
		}
	}

	/**
	 * Faz a recuperacao da senha baseada no token.		
	 * @date   2016-06-01T15:30:37-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @param  string                   $token token de alteracao de senha
	 */
	function recupera_senha( $token = NULL ){
		$dados['token'] = $token;

		//verifica se existe o token.
		if( strlen(trim($token)) == 32 ){
			//verifica se existe no sistema.
			$sel = $this->cm->get('recupera_senha', array('token' => $token), 1, array(), 'id_usuario, data_add');
			if( count($sel) > 0 ){
				$id = $sel[0]->id_usuario;
				$d1 = new DateTime( $sel[0]->data_add );
				$d2 = new DateTime();
				$diferenca = $d2->diff($d1);
				if( $diferenca->h < 2 ){
					$dados['mensagem_erro'] = '';
					if( $this->input->post() ){
						//verifica as senhas.
						$s1  = $this->input->post('senha');
						$s2  = $this->input->post('senha2');
						if( $s1 != $s2 ){
							$dados['mensagem_erro'] = 'As senhas informadas não são identicas.';
						}elseif( strlen( $s1 ) < 6 ){
							$dados['mensagem_erro'] = 'A senha deve conter no mínimo 6 caracteres.';
						}else{
							//remove o registro da tabela de recuperacao.
							//$this->cm->delete('recupera_senha', array('hash' => $hash ));
							//altera senha no bd
							$this->cm->update('usuarios', array('senha' => sha1($s1)), array('id'=>$id));
							$this->session->set_userdata('senha_alterada', true);
							redirect('/#/');
						}
					}
					$this->load->view('user/altera_senha', $dados);
				}else{
					$this->session->set_userdata('erro_token_email', true);
					redirect('/#/');
				}
			}else{
				$this->session->set_userdata('erro_token_email', true);
				redirect('/#/');
			}
			
		}else{
			$this->session->set_userdata('erro_token_email', true);
			redirect('/#/');
		}
	}

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */