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
    public function person($id=0)
        {
            $this->load->model('comgrads');
            $this->load->model('pags');
            $this->cab();
            $data = array();
            
            $data = $this->pags->le($id);
            
            $data['title'] = $data['p_nome'];
            $data['content'] = $this->load->view('person/show',$data,true);
            $data['content'] .= $this->load->view('person/show_contato',$data,true);
            $this->load->view('content',$data);         
            
        }
	public function pag()
		{
			$this->load->model('comgrads');
			$this->load->model('pags');
			$this->cab();
			$data = array();
			$data['title'] = 'Comgrad/PAG';
			$file = '_documentation/estudantes_2017-10-05.txt';
			$data['content'] = $this->pags->inport($file);
			$this->load->view('content',$data);			
		}			
	}
?>
