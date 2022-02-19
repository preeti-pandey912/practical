<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	
	public function __construct(){

		parent :: __construct();
		$this->load->helper("url");
		$this->load->library(array("form_validation","session"));
		$this->load->helper("form_helper");
		$this->load->model("user_model");
		$this->load->model("blog_model");
		//$this->load->library("database");
	}

	public function index()
	{
		//$this->load->view('welcome_message');
		//$this->load->view('index');
	}

	public function addblog(){
		$this->load->view("addblog");
	}

	public function create()
	{

		$userdata = $this->session->userdata("user") ;

	   $user_id = $userdata['user_id'] ;

		$this->form_validation->set_rules("title","First Name",'required|xss_clean');
		$this->form_validation->set_rules("description","Last Name",'required|xss_clean');
		$this->form_validation->set_rules("start_date","First Name",'required|xss_clean');
		$this->form_validation->set_rules("end_date","First Name",'required|xss_clean');
		$this->form_validation->set_rules("is_active","First Name",'required|xss_clean');
		//$this->form_validation->set_rules("image","First Name",'required|xss_clean');
		//$this->form_validation->set_rules("role","Role",'required|xss_clean');

//echo "hiii" ; die;
		//if($this->form_validation->run()== true){


			//echo "hiii" ; die;
			$start_date  = date('Y-m-d',strtotime($this->input->post('start_date')));
			$end_date  = date('Y-m-d',strtotime($this->input->post('end_date')));
			
			$data = array(

				'blog_title' => $this->input->post('title'),
				'blog_description'  => $this->input->post('description'),
				'start_date'     => $start_date,
				'end_date'      => $end_date ,
				'is_active'       => $this->input->post('is_active'),
				'blog_user_id' =>  $user_id 
				);
		//	print_r($data);die;

			$insert_id = $this->blog_model->insertblog($data);

			$config['upload_path'] = "./blogimage" ;
			$config['allowed_types'] = "*" ;
			$config['max_size']  = 100 ;
			$config['max_width'] = 1024 ;
			$config['max_height'] = 768;
			$this->load->library("upload",$config);
		//	$this->upload->initialize($config);

			if(!empty($_FILES['blog_image']['name'])){

				if(!$this->upload->do_upload('blog_image')){
					$arr = $this->upload->display_errors();
					print_r($arr);die;
				}else{
					$this->upload->data();
				}


			}

			$userimage = $_FILES['blog_image']['name'] ;

			$image_data = array('blog_image'=> $userimage );

			$this->blog_model->updateblog($blog_id,$image_data);
			$this->sessoin->set_flashdata("msg","Record Added Successfully");

			//echo $insert_id ;die;

		// }else{
		// 	redirect("welcome/index");
		// }
	}

	public function userlogin()
	{

		$email =$this->input->post("email");
		$password =$this->input->post("password");

		$result = $this->user_model->getuser($email,$password);

		if(!empty($result)){

			$session_data = array(

				'user_id' => $result['user_id'],
				'email'   =>  $result['email'],
				'role'   => $result['role'],
				'image'  => $result['image']
				) ;

			$this->session->set_userdata("user",$session_data) ;
			redirect('blog/create');

		}else{
			$this->session->set_flashdata("msg","Invalid Username or Password");
		}


	}


	public function bloglist(){


		$userdata = $this->session->userdata("user") ;
		$user_id = "" ;
		$role = "" ;
		if(!empty($userdata)){

			$user_id = $userdata['user_id'];
			$user_role =  $userdata['role'];
		}
		$data["bloglist"] = $this->blog_model->getbloglist($user_id,$role);
		//print_r($data["bloglist"]);die;
		$this->load->view("bloglist",$data);
	}
}
