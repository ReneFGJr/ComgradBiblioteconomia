<?php
class Main extends CI_controller {
	function __construct() {
		parent::__construct();

		$this -> lang -> load("login", "portuguese");
		//$this -> lang -> load("skos", "portuguese");
		//$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('bootstrap');
		$this -> load -> helper('email');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		$this -> load -> library('tcpdf');
		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}

	function login() {
		redirect(base_url('index.php/main'));
	}

	function evento($action = '', $arg = '', $arg2 = '') {
		$this -> load -> model('events');

		$data['title'] = 'Comgrad de Biblitoeconomia da UFRGS ::::';
		$this -> load -> view('header/header', $data);

		switch($action) {
			case 'import' :
				$this -> cab(0);
				$data['content'] = $this -> events -> inport_event_incritos($arg, $arg2);
				$this -> load -> view("content", $data);
				break;

			case 'assignin' :
				$this -> cab(0);
				$data['content'] = $this -> events -> assignin($arg, $arg2);
				$this -> load -> view("content", $data);
				break;

			case 'select' :
				$this -> cab(0);
				$data['content'] = $this -> events -> select($arg, $arg2);
				$this -> load -> view("content", $data);
				break;

			case 'inscritos' :
				$this -> cab(0);
				$data['content'] = $this -> events -> inscritos($arg, $arg2);
				$this -> load -> view("content", $data);
				break;
            case 'presenca' :
                $this -> cab(0);
                $data['content'] = $this -> events -> presenca($arg, $arg2);
                $this -> load -> view("content", $data);
                break;                
			case 'booking' :
				$this -> cab(0);
				$data['content'] = $this -> events -> inscricao($arg, $arg2);
				$this -> load -> view("content", $data);
				break;
			case 'valida' :
				$this -> cab(0);
				$this -> events -> valida($arg, $arg2);
				break;
			case 'checkin' :
				$this -> cab();
				if (isset($_SESSION['event_id'])) {
					$event = $_SESSION['event_id'];
					$this -> events -> acao($event);
					$data['content'] = $this -> events -> event_checkin_form($event);
				} else {
					redirect('index.php/main/evento/select');
				}

				/*************/
				$CHK = get("checkin");
				if (strlen($CHK . $arg) > 0) {
					/**************************************** CHECKIN REGISTER ********/
					$CHK = get("checkin");
					$data['content'] .= $this -> events -> event_registra_checkin($CHK, $arg);
					if (strlen($arg) > 0) {
						redirect(base_url('index.php/main/evento/checkin'));
					}
				}
				$data['content'] .= $this -> events -> lista_inscritos($event);
				$this -> load -> view("content", $data);
				break;

			case 'print' :
				$mes = array('', 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');
				$chk = checkpost_link($arg);
				if ($chk != $arg2) {
					$sx = '
                        <br>
                        <div class="alert alert-danger" role="alert">
                          Erro de checksum do post
                        </div>
                        ';
					$this -> cab(0);
					$data['content'] = $sx;
					$this -> load -> view('content', $data);
					return ('');
				}

				$line = $this -> events -> le($arg);
				if (round($line['i_certificado']) == 0) {
					$sql = "update events_inscritos 
                                    set i_certificado = '" . date("Y-m-d H:i_s") . "'
                                    WHERE id_i = " . $line['id_i'];
					$this -> db -> query($sql);
				}
				$nr = $line['id_i'];
				$nome = trim($line['n_nome']);
				$cidade = trim($line['e_cidade']);
				$evento = trim($line['e_name']);
				$data = sonumero($line['e_data']);
				$img_file = $line['e_background'];

				$data = substr($data, 6, 2) . ' de ' . $mes[round(substr($data, 4, 2))] . ' de ' . substr($data, 0, 4) . '.';
				$ass_nome = trim($line['e_ass_none_1']);
				$ass_cargo = trim($line['e_ass_cargo_1']);

				// create new PDF document
				$pdf = new tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

				// set document information
				$pdf -> SetCreator(PDF_CREATOR);
				$pdf -> SetAuthor($evento);
				$pdf -> SetTitle('Declaração - ' . $evento);
				$pdf -> SetSubject($evento);
				$pdf -> SetKeywords($evento);

				// set header and footer fonts
				$pdf -> setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

				// set default monospaced font
				$pdf -> SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				// set margins
				$pdf -> SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf -> SetHeaderMargin(0);
				$pdf -> SetFooterMargin(0);

				// remove default footer
				$pdf -> setPrintFooter(false);

				// set auto page breaks
				$pdf -> SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

				// set image scale factor
				$pdf -> setImageScale(PDF_IMAGE_SCALE_RATIO);

				// set font
				$pdf -> SetFont('times', '', 48);

				// add a page
				$pdf -> AddPage();

				// -- set new background ---

				// get the current page break margin
				$bMargin = $pdf -> getBreakMargin();
				// get current auto-page-break mode
				$auto_page_break = $pdf -> getAutoPageBreak();
				// disable auto-page-break
				$pdf -> SetAutoPageBreak(false, 0);
				// set bacground image

				$pdf -> Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
				// restore auto-page-break status

				$pdf -> SetAutoPageBreak($auto_page_break, $bMargin);
				// set the starting point for the page content
				$pdf -> setPageMark();

				// Print a text
				$pdf -> setfont("helvetica");
				$html = '<span style="font-family: tahoma, arial; color: #333333;text-align:left;font-weight:bold;font-size:30pt;">DECLARAÇÃO</span>';
				$pdf -> writeHTML($html, true, false, true, false, '');

				$txt1 = $line['e_texto'];
				$txt1 = troca($txt1, '$nome', $nome);

				$txt2 = '<br><br>' . $cidade . ', ' . $data;

				$txt3 = '<br><br><br><br><br><br><br><br>';
				$txt3 .= '<b>' . $ass_nome . '</b>';
				$txt4 = '<br>' . $ass_cargo;

				$html = '
                <table cellspacing="0" cellpadding="0" border="0" width="445"  style="font-family: tahoma, arial; color: #333333;text-align:left; font-size:15pt; line-height: 190%;">
                    <tr>
                        <td rowspan="1" width="100%">' . $txt1 . '</td>
                    </tr>
                    <tr>
                        <td rowspan="1" width="100%" align="right">' . $txt2 . '</td>
                    </tr>
                    <tr style="font-family: tahoma, arial; color: #333333;text-align:left; font-size:15pt; line-height: 100%;">
                        <td rowspan="1" width="100%" align="center">' . $txt3 . '</td>
                    </tr>                
                    <tr style="font-family: tahoma, arial; color: #333333;text-align:left; font-size:9pt; line-height: 120%;">
                        <td rowspan="1" width="100%" align="center">
                        ' . $txt4 . '</td>
                    </tr>                
                </table>
                ';

				$img_file = $line['e_ass_img'];
				$pdf -> Image($img_file, 40, 175, 80, 30, '', '', '', false, 300, '', false, false, 0);
				$pdf -> writeHTML($html, true, false, true, false, '');

				// QRCODE,Q : QR-CODE Better error correction
				// set style for barcode
				$style = array('border' => 2, 'vpadding' => 'auto', 'hpadding' => 'auto', 'fgcolor' => array(0, 0, 0), 'bgcolor' => false, //array(255,255,255)
				'module_width' => 1, // width of a single module in points
				'module_height' => 1 // height of a single module in points
				);
				$pdf -> write2DBarcode('www.ufrgs.br/comgradbib/index.php/main/evento/valida/' . $nr . '/' . checkpost_link($nr), 'QRCODE,Q', 110, 241, 30, 30, $style, 'N');

				$pdf -> SetFont('helvetica', '', 8, '', false);
				$pdf -> Text(110, 236, 'validador do certificado');

				// ---------------------------------------------------------

				//Close and output PDF document
				$pdf -> Output('UFRGS-Certificado-' . $nome . '.pdf', 'I');

				//============================================================+
				// END OF FILE
				//============================================================+
				break;

			default :
				$this -> cab(0);
				$data['content'] = $this -> events -> certificados();
				$this -> load -> view('content', $data);
		}

	}

	private function cab($navbar = 1) {
		$this -> load -> model('socials');
		$data['title'] = 'Comgrad de Biblitoeconomia da UFRGS ::::';
		$this -> load -> view('header/header', $data);
		if ($navbar == 1) {
			$this -> load -> view('header/navbar', null);
		}
	}

	private function foot() {
		$this -> load -> view('header/footer');
	}

	public function index() {
		$this -> cab();
		$this -> load -> view('welcome');
	}

	public function bolsas() {
		$this -> cab();
		$data = array();
		$this -> load -> view('bolsas/divulgacao', $data);
	}

	public function contact() {
		$this -> load -> model('comgrads');
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> comgrads -> contact();
		$this -> load -> view('content', $data);
	}

	public function about() {
		$this -> load -> model('comgrads');
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> comgrads -> about();
		$this -> load -> view('content', $data);
	}

	public function persons($id = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();
		$form = new form;
		$form -> tabela = $this -> pags -> tabela;
		$form -> see = true;
		$form -> row = base_url('index.php/main/persons');
		$form -> row_view = base_url('index.php/main/person');
		$form -> row_edit = base_url('index.php/main/persons');
		$form -> edit = False;
		$form -> novo = False;
		$form = $this -> pags -> row($form);

		$data['title'] = 'Estudantes';
		$data['content'] = row($form, $id);
		$this -> load -> view('content', $data);
	}

	public function person($id = 0) {
		$this -> load -> model('comgrads');
		$this -> load -> model('mensagens');
		$this -> load -> model('pags');
		$this -> cab();
		$data = array();

		$data = $this -> pags -> le($id);
		$total_mensagens = $this -> mensagens -> mensagens_total($id);

		$data['title'] = $data['p_nome'];
		$data['content'] = $this -> load -> view('person/show', $data, true);
		$this -> load -> view('content', $data);

		$sx = '<div class="row">';
		$sx .= '<div class="col-md-3">';
		$sx .= $this -> load -> view('person/person_contato', $data, true);
		$sx .= '</div>';

		$sx .= '<div class="col-md-3">';
		$sx .= $this -> load -> view('person/person_curso', $data, true);
		$sx .= '</div>';

		$sx .= '<div class="col-md-3">';
		$sx .= $this -> load -> view('person/person_creditos', $data, true);
		$sx .= '</div>';

		$sx .= '<div class="col-md-3">';
		$sx .= $this -> load -> view('person/person_indicadores', $data, true);
		$sx .= '</div>';
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

		$sx = '<hr>' . $this -> mensagens -> mostra_mensagens($id);
		$sx .= $this -> mensagens -> nova_mensagem($id);
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

		$sx = $this -> load -> view('person/gr_tim', $data, true);
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

	}

	public function pag() {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();
		$data = array();
		$data['title'] = 'Comgrad/PAG';
		$file = '_documentation/estudantes-2019-1.txt';
		if (file_exists($file)) {
			$data['content'] = $this -> pags -> inport($file);
			$this -> load -> view('content', $data);
		} else {
			echo "OPS. file not found " . $file;
		}
	}

	public function persons_list() {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$data['content'] = $this -> pags -> list_acompanhamento(999999, 'p_nome');
		$data['title'] = 'lista';
		$this -> load -> view('content', $data);

		$this -> foot();

	}

	public function import_ROD() {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();
		$data = array();

		$form = new form;
		$cp = array();
		array_push($cp, array('$H8', '', '', false, true));
		array_push($cp, array('$T80:5', '', 'Cracha', True, true));
		$m = 'Informe o número do crachá dos estudantes incluindo um em cada linha, ou utilize o ";" como separador';
		array_push($cp, array('$M', '', $m, false, true));
		$op = '1:Incluir em ROD';
		$op .= '&2:Incluir em Controle de Matricula';
		$op .= '&3:Incluir em Bloqueio';
		array_push($cp, array('$O ' . $op, '', 'Cracha', True, true));
		array_push($cp, array('$B8', '', 'Incluir >>>', False, true));
		$tela = $form -> editar($cp, '');
		$data['title'] = 'Comgrad/PAG';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		if ($form -> saved > 0) {
			$t = get("dd1");
			$t = troca($t, chr(13), ';');
			$ts = splitx(';', $t);
			for ($r = 0; $r < count($ts); $r++) {
				$this -> pags -> incluir_acompanhamento($ts[$r], get("dd3"));
			}
		}
		$data['content'] = $this -> pags -> list_acompanhamento();
		$data['title'] = 'lista';
		$this -> load -> view('content', $data);
	}

	function campanha_email($arg) {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$tela = '';

		$data = $this -> pags -> le_campanha($arg);
		$data = $this -> pags -> le_campanha($arg);
		$tela .= '<table width="100%" class="table">';
		$tela .= '<tr><td width="10%">Campanha</td>
                            <td style="font-size: 150%; border-bottom: 1px solid #000000;"><b>' . $data['ca_nome'] . '</b></td>
                      </tr>';
		$tela .= '</table>';

		$cp = array();
		array_push($cp, array('$H8', '', '', false, false));
		array_push($cp, array('$S80', '', 'Título do e-mail', true, true));
		array_push($cp, array('$T80:6', '', 'Texto para o e-mail', true, true));
		array_push($cp, array('$B8', '', 'Enviar e-mail >>>', false, true));
		$form = new form;

		$tela .= $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$title = '[COMGRAD] ' . get("dd1");
			$texto = get("dd2");
			$tela = $this -> pags -> campanha_enviar_email($arg, $title, $texto);
		}
		$data['content'] = $tela;

		$this -> load -> view('content', $data);
	}

	function campanha_prepara($id) {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$data = $this -> pags -> le_campanha($id);
		$arg2 = $data['ca_acompanhamento'];

		$this -> pags -> campanha_prepara($id, $arg2);
		redirect(base_url('index.php/main/campanha/' . $id));
	}

    function campanha_selecionar_alunos($arg='')
        {
        $this -> load -> model('pags');
        $this -> cab();
        $this->pags->alunos_select($arg); 
        redirect(base_url('index.php/main/campanha/'.$arg));                           
        }


    function campanha_xls($arg = '') {
                $this -> load -> model('comgrads');
                $this -> load -> model('pags');
                $this -> cab();
                $this->pags->export_answer($arg);
                $this->foot();
        }
	function campanha($arg = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$tela = '';

		$data = $this -> pags -> le_campanha($arg);
		$tela .= '<table width="100%" class="table">';
		$tela .= '<tr><td width="10%">Campanha</td>
                            <td style="font-size: 150%; border-bottom: 1px solid #000000;"><b>' . $data['ca_nome'] . '</b></td>
                      </tr>';
		$tela .= '<tr><td></td>
                            <td>
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanhas_questionario_edit/' . $arg) . '">Editar Questionário</a>
                                |
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanhas_edit/' . $arg) . '">Editar Campanha</a>
                                |
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanha_email/' . $arg) . '">Envia e-mail</a>
                                | 
                                <a class="btn btn-primary" href="' . base_url('index.php/main/campanha_selecionar_alunos/' . $arg) . '">Marcar Alunos</a>
                                | 
                                <a class="btn btn-warning" href="' . base_url('index.php/main/campanha_cancela_alvo/' . $arg) . '">Excluir todos</a>
                            </td>
                      </tr>';
		$tela .= '</table>';

		$tela .= $this -> pags -> campanha_situacao($arg);

		$data['content'] = $tela;
		$data['title'] = 'lista';
		$this -> load -> view('content', $data);
		$this -> foot();
	}

	function campanha_cancela_alvo($id = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$this -> pags -> cancela_campanha($id);
		redirect(base_url('index.php/main/campanha/' . $id));

	}

	function campanhas($id = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$form = new form;
		$form -> tabela = 'campanha';
		$form -> see = true;
		$form -> row = base_url('index.php/main/campanhas');
		$form -> row_view = base_url('index.php/main/campanha');
		$form -> row_edit = base_url('index.php/main/campanhas_edit');

		$form -> edit = True;
		$form -> novo = True;
		$form = $this -> pags -> row_campanhas($form);

		$data['title'] = 'Estudantes';
		$data['content'] = row($form, $id);
		$this -> load -> view('content', $data);
	}

	function campanhas_edit($id = '', $chk = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();

		$cp = $this -> pags -> cp_campanhas($id);
		$form = new form;
		$form -> id = $id;
		$data['content'] = $form -> editar($cp, 'campanha');
		$data['title'] = msg('campanhas');
		$this -> load -> view('content', $data);

		if ($form -> saved > 0) {
			redirect(base_url('index.php/main/campanhas'));
		}

	}

	function questionario_ver($id = '', $chk = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab(0);

		$chk2 = checkpost_link($id);
		if ($chk2 == $chk) {
			$tela = $this -> pags -> questionario_ver($id);
			$data['content'] .= $tela;
			$data['title'] = 'lista';
			$this -> load -> view('content', $data);
		} else {
			echo '=' . $chk . '<br>=' . $chk2;
		}
	}
    
    function campanhas_questionario_edit($arg1='')
        {
        $this -> load -> model('comgrads');
        $this -> load -> model('pags');
        $this -> cab();
        $data['content'] = $this -> pags -> questionario_editar($arg1);
        $this->load->view('content',$data);
        $this->foot();            
        }
    function campanhas_questionario_editar($arg1='',$arg2='')
        {
        $this -> load -> model('comgrads');
        $this -> load -> model('pags');
        $this -> cab(0);
        $data['content'] = $this -> pags -> questionario_editar_cp($arg2,$arg1);
        $this->load->view('content',$data);            
        }

	function questionario($arg1 = '', $arg2 = '', $chk = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab(0);

		$chk2 = checkpost_link($arg1 . $arg2);
		if ($chk2 != $chk) {
			$data['content'] = 'Checksun do link é inválido';
			$this -> load -> view('errors/error', $data);
			return ("");
		}

		$data['content'] = $this -> pags -> questionario($arg1, $arg2);
		$data['content'] .= $this -> load -> view('header/form_style', null, true);
		$data['title'] = 'lista';
		$this -> load -> view('content', $data);
	}

	function relatorio($rel = '1', $arg1 = '', $arg2 = '', $arg3 = '') {
		$this -> load -> model('comgrads');
		$this -> load -> model('pags');
		$this -> cab();
		$data = array();
		switch($rel) {
			case '1' :
				$data['content'] = $this -> pags -> rel_alunos_matriculados();
				$data['title'] = 'lista';
				break;
			case '2' :
				$data['content'] = $this -> pags -> rel_alunos_periodo($arg1, $arg2);
				$data['title'] = 'lista';
				break;
			case '3' :
				$data['content'] = $this -> pags -> rel_tempo_medio_integralizacao($arg1, $arg2);
				$data['title'] = 'lista';
				break;
			case '4' :
				$data['content'] = $this -> comgrads -> rel_bairros($arg1, $arg2);
				$data['title'] = 'lista';
				break;
            case '5' :
                $data['content'] = $this -> comgrads -> rel_email($arg1, $arg2);
                $data['title'] = 'lista';
                break;                
		}

		$this -> load -> view('content', $data);
	}

	function cliente_mensagem_edit($id, $cliente) {
		$this -> load -> model('mensagens');
		//$this -> load -> model('clientes');

		$data['nocab'] = true;
		$this -> cab($data);

		$cp = $this -> mensagens -> cp($cliente);
		$form = new form;
		$form -> id = $id;
		$data['content'] = $form -> editar($cp, $this -> mensagens -> table);
		$data['title'] = msg('mensagens');
		$this -> load -> view('content', $data);

		if ($form -> saved > 0) {
			if (get("dd5") == '1') {
				$assunto = utf8_decode(get("dd3"));
				$text = utf8_decode(get("dd4"));
				$de = 1;
				$anexos = array();
				$this -> clientes -> enviaremail_cliente($cliente, $assunto, $text, $de, $anexos);
			}
			$data['content'] .= '<script> wclose(); </script>';
			$this -> load -> view('content', $data);
		}
	}

	function mailer() {
		$this -> load -> model('mensagens');
		//$this -> load -> model('clientes');

		$data['nocab'] = true;
		$this -> cab($data);

		$cp = array();
		array_push($cp, array('$H8', '', '', False, True));
		array_push($cp, array('$T80:6', '', 'Lista de e-mail', True, True));
		array_push($cp, array('$Q id_m:m_descricao:select * from mensagem_own where m_ativo = 1', '', 'Enviador', True, True));
		array_push($cp, array('$S80', '', msg('msg_subject'), True, True));
		array_push($cp, array('$T80:5', '', msg('msg_content'), True, True));
		array_push($cp, array('$O 0:NÃO&1:SIM', '', 'Enviar e-mail ao cliente', True, True));

		$form = new form;
		$data['content'] = $form -> editar($cp, '');
		$data['title'] = msg('mensagens');
		$this -> load -> view('content', $data);

		if ($form -> saved > 0) {
			if (get("dd5") == '1') {
				$assunto = utf8_decode(get("dd3"));
				$text = utf8_decode(get("dd4"));
				$de = 1;
				$anexos = array();
				$l = get("dd1");
				$l = troca($l, chr(13), ';');
				$l = troca($l, chr(10), '');
				$ln = splitx(';', $l);
				for ($r = 0; $r < count($ln); $r++) {
					$email = $ln[$r];
					echo '<br>==>' . $email;
					$to = $email;
					$subject = $assunto;
					$message = $text;
					$headers = 'From: PPGCIN-UFRGS<ppgcin@ufrgs.br>' . "\r\n" . 'Reply-To: ppgcin@ufrgs.br' . "\r\n" . 'X-Mailer: PHP/' . phpversion(). "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1';

					mail($email, $assunto, $text, $headers);
				}
			}
			$data['content'] = '';
			$this -> load -> view('content', $data);
		}
	}

	function prerequisito_nr($nr) {
		$this -> load -> model('comgrads');
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> comgrads -> prerequisito_nrs($nr);
		$data['content'] .= $this -> comgrads -> avaliacao($nr);

		$this -> load -> view('content', $data);
	}

	function prerequisito() {
		$this -> load -> model('comgrads');
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> comgrads -> prerequisito_form();

		$this -> load -> view('content', $data);
	}

	function prerequisito_analise() {
		$this -> load -> model('comgrads');
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> comgrads -> prerequisito_analise();

		$data['content'] .= '<a href="' . base_url('index.php/main/prerequisito') . '" class="btn btn-secondary">Solicitar quebra</a>';

		$this -> load -> view('content', $data);
	}

	function show_perfil() {
		$id = $_SESSION['id'];
		$this -> load -> model('socials');

		$this -> cab();
		$data['title'] = '';
		$data['content'] = $this -> socials -> show_perfil($id);
		$this -> load -> view('content', $data);
	}

}
?>
