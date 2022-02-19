<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){

		parent :: __construct();
		$this->load->helper("url");
		$this->load->library("form_validation");
		$this->load->library("session");
		$this->load->helper("form_helper");
		$this->load->model("blog_model");
	}

	public function index()
	{
		
		$user_id = "" ;
		$role = "" ;
		if($this->session->userdata("user")){

			$userdata = $this->session->userdata("user") ;

			$user_id = $userdata['user_id'];
			$user_role =  $userdata['role'];
		}
		$data["bloglist"] = $this->blog_model->getbloglist($user_id,$role);
		//print_r($data["bloglist"]);die;
		$this->load->view("bloglist",$data);

		$this->load->view('bloglist');
	}

	public function signup()
	{
		//$this->load->view('welcome_message');
		//$this->load->view('index');

		$this->load->view('index');
	}
}
