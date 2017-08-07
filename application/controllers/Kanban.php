<?php
class kanban extends CI_Controller
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
			$data['title'] = ':: ReDD - Login ::';
			$this->load->view('header/header',$data);
			if ($navbar==1)
				{
					$this->load->view('header/navbar',null);
				}
		}
	public function index()
		{
			$this->cab();
			
			$id = get("dd0");
			$table = 'kanban_tasks';
			if ($this->exist_table($table) == 0) { $this->create_table($table); };
			$this->show();
			$this->edit_task($id);
		}
		
		
	/****************************************** MODEM **********************/
	private function edit_task($id)
		{
			$form = new form;
			$cp = array();
			array_push($cp,array('$H8','id_kb','',false,false));
			array_push($cp,array('$S30','kb_project','Project',true,true));
			array_push($cp,array('$S100','kb_title','Title',true,true));
			array_push($cp,array('$T80:5','kb_text','Description',true,true));
			array_push($cp,array('$S50','kb_who','Who',false,true));
			$op = '1:to do';
			$op .= '&2:doing';
			$op .= '&3:done';
			$op .= '&10:close';			
			array_push($cp,array('$O '.$op,'kb_status','Status',true,true));
						
			$op = '0:normal';
			$op .= '&1:high';
			$op .= '&2:urgent';		
			array_push($cp,array('$O '.$op,'kb_priority','Priority',true,true));						
			
			$form -> id = $id;
			$tela = $form->editar($cp,'kanban_tasks');
			$data['content'] = $tela;
			
			$this->load->view('content',$data);
			
			if ($form->saved > 0)
				{
					redirect(base_url('index.php/kanban'));
				}
		}
	private function show()
		{
			$sql = "select * from kanban_tasks where kb_status < 10 order by kb_status, kb_priority desc, kb_date";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$pos = 0;
			$sx = '';
			$sx .= '<div class="row">';
			$sx .= '<div class="col-md-4 text-center" style="border-right: 2px solid #808080; border-bottom: 2px solid #808080;"><b>TO DO</b></div>';
			$sx .= '<div class="col-md-4 text-center" style="border-right: 2px solid #808080; border-bottom: 2px solid #808080;"><b>DOING</b></div>';
			$sx .= '<div class="col-md-4 text-center" style="border-bottom: 2px solid #808080;"><b>DONE</b></div>';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$status = $line['kb_status'];
					if ($status <> $pos)
						{
							if ($pos > 0)
								{
									$sx .= '</div>';
								}

							for ($x=($pos+1);$x < $status; $x++) { $sx .= '<div class="col-md-4 text-center"></div>';}
							switch ($status)
								{
								case '1':
									$sx .= '<div class="col-md-4 text-center" style="border-right: 2px solid #808080; background-color:#ffe0e0;">';
									break;
								case '2':
									$sx .= '<div class="col-md-4 text-center" style="border-right: 2px solid #808080; background-color:#e0ffff;">';
									break;
								case '3':
									$sx .= '<div class="col-md-4 text-center" style="background-color:#e0ffe0;">';
									break;
								}
							$pos = $status;
						}
					$link = '<a href="'.base_url('index.php/kanban?dd0='.$line['id_kb']).'">';
					$sx .= '<div style="padding: 5px; border: 1px solid #000000; margin: 5px 15px; min-height: 80px; background-color: yellow;">';
					$sx .= '<tt>'.$link.$line['kb_project'].':'.$line['kb_title'].'</a>'.'</tt>';
					$sx .= '<div class="text-right small">'.stodbr($line['kb_date']).'</div>';
					if (strlen($line['kb_who']) > 0)
						{
							$sx .= '<div class="text-center middle">'.UpperCaseSql($line['kb_who']).'</div>';
						}
					$sx .= '</div>';
				}

			$data['content'] = $sx;
			$this->load->view('content',$data);
		}
	private function create_table($table = 'table')
		{
			switch($table)
				{
				case 'kanban_tasks':
					$sql = "
						CREATE TABLE kanban_tasks (
						  id_kb SERIAL NOT NULL,
						  kb_title char(255) NOT NULL,
						  kb_text longtext NOT NULL,
						  kb_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						  kb_status int(11) NOT NULL,
						  kb_project char(30) NOT NULL,
						  kb_who char(30) NOT NULL,
						  kb_priority int(11) DEFAULT 0
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;
					";
					$this->db->query($sql);
					break;
				}
		}	
	private function exist_table($table = 'table')
		{
			$result = $this->db->query("SHOW TABLES LIKE '$table'");
			$tableExists = $result->num_rows();	
			return($tableExists);		
		}	
	}
?>
