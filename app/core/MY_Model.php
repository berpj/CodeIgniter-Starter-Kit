<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function set_table($table)
    {
        $this->table = $table;
    }
    
    public function create($options_echappees = array(), $options_non_echappees = array())
    {
        if (empty($options_echappees) AND empty($options_non_echappees))
        {
            return false;
        }
        
        $this->db->set($options_echappees)
        ->set($options_non_echappees, null, false)
        ->insert($this->table);
        return $this->db->insert_id();
    }
    
    public function read($select = '*', $where = array(), $nb = 100000000, $debut = null)
    {
        return $this->db->select($select)
        ->from($this->table)
        ->where($where)
        ->limit($nb, $debut)
        ->get()
        ->result();
    }

    public function update($where, $options_echappees = array(), $options_non_echappees = array())
    {
        if (empty($options_echappees) AND empty($options_non_echappees))
        {
            return false;
        }
        
        if (is_integer($where))
        {
            $where = array('id' => $where);
        }
        
        return (bool) $this->db->set($options_echappees)
        ->set($options_non_echappees, null, false)
        ->where($where)
        ->update($this->table);
        
    }
    
    public function delete($where)
    {
        if (is_integer($where))
        {
            $where = array('id' => $where);
        }
        
        return (bool) $this->db->where($where)
        ->delete($this->table);
    }

    public function count($champ = array(), $valeur = null)
    {
        return (int) $this->db->where($champ, $valeur)
        ->from($this->table)
        ->count_all_results();
    }
}