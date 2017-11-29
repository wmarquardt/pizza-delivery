<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Crud_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    /**
     * funcao principal para obtencao de dados no sistema
     * @author William Marquardt <williammqt@gmail.com>
     * @date   2015-06-18
     * @param  string     $table     nome da tabela
     * @param  array      $condition condicao em array no formado da selecao no active record campo => valor
     * @param  integer    $limit     limite de selecao - 0 para selecionar todos
     * @param  array      $order     ordenação de campos ex: 'nome' => 'ASC'
     * @param  string     $fields    campos a selecionar, separados por ',' igual ao colocado na funcao select do active record
     * @param  string     $group_by  agrupamento de campos, passado em string seprado por vírgulas
     * @return array                retorna o get()->result() do active record
     */
    public function get($table, $condition = [], $limit = 0, $order = [], $fields = NULL, $group_by = ""){
        //define condicoes
        if ( count($condition) > 0 ):
            foreach ($condition as $campo => $valor) {
                if(!is_array($valor)){
                    $this->db->where($campo, $valor);
                }else{
                    for($i=0; $i < count($valor); $i++){
                        $this->db->where($campo, $valor[$i]);
                    }
                }
            }
        endif;
        //define campos
        if ( $fields != NULL ):
            $this->db->select($fields);
        endif;
        //define limite
        if($limit > 0)
            $this->db->limit($limit);
        //define agrupamento
        if($group_by)
            $this->db->group_by($group_by);
        //define ordenacao
        if ( count($order) > 0 ):
            foreach ($order as $campo => $valor) {
                $this->db->order_by($campo, $valor);
            }
        endif;
        //obtem os dados;
        $getData =  $this->db->get($table);
        $ret = $getData->result();
        return $ret;
    }
    /**
     * atualiza registros
     * @author William Marquardt <williammqt@gmail.com>
     * @date   2015-06-18
     * @param  string     $tabela   tabela a ser atualizada
     * @param  array      $condicao condicao no formato de where do active record $campo => $valor
     * @param  array      $dados    array de dados referente ao valor adicionado - formato $campo => $valor
     * @return void               
     */
    function update($tabela, $dados, $condition = array()) {
        $this->db->trans_start();
        if ( count($condition) > 0 ):
            foreach ($condition as $campo => $valor) {
                if(!is_array($valor)){
                    $this->db->where($campo, $valor);
                }else{
                    for($i=0; $i < count($valor); $i++){
                        $this->db->where($campo, $valor[$i]);
                    }
                }
            }
        endif;
        $this->db->update($tabela, $dados);
        $ret =  $this->db->affected_rows();
        $this->db->trans_complete();
        return $ret;
    }
    function insert($tabela, $dados) {
        $this->db->trans_start();
        $this->db->insert($tabela, $dados);
        $id_ins = $this->db->insert_id();
        $this->db->trans_complete();
        return $id_ins;
    }
    function insert_batch($tabela, $dados) {
        $this->db->trans_start();
        $this->db->insert_batch($tabela, $dados);
        $this->db->trans_complete();
    }
    function delete($tabela, $condition) {
        $this->db->trans_start();
        if ( count($condition) > 0 ):
            foreach ($condition as $campo => $valor) {
                if(!is_array($valor)){
                    $this->db->where($campo, $valor);
                }else{
                    for($i=0; $i < count($valor); $i++){
                        $this->db->where($campo, $valor[$i]);
                    }
                }
            }
        endif;
        $this->db->delete($tabela);
        $aft= $this->db->affected_rows();
        $this->db->trans_complete();
        return $aft;
    }
    function query($sql){
        $this->db->trans_start();
        $query = $this->db->query($sql);
        return $query->result();
        $this->db->trans_complete();
    }
    /**
     * conta o numero de linhas de determinada tabela
     * @author MARQUARDT, william <williammqt@gmail.com>
     * @date   2015-06-25
     * @param  string     $tabela   tabela
     * @param  array      $condicao condição em array no formato campo => valor
     * @return int                  numero de linhas
     */
    function count($tabela, $condition = array()){
        $this->db->select('COUNT(*) as count');
        if ( count($condition) > 0 ):
            foreach ($condition as $campo => $valor) {
                if(!is_array($valor)){
                    $this->db->where($campo, $valor);
                }else{
                    for($i=0; $i < count($valor); $i++){
                        $this->db->where($campo, $valor[$i]);
                    }
                }
            }
        endif;
        $query = $this->db->get($tabela);
        $ret = $query->row()->count;
        return $ret;
    }
    function getJoined($tabela, $campos="*", $join=array(), $condition=array(),  $group_by = "", $order = array(), $limit = 0, $distinct = FALSE) {
        $this->db->select($campos, FALSE);
        $this->db->from($tabela);
        if($join) {
            foreach ($join as $row => $value) {
                //verifica se valor eh array
                if( is_array($value) ){
                    $this->db->join($row, $value[0], $value[1]);
                }else{
                    $this->db->join($row, $value);
                }
            }
        }
        //define ordenacao
        if ( count($order) > 0 ):
            foreach ($order as $campo => $valor) {
                $this->db->order_by($campo, $valor);
            }
        endif;
        if ( count($condition) > 0 ):
            foreach ($condition as $campo => $valor) {
                if(!is_array($valor)){
                    $this->db->where($campo, $valor);
                }else{
                    for($i=0; $i < count($valor); $i++){
                        $this->db->where($campo, $valor[$i]);
                    }
                }
            }
        endif;
        //define agrupamento
        if($group_by)
            $this->db->group_by($group_by);
        //define limite
        if($limit > 0)
            $this->db->limit($limit);
        if($distinct)
            $this->db->distinct();
        $query = $this->db->get();
        $ret = $query->result();
        return $ret;
    }
}