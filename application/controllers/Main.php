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
        $this -> load -> helper('email');
        $this -> load -> helper('url');
        $this -> load -> library('session');
        $this -> load -> library('tcpdf');
        date_default_timezone_set('America/Sao_Paulo');
        /* Security */
        //		$this -> security();
    }

    function login() {
        $_SESSION['user'] = 'COMGRADBIB';
        redirect(base_url('index.php/main'));
    }

    function evento($action='',$arg='') {
        $this->load->model('events');

        $data['title'] = 'Comgrad de Biblitoeconomia da UFRGS ::::';
        $this -> load -> view('header/header', $data);
        
        switch($action)
            {
            case 'checkin':
                $this->cab();
                $event = 1;
                $this->events->acao($event);
                
                $data['content'] = $this->events->event_checkin_form($event);               
                
                
                /*************/
                $CHK = get("checkin");
                if (strlen($CHK.$arg) > 0)
                    {
                        /**************************************** CHECKIN REGISTER ********/
                        $CHK = get("checkin");
                        $data['content'] .= $this->events->event_registra_checkin($CHK,$arg);
                        if (strlen($arg) > 0)
                            {
                                redirect(base_url('index.php/main/evento/checkin'));
                            }
                    }
                $data['content'] .= $this->events->lista_inscritos($event);  
                $this->load->view("content",$data);
                break;
                
            case 'print':
                $mes = array('','janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro');
                $nr = 1021;
                $nome = 'RENE FAUSTINO GABRIEL JUNIOR';
                $cidade = 'Porto Alegre';
                $data = date("d").' de '.$mes[round(date("m"))].' de '.date("Y").'.';
                $ass_nome = "Rita do Carmo F. Laipelt";
                $ass_cargo = "Coordenadora da Comgrad de Biblioteconomia/UFRGS";
                
                // create new PDF document
                $pdf = new tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                
                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Comgrad Biblioteconomia - UFRGS');
                $pdf->SetTitle('Declaração - 70 anos de Biblioteconomia');
                $pdf->SetSubject('Biblioteconomia. UFRGS. 70 anos');
                $pdf->SetKeywords('70 anos, UFRGS, Biblioteconomia');
                
                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                
                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(0);
                $pdf->SetFooterMargin(0);
                
                // remove default footer
                $pdf->setPrintFooter(false);
                
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                
                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                
                // set font
                $pdf->SetFont('times', '', 48);
                
                // add a page
                $pdf->AddPage();
                
                
                // -- set new background ---
                
                // get the current page break margin
                $bMargin = $pdf->getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = $pdf->getAutoPageBreak();
                // disable auto-page-break
                $pdf->SetAutoPageBreak(false, 0);
                // set bacground image
                $img_file = 'img/certificado/cert_biblio_003.jpg';
                $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                // restore auto-page-break status

                
                $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                $pdf->setPageMark();
                
                
                // Print a text
                $pdf->setfont("helvetica");
                $html = '<span style="font-family: tahoma, arial; color: #333333;text-align:left;font-weight:bold;font-size:30pt;">DECLARAÇÃO</span>';
                $pdf->writeHTML($html, true, false, true, false, '');
                
                $txt1 = 'Declaro, para os devidos fins, que ';
                $txt1 .= '<b>'.$nome.'</b>';
                $txt1 .= ' participou da ';
                $txt1 .= ' palestra ';
                $txt1 .= ' proferida pela ';
                $txt1 .= '<b>Profa. Dra. Marisa Brascher Basilio Medeiros</b>';
                $txt1 .= ' intitulada ';
                $txt1 .= '"Panorama da Pós-Graduação em Ciência da Informação no Brasil: oportunidades de formação e pesquisa"';
                $txt1 .= ' e das atividades dos 70 anos do Curso de Biblioteconomia da UFRGS ';
                $txt1 .= ' dia 05 de dezembro de 2017, no horário das 09h às 12h no Auditório 1 da FABICO/UFRGS, totalizando três horas.';                

                $txt2 = '<br><br>'.$cidade.', '.$data;
                
                $txt3 = '<br><br><br><br><br><br><br><br>';
                $txt3 .= '<b>'.$ass_nome.'</b>';
                $txt4 = '<br>'.$ass_cargo;
                
                $html = '
                <table cellspacing="0" cellpadding="0" border="0" width="445"  style="font-family: tahoma, arial; color: #333333;text-align:left; font-size:15pt; line-height: 190%;">
                    <tr>
                        <td rowspan="1" width="100%">'.$txt1.'</td>
                    </tr>
                    <tr>
                        <td rowspan="1" width="100%" align="right">'.$txt2.'</td>
                    </tr>
                    <tr style="font-family: tahoma, arial; color: #333333;text-align:left; font-size:15pt; line-height: 100%;">
                        <td rowspan="1" width="100%" align="center">'.$txt3.'</td>
                    </tr>                
                    <tr style="font-family: tahoma, arial; color: #333333;text-align:left; font-size:9pt; line-height: 120%;">
                        <td rowspan="1" width="100%" align="center">
                        '.$txt4.'</td>
                    </tr>                
                </table>
                ';                

                $img_file = 'img/certificado/ass_rita.jpg';
                $pdf->Image($img_file, 40, 175, 80, 30, '', '', '', false, 300, '', false, false, 0);

                //$html .= '<div style="text-align: right; width: 100%">';
                //$html .= $cidade.', '.$data;
                //$html .= '</div>';
                
                
                
                $pdf->writeHTML($html, true, false, true, false, '');
                
                
                // QRCODE,Q : QR-CODE Better error correction
                // set style for barcode
                $style = array(
                    'border' => 2,
                    'vpadding' => 'auto',
                    'hpadding' => 'auto',
                    'fgcolor' => array(0,0,0),
                    'bgcolor' => false, //array(255,255,255)
                    'module_width' => 1, // width of a single module in points
                    'module_height' => 1 // height of a single module in points                    
                );                
                $pdf->write2DBarcode('www.ufrgs.br/comgrad/main/evento/valida/', 'QRCODE,Q', 110, 241, 30, 30, $style, 'N');
                
                $pdf->SetFont('helvetica', '', 8, '', false);
                $pdf->Text(110, 236, 'validador do certificado');
                
                // ---------------------------------------------------------
                
                //Close and output PDF document
                $pdf->Output('UFRGS-Certificado'.$nr.'.pdf', 'I');
                
                //============================================================+
                // END OF FILE
                //============================================================+
                break;
            }
        
    }


    private function cab($navbar = 1) {
        $data['title'] = 'Comgrad de Biblitoeconomia da UFRGS ::::';
        $this -> load -> view('header/header', $data);
        if ($navbar == 1) {
            $this -> load -> view('header/navbar', null);
        }
        $_SESSION['id'] = 1;
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
        $file = '_documentation/estudantes-2012-2.txt';
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
        array_push($cp, array('$S80', '', 'Sítulo do e-mail', true, true));
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
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanhas_edit/' . $arg) . '">Editar Campanha</a>
                                |
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanha_prepara/' . $arg) . '">Prepara Campanha</a>
                                | 
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanha_email/' . $arg) . '">Envia e-mail</a>
                                | 
                                <a class="btn btn-secondary" href="' . base_url('index.php/main/campanha_cancela_alvo/' . $arg) . '">Excluir selecionados</a>
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
            echo '='.$chk.'<br>='.$chk2;
        }
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

}
?>
