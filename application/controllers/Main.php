<?php
class Main extends CI_controller
	{
	function __construct() {
		parent::__construct();

		$this -> lang -> load("login", "portuguese");
		//$this -> lang -> load("skos", "portuguese");
		//$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}		
	public function cab($navbar=1)
		{
			$data['title'] = 'Comgrad de Biblitoeconomia da UFRGS ::::';
			$this->load->view('header/header',$data);
			if ($navbar==1)
				{
					$this->load->view('header/navbar',null);
				}
		}
	public function index()
		{
			$this->cab();
			$this->load->view('welcome');
		}	
	public function bolsas()
		{
			$this->cab();
			$data = array();
			$this->load->view('bolsas/divulgacao',$data);
		}
	public function contact()
		{
			$this->load->model('comgrads');
			$this->cab();
			$data = array();
			$data['title'] = '';
			$data['content'] = $this->comgrads->contact();
			$this->load->view('content',$data);
		}
	public function about()
		{
			$this->load->model('comgrads');
			$this->cab();
			$data = array();
			$data['title'] = '';
			$data['content'] = $this->comgrads->about();
			$this->load->view('content',$data);
		}				
	}
?>
