<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function insertuser($data)
	{

		//echo "hiii";die;
	$this->db->insert('bloguser',$data);
	//echo $this->db->last_query();die;
		return $this->db->insert_id();

	}

	public function updateuser($id,$data)
	{

		$this->db->where('user_id',$id);
		$this->db->update("bloguser",$data);

	}

	public function getuser($email,$password)
	{
		$pass = md5($password) ;
		$condition = array("email"=>$email, "password"=>$pass);
		$this->db->where($condition);
		$this->db->select("*");
		$this->db->from("bloguser");
		$query = $this->db->get() ;
		$result = $query->result_array();

	//	print_r($result);die;
		return $result  ;


	}
}
