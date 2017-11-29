<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('crud_model', 'crud');
	}
	public function index()
	{
		//nao permite entrada diretamente aqui
		redirect('','refresh');
	}
	public function get_cidades(){
		$v = json_encode($this->crud->get('cidades'));
		print_r($v);
		return $v;
	}
	public function cadastraUser(){
		$this->load->helper('cookie');
		//$v =  $this->input->post();
		//pega apenas o prmeiro
		$v = json_decode(file_get_contents('php://input'), true);
		//print_r($v);
		//A array $v tem os dados que foram passados.
		//faz a validacao dos dados aqui no php mesmo.
		$err = array();
		//valida nome
		if( trim( $v['nome'] ) == ""  )
			$err['errorNome'] = "O nome não pode estar em branco";
		//verifica se o email já está no cadastro.
		if( $this->crud->count('usuarios', array('email' => $v['email'])) > 0 )
			$err['errorEmail'] = "O e-mail já encontra-se em nosso cadastro. Por favor, tente fazer o login.";
		if( $v['senha'] != $v['senhab'] )
			$err['errorSenha'] = "As senhas não são iguais";
		elseif( trim( $v['senha'] ) == "" || strlen(trim( $v['senha'] )) < 6)
			$err['errorSenha'] = "As senha precisa ter no mínimo 6 caracteres";
		//verifica se CPF encontra-se no cadastro
		if( !$this->validaCpf( $v['cpf'] ) )
			$err['errorCPF'] = "O CPF informado não é válido.";
		//telefone
		if( strlen(intval( $v['fone'] )) < 8 || strlen( $v['fone'] ) > 11 )
			$err['errorFone'] = "O telefone deve conter apenas números"; 
		//endereco
		if( strlen( trim( $v['rua'] ) ) == 0 )
			$err['errorRua'] = "Preencha o nome da rua";
		//bairro
		if( strlen( trim( $v['bairro'] ) ) == 0 )
			$err['errorBairro'] = "Preencha o nome do bairro";
		//cidade
		if( strlen( trim( $v['cidade'] ) ) == 0 )
			$err['errorCidade'] = "Selecione a cidade";
		//cep
		if( strlen( trim( $v['cep'] ) ) == 0 )
			$err['errorCEP'] = "Preencha o CEP";
		//numero
		if( strlen( trim( $v['numero'] ) ) == 0 )
			$err['errorNumero'] = "Preencha o número da casa";
		//ponto de referencia
		//if( strlen( trim( $v['ponto_referencia'] ) ) == 0 )
		//	$err['ponto_referencia'] = "Preencha o ponto de referência";
		/**
		 * @todo fazer a atribuĩção da rua
		 */
		$ins = 0;
		if( count( $err ) <= 0 ):
			//sem erros - faz a insercao
			unset($v['senhab']);

			//cria o hash
			$v['hash'] = md5( uniqid() );
			//encripta a senha
			$v['senha'] = sha1( $v['senha'] );
			$this->crud->insert('usuarios', $v);
			/**
			 * @todo envia o email para confirmacao de cadastro
			 */
			//grava o cookie para o ngCookie fazer a leitura
			set_cookie('cadastro_ok', 'Cadastro realizado com sucesso! Faça login abaixo.');
			//retorna ok - js faz o redirect
			echo "ok";
		else:
			//retorna todos os erros.
			echo json_encode($err) ;
		endif;
		}
	/**
	 * Faz o login no sistema
	 * @date   2016-04-18T15:09:07-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @return int 0 or 1
	 */
	public function login(){
		$v = json_decode(file_get_contents('php://input'), true);
		$v['senha'] = sha1($v['senha']);
		//print_r($v);
		//verifica se login existe.
		if( $this->crud->count( 'usuarios', array( 'email' => $v['email'], 'senha' => $v['senha'] ) )  > 0 ):
			//registra sessao.
			$dt = $this->crud->get( 'usuarios', array( 'email' => $v['email'], 'senha' => $v['senha'] ) );
			$s['user_logged'] = true;
			$s['user_email'] = $dt[0]->email;
			$s['user_id'] = $dt[0]->id;
			$s['user_confirmado'] = ($dt[0]->confirmado == 1) ? true : false;
			$s['user_unique'] = uniqid();
			$this->session->set_userdata($s);
			echo '1';
		else:
			echo '0';
		endif;
	}
	//metodos privados
	/**
	 * faz a validacao do cnpj
	 * @date   2016-04-14T15:09:52-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 * @param  string 	$cpf
	 * @return bool
	 */
	private function validaCpf( $cpf = NULL )
	{
		$valido = true;
		//verifica se foi passado e digitado de acordo com o solicitado.
		if( $cpf == NULL ||  strlen( ( $cpf ) ) != 11 )
			$valido = false;
		// Verifica se nenhuma das sequências invalidas abaixo 
	    // foi digitada. Caso afirmativo, retorna falso
	    else if ($cpf == '00000000000' || 
	        $cpf == '11111111111' || 
	        $cpf == '22222222222' || 
	        $cpf == '33333333333' || 
	        $cpf == '44444444444' || 
	        $cpf == '55555555555' || 
	        $cpf == '66666666666' || 
	        $cpf == '77777777777' || 
	        $cpf == '88888888888' || 
	        $cpf == '99999999999') {
	        $valido = false;
	     // Calcula os digitos verificadores para verificar se o
	     // CPF é válido
	     } else {   
	        for ($t = 9; $t < 11; $t++) {
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            $d = ((10 * $d) % 11) % 10;
	            if ($cpf{$c} != $d) {
	                $valido = false;
	            }
	        }
		return $valido;
		}
	}
	/**
	 * obtem os dados de cadastro da pizzaria
	 * @date   2016-05-20T09:09:55-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function get_pizzaria_dados(){
		$sel = $this->crud->get('pizzaria');
		$sel = $sel[0];
		print_r(json_encode($sel));
	}
	/**
	 * Grava os dados basicos de cadastro
	 * @date   2016-05-31T16:13:41-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function grava_dados_cadastro(){
		$v = json_decode(file_get_contents('php://input'), true);
		$v = $v['dados'];
		//valida e grava no bd.
		//remove dados que nao podem ser alterados.
		unset($v['cpf']);
		unset($v['email']);	
		unset($v['confirmado']);
		unset($v['data_add']);
		unset($v['valor_entrega']);
		unset($v['id']);
		//grava o registro.
		try{
			$this->cm->update('usuarios', $v, array('id' => $this->session->user_id));
		}catch (Exception $e) {
			echo 'erro';
		}	
	}
	/**
	 * Grava a senha no bd
	 * faz a validacao toda no backend por seguranca
	 * @date   2016-05-31T16:13:21-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	function grava_dados_seguranca(){
		$v = json_decode(file_get_contents('php://input'), true);
		$v = $v['dados'];
		$v['nova_senha'] = trim( $v['nova_senha'] );
		$v['nova_senha_confirma'] = trim( $v['nova_senha_confirma'] );
		//verifica se a senha atual está correta
		if( $this->cm->count( 'usuarios', array( 'id' => $this->session->user_id, 'senha' => sha1($v['senha']) ) ) > 0){
			//verifica se as senha tem 6 caracteres.
			if( strlen($v['nova_senha']) >= 6 ){
				//verifica se as senhas são idênticas
				if( $v['nova_senha'] === $v['nova_senha_confirma'] ){
					//atualiza a senha no bd
					$this->cm->update( 'usuarios', array('senha' => sha1($v['nova_senha'])), array('id' => $this->session->user_id) );
					
				}else{
					echo 'senhas_nao_coincidem';
				}
			}else{
				echo 'minimo_caracteres';
			}
		}else{
			echo "senha_incorreta";
		}
	}

	/**
	 * Gera o token de alteração de senha 
	 * E envia o link por email
	 * @date   2016-06-01T11:26:12-0300
	 * @author MARQUARDT, William <williammqt@gmail.com>
	 */
	public function geraTokenAltSenha(){
		$email = json_decode(file_get_contents('php://input'), true);
		$email = $email['dados'];

		//verifica se o email existe
		$sel = $this->cm->get('usuarios', array('email' => $email), 1);
		if( count($sel) > 0 ){
			//gera o token.
			$this->cm->delete('recupera_senha', array('id_usuario' => $sel[0]->id));
			$d['token'] = md5( uniqid().$this->session->user_email.rand(0,999) );
			$d['id_usuario'] = $sel[0]->id;
			$this->cm->insert('recupera_senha', $d);

			$link = base_url('recupera_senha/'.$d['token']);

			$mensagem = "Você solititou uma alteração de senha em nosso sistema de delivery.";
			$mensagem .= "<br>Para fazer a alteração de senha, por favor, acesse o link abaixo:<br>".$link;
			$mensagem .= "<br><br>Por razões de segurança este link é válido por apenas uma hora.";

			$dd['titulo'] = "Alteração de senha";
			$dd['mensagem'] = $mensagem;


			//envia o email
			$this->email->clear();
			$this->email->to($this->session->user_email);
			$this->email->from($this->config->item('system_email'),$this->config->item('empresa'));
			$this->email->subject(config_item('empresa')." - Solicitação de alteração de senha");
			$this->email->message($this->load->view('emails/default', $dd, TRUE));
			$this->email->send();
			echo "ok";
		}else{
			echo "nao_existe";
		}
	}
}
/* End of file  Ajax.php*/
/* Location: ./application/controllers/Ajax.php */