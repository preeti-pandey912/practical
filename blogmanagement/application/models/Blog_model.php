<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {

	public function insertblog($data)
	{

		//echo "hiii";die;
	$this->db->insert('blog',$data);
	//echo $this->db->last_query();die;
		return $this->db->insert_id();

	}

	public function updateblog($id,$data)
	{

		$this->db->where('blog_id',$id);
		$this->db->update("blog",$data);

	}

	public function getbloglist($id=null,$role=null)
	{
	//	$pass = md5($password) ;
		//$condition = array("email"=>$email, "password"=>$pass);

		//$this->db->where($condition);
		$today_date = date('Y-m-d');
		if($role==2 ){
			$this->db->where('blog_user_id',$id);
		}
		$this->db->where("start_date<=",$today_date);
		$this->db->where("end_date>=",$today_date);
		$this->db->select("*");
		$this->db->from("blog");
		$query = $this->db->get() ;
		$result = $query->result_array();

	//	print_r($result);die;
		return $result  ;


	}
}
