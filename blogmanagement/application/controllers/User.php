<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	
	public function __construct(){

		parent :: __construct();
		$this->load->helper("url");
		$this->load->library(array("form_validation","session"));
		$this->load->helper("form_helper");
		$this->load->model("user_model");
		//$this->load->library("database");
	}

	public function login()
	{
		//$this->load->view('welcome_message');
		$this->load->view('login');
	}

	public function create()
	{

		$this->form_validation->set_rules("firstname","First Name",'required|xss_clean');
		$this->form_validation->set_rules("lastname","Last Name",'required|xss_clean');
		$this->form_validation->set_rules("email","Email",'required|xss_clean');
		$this->form_validation->set_rules("password","Password",'required|xss_clean');
		$this->form_validation->set_rules("dob","Dob",'required|xss_clean');
		//$this->form_validation->set_rules("image","First Name",'required|xss_clean');
		$this->form_validation->set_rules("role","Role",'required|xss_clean');


	//	if($this->form_validation->run()== true){


			//echo "hiii" ; die;
			$dob  = date('Y-m-d',strtotime($this->input->post('dob')));
			
			$data = array(

				'firstname' => $this->input->post('firstname'),
				'lastname'  => $this->input->post('lastname'),
				'email'     => $this->input->post('email'),
				'password'  => md5($this->input->post('password')),
				'dob'       => $dob,
				'role'      => $this->input->post('role'),
				);
		//	print_r($data);die;

			$insert_id = $this->user_model->insertuser($data);

			$config['upload_path'] = "./userimage" ;
			$config['allowed_types'] = "*" ;
			$config['max_size']  = 100 ;
			$config['max_width'] = 1024 ;
			$config['max_height'] = 768;
			$this->load->library("upload",$config);
		//	$this->upload->initialize($config);

			if(!empty($_FILES['user_pic']['name'])){

				if(!$this->upload->do_upload('user_pic')){
					$arr = $this->upload->display_errors();
					print_r($arr);die;
				}else{
					$this->upload->data();
				}


			}

			$userimage = $_FILES['user_pic']['name'] ;

			$image_data = array('image'=> $userimage );

			$this->user_model->updateuser($insert_id,$image_data);
			$this->sessoin->set_flashdata("msg","Record Added Successfully");

			//echo $insert_id ;die;

		// }else{
		//  	redirect("welcome/signup");
		//  }
	}

	public function userlogin()
	{

		$email =$this->input->post("email");
		$password =$this->input->post("password");

		$result = $this->user_model->getuser($email,$password);

		//echo $this->db->last_query();die;
		//print_r($result);die;

		if(!empty($result)){

			$session_data = array(

				'user_id' => $result[0]['user_id'],
				'email'   =>  $result[0]['email'],
				'role'   => $result[0]['role'],
				'image'  => $result[0]['image']
				) ;

			$this->session->set_userdata("user",$session_data) ;

			$userdata = $this->session->userdata("user") ;

		//	print_r($userdata);die;
			redirect('blog/create');

		}else{
			$this->session->set_flashdata("msg","Invalid Username or Password");
		}


	}
}
