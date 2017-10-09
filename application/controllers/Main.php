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
        $this -> load -> helper('url');
        $this -> load -> library('session');
        date_default_timezone_set('America/Sao_Paulo');
        /* Security */
        //		$this -> security();
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

        $sx = '<hr>'.$this -> mensagens -> mostra_mensagens($id);
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

    function relatorio() {
        $this -> load -> model('comgrads');
        $this -> load -> model('pags');
        $this -> cab();

        $data['content'] = $this -> pags -> rel_alunos_matriculados();
        $data['title'] = 'lista';
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
