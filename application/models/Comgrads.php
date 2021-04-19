<?php
class comgrads extends CI_model {
	function about() {
		/* formulário */
		$form = 'Formulário';
		$img = base_url('img/icone/about.png');
		$tela = '
				<div class="col-md-2"><img src="' . $img . '"  style="height: 100px;"></div>
				<div class="col-md-8">' . $form . '</div>';
		return ($tela);
	}

	function le_curso($id)
		{
			$sql = "select * from person_curso 
						where id_pc = ".round($id);
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$line = $rlt[0];
			return($line);
		}
	function cursos($id='')
		{
			if (strlen($id) > 0)
				{
					$_SESSION['CURSO'] = $id;	
				} else {
					$id = CURSO;
				}
			
			$sql = "select * from person_curso order by pc_nome";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();

			$dt = $this->le_curso($id);
			$sx = '<h3>Curso selecionado: '.$dt['pc_nome'].'</h3>';

			$sx .= '<table class="table">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$link = '<a href="'.base_url(PATH.'cursos/'.$line['id_pc']).'">';
					$linka = '</a>';
					$sx .= '<tr>';
					$sx .= '<td>'.$link.$line['pc_nome'].$linka.'</td>';
					$sx .= '</tr>';
				}
			$sx .= '</table>';
			return($sx);
		}
	function painel()
		{
			$data = $this->le_curso(CURSO);
			$sx = '<div class="row" style="background-color: #F0F0F0; padding: 30px 10px;">';
			$sx .= '<div class="col-md-12">';
			$sx .= '<h3>Painel '.$data['pc_nome'].' - UFRGS</h3>';
			$sx .= '</div>';

			$sx .= '<div class="col-md-2">';
			$sx .= $this->painel_grid('Duração do curso','4 anos','1');
			$sx .= '</div>';

			$sx .= '<div class="col-md-2">';
			$sx .= $this->painel_grid('Período do curso','Diurno/Manhã','1');
			$sx .= '</div>';

			$sx .= '<div class="col-md-2">';
			$sx .= $this->painel_grid('Total de estudantes',$this->alunos_ativos());
			$sx .= '</div>';

			$sx .= '<div class="col-md-2">';
			$sx .= $this->painel_grid('Idade média dos estudantes',$this->alunos_idade_ativos(),'2');
			$sx .= '</div>';


			$sx .= '<div class="col-md-2">';
			$sx .= $this->painel_grid('Tempo de Integralização do curso','-','1');
			$sx .= '</div>';

			$sx .= '</div>';
			return($sx);
		}
	function alunos_idade_ativos()
		{
			$sql = "select p_nasc 
						from person_indicadores 
						INNER JOIN person ON id_p = i_person
						where i_ano = '2020/2' 
						AND i_curso = ".CURSO;
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$tot = 0;
			$dias = 0;
			for ($r=0;$r < count($rlt); $r++)
				{
					$line = $rlt[$r];
					$ano = substr($line['p_nasc'],0,4);
					$mes = substr($line['p_nasc'],5,2);
					$dia = substr($line['p_nasc'],8,2);
					$data_inicio = new DateTime($line['p_nasc']);
					$data_fim = new DateTime(date("Y-m-d"));
					$dateInterval = $data_inicio->diff($data_fim);
    				$di = $dateInterval->days;
					$dias = $dias + $di;
					$tot++;
				}
			$media = round(10*($dias/$tot)/365)/10;
			return($media);
		}

	function alunos_ativos()
		{
			$sql = "select count(*) as total 
						from person_indicadores 
						where i_ano = '2020/2' 
						AND i_curso = ".CURSO;
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$total = $rlt[0]['total'];
			return($total);
		}
	function painel_grid($txt,$ind,$t=1)
		{
			switch($t)
				{
					case '2':
					$cor = '#A00000';
					break;

					default:
					$cor = '#00A000';
				}
			
			$sx = '';
			$sx .= '<div style="background-color: #fff; border-radius: 4px; border-left: 4px '.$cor.' solid; padding: 5px 5px 5px 10px;">';
			$sx .= '<span style="color: '.$cor.'">'.$txt.'</span>';
			$sx .= '<br>';
			$sx .= '<h2>'.$ind.'</h2>';
			$sx .= '</div>';
			return($sx);
		}

	function contact() {
		$img = base_url('img/icone/contact-us.png');
		$tela = '
				<div class="col-md-2"><img src="' . $img . '" style="height: 100px;"></div>
				<div class="col-md-8">comgradbib@ufrgs.br</div>';
		return ($tela);
	}

	function disciplinas() {
		$cp = array();
        $cp['BIB03016'] = 'FONTES GERAIS DE INFORMAÇÃO';
        $cp['BIB03023'] = 'PESQUISA E DESENVOLVIMENTO DE COLEÇÕES';
        $cp['BIB03028'] = 'PLANEJAMENTO E ELABORAÇÃO DE BASES DE DADOS';
        $cp['BIB03079'] = 'INFORMACAO ESPECIALIZADA';
        $cp['BIB03084'] = 'NORMATIZAÇÃO DE DOCUMENTOS';
        $cp['BIB03085'] = 'FUNDAMENTOS DA CIÊNCIA DA INFORMAÇÃO A';
        $cp['BIB03088'] = 'SERVIÇO DE REFERÊNCIA E INFORMAÇÃO';
        $cp['BIB03225'] = 'GESTÃO DO CONHECIMENTO';
        $cp['BIB03332'] = 'FUNDAMENTOS DE ORGANIZAÇÃO DA INFORMAÇÃO';
        $cp['BIB03333'] = 'ORGANIZAÇÃO, CONTROLE E AVALIAÇÃO EM AMBIENTES DE INFORMAÇÃO';
        $cp['BIB03334'] = 'DOCUMENTOS DIGITAIS';
        $cp['BIB03335'] = 'LINGUAGEM DOCUMENTÁRIA I';
        $cp['BIB03336'] = 'REPRESENTAÇÃO DESCRITIVA I';
        $cp['BIB03337'] = 'GESTÃO DE AMBIENTES EM UNIDADES DE INFORMAÇÃO';
        $cp['BIB03338'] = 'LINGUAGEM DOCUMENTÁRIA II';
        $cp['BIB03339'] = 'REPRESENTAÇÃO DESCRITIVA II';
        $cp['BIB03340'] = 'ESTUDO DE COMUNIDADES, PÚBLICOS E USUÁRIOS';
        $cp['BIB03341'] = 'LINGUAGEM DOCUMENTÁRIA III';
        $cp['BIB03342'] = 'MARKETING EM AMBIENTES DE INFORMAÇÃO';
        $cp['BIB03343'] = 'ÉTICA EM INFORMAÇÃO';
        $cp['BIB03345'] = 'PESQUISA EM CIÊNCIAS DA INFORMAÇÃO';
        $cp['BIB03346'] = 'SEMINÁRIO DE PRÁTICA DE ESTÁGIO (Retirado do Currículo)';
        $cp['BIB03369'] = 'SEMINÁRIO DE PRÁTICA DE ESTÁGIO I';
        $cp['BIB03370'] = 'SEMINÁRIO DE PRÁTICA DE ESTÁGIO II';
        $cp['BIB03361'] = 'INTRODUÇÃO À ORGANIZAÇÃO DA INFORMAÇÃO';
        $cp['MAT02280'] = 'ESTATÍSTICA BÁSICA I';
        $cp['ELETI01'] = 'ELETIVA 1 CRÉDITOS';
        $cp['ELETI02'] = 'ELETIVA 2 CRÉDITOS';
        $cp['ELETI03'] = 'ELETIVA 3 CRÉDITOS';
        $cp['ELETI04'] = 'ELETIVA 4 CRÉDITOS';
        $cp['ELETI05'] = 'ELETIVA 5 CRÉDITOS';
        $cp['ELETI06'] = 'ELETIVA 6 CRÉDITOS';
        $cp['ELETI07'] = 'ELETIVA 7 CRÉDITOS';
        $cp['ELETI08'] = 'ELETIVA 8 CRÉDITOS';
        $cp['ELETI09'] = 'ELETIVA 9 CRÉDITOS';
        $cp['ELETI10'] = 'ELETIVA 10 CRÉDITOS';
        
        $cp['ESTÁGIO1'] = 'CURRICULAR OBRIGATÓRIO I - BIB';
        $cp['ESTÁGIO2'] = 'CURRICULAR OBRIGATÓRIO II - BIB';
        $cp['ESTÁGIO'] = 'CURRICULAR OBRIGATÓRIO - BIB (Retirado do Currículo)';
        $cp['NAOAPLICA'] = 'NÃO APLICÁVEL';
        $cp['OBRIGAT'] = 'FALTA DE CRÉDITOS OBRIGATÓRIOS';
        $cp['BIB03344'] = 'GERENCIAMENTO DA ORGANIZAÇÃO DA INFORMAÇÃO';

		
		$cp['TCC'] = 'TRABALHO DE CONCLUSÃO DE CURSO - BIB';
		return ($cp);
	}

	function prerequisito_form() {
		$form = new form;
		$cp = array();
		array_push($cp, array('$H8', '', '', false, false));

		array_push($cp, array('$A1', '', 'Identificação do estudante', false, false));
		array_push($cp, array('$S30', 'pr_estudante', 'Código Cracha', true, TRUE));
		array_push($cp, array('$S100', 'pr_email', 'e-mail', false, TRUE));
		$dados = '';
		$dd2 = get('dd2');
		if ($dd2 != '') {
			$dados = $dd2;
			$dd2 = strzero($dd2, 8);
			$data = $this->le($this -> le_cracha($dd2));
			$dados = '<b>'.$data['p_nome'].'</b>';
			$cta = $data['contato'];
			for ($r=0;$r < count($cta);$r++)
				{
					$ct = $cta[$r];
					$tp = $ct['ct_tipo'];
					if ($tp == 'T') { $tp = 'Telefone: '; }
					if ($tp == 'E') { $tp = 'E-mail: '; }
					$dados .= '<br>'.$tp;
					$dados .= $ct['ct_contato'];
				}
		}
		array_push($cp, array('$M', '', $dados, false, false));

		array_push($cp, array('$A1', '', 'Solicitação', false, false));

		$op = '';
		$d = $this -> disciplinas();
		foreach ($d as $cod => $nome) {
			if (strlen($op) > 0) {
				$op .= '&';
			}
			$op .= $cod . ':' . $cod . ' - ' . $nome;
		}
		array_push($cp, array('$O ' . $op, 'pr_disciplina_1', 'Disciplina a ter o pré-requisito quebrado:', true, TRUE));


		array_push($cp, array('$O ' . $op, 'pr_disciplina_2', 'Disciplina a ser cursada com a quebra do pré-requisito:', true, TRUE));

		array_push($cp, array('$A1', '', 'Justificativa', false, false));
		array_push($cp, array('$T80:6', 'pr_justificativa', 'Descreva a justificativa', true, TRUE));
		
		array_push($cp, array('$O 1:SIM', '', 'Confirma solicitação de quebra de pré-requisito?', true, TRUE));
		array_push($cp, array('$B', '', 'Enviar solicitação >>>', false, false));
		$tela = $form -> editar($cp, 'prerequisito');
		if ($form->saved > 0)
			{
				$tela = 'Solicitação realizada com sucesso!';
			}
		return ($tela);
	}

	function le_cracha($id) {
		$id = strzero(sonumero($id), 8);
		$sql = "select * from person where p_cracha = '$id' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_p']);
		} else {
			return (0);
		}
	}

	function le($id) {
		$sql = "select * from person where id_p = $id";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			/* enderecos */
			$sql = "select * from person_endereco where ed_person = $id";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['endereco'] = $rlt;

			/* contatos */
			$sql = "select * from person_contato where ct_person = $id";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['contato'] = $rlt;

			/* graduacao */
			$sql = "select * from person_graduacao  
                        inner join person_curso on id_pc = g_curso_1 
                        where g_person = $id";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['graduacao'] = $rlt;

			/* person_indicadores */
			$sql = "select * from person_indicadores where i_person = $id order by i_ano desc ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['indicadores'] = $rlt;

			/* person_acompanhamento */
			$sql = "select * from person_rod
                        INNER JOIN person_acompanhamento_tipo ON id_pat = rod_tipo 
                        where rod_person = $id 
                        order by rod_created desc ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['acompanhamento'] = $rlt;
			return ($line);
		}
		return ( array());
	}
	function prerequisito_nrs($nr='')
		{
			$disc = $this->disciplinas();
			$tabela = 'prerequisito';
			$sql = "select * from ".$tabela."
						LEFT JOIN person ON pr_estudante = p_cracha
						where id_pr = ".round($nr);

			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '<table class="table" width="640">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$link = '';
					$sx .= '<tr valign="top">';
					$sx .= '<td>Requerimento</td>';
					$sx .= '<td>'.$link.strzero($line['id_pr'],5).'/'.substr($line['pr_data'],2,2).'</a>'.'</td>';
					$sx .= '</tr>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td>Data e Hora</td>';
					$sx .= '<td>'.stodbr($line['pr_data']).' ';
					$sx .= ''.substr($line['pr_data'],11,5).'</td>';
					$sx .= '</tr>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td>Estudante</td>';
					$sx .= '<td>'.$line['p_nome'].'<br>'.$line['pr_email'].'</td>';
					$sx .= '</tr>';

					$sx .= '<tr valign="top">';
					$sx .= '<td>Disciplina a ser quebrada</td>';
					$sx .= '<td>'.$line['pr_disciplina_1'].' - '.$disc[$line['pr_disciplina_1']].'</td>';
					$sx .= '</tr>';

					$sx .= '<tr valign="top">';
					$sx .= '<td>Disciplina a ser cursada concomitante</td>';
					$sx .= '<td>'.$line['pr_disciplina_2'].' - '.$disc[$line['pr_disciplina_2']].'</td>';
					$sx .= '</tr>';

					$sx .= '<tr valign="top">';
					$sx .= '<td>Justificativa</td>';
					$sx .= '<td>'.mst($line['pr_justificativa']).'</td>';
					$sx .= '</tr>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td>Situação</td>';
					$st = $line['pr_status'];
					switch ($st)
						{
						case '1':
							$st = '<span style="color: green"><b>Em análise</b></span>';
							break;
						case '2':
							$st = '<span style="color: blue"><b>Deferido</b></span>';
							break;
						case '3':
							$st = '<span style="color: red"><b>Indeferido</b></span>';
							break;
						}
					$sx .= '<td>'.$st.'</td>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td>Parecer da Comgrad</td>';
					$sx .= '<td>'.mst($line['pr_parecer']).'</td>';
					$sx .= '</tr>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td>Data do parecer</td>';
					$sx .= '<td>'.stodbr($line['pr_parecer_data']).'</td>';
					$sx .= '</tr>';	
				}
			$sx .= '</table>';
			return($sx);
		}
	function avaliacao($nr)
		{
				$tabela = 'prerequisito';
					$sx = '';
					$form = new form;
					$form->id = $nr;
					$cp = array();
					array_push($cp,array('$H8','id_pr','',false,true));
					array_push($cp,array('$T80:6','pr_parecer','Parecer',true,true));
					array_push($cp,array('$O 1:Aberto&2:Deferido&3:Indeferido&9:Cancelado&5:Aguardando informações','pr_status','Situação',true,true));
					array_push($cp,array('$HV','pr_parecer_data',date("Y-m-d"),true,true));
					array_push($cp,array('$C','','Enviar e-mail para o estudante',false,true));
					array_push($cp,array('$B8','','Finalizar parecer',false,true));
					$tela = $form->editar($cp,$tabela);
					
					if ($form->saved > 0)
						{
							$tabela = 'prerequisito';
							$sql = "select * from ".$tabela."
										LEFT JOIN person ON pr_estudante = p_cracha
										where id_pr = ".round($nr);							
							$rlt = $this->db->query($sql);
							$rlt = $rlt->result_array();
							
							$estu = $rlt[0]['pr_estudante'];
							$data = $this->le($this->le_cracha($estu));
							$contato = $data['contato'];
														
							for ($r=0;$r < count($contato);$r++)
								{
									$cc = $contato[$r];
									if (($cc['ct_tipo']=='E') and (strlen(get("dd4")) > 0))
										{	
										$us_email = trim($cc['ct_contato']);
										$titulo = '[COMGRADBIB] - Solicitação de Quebra de Pré-Requisito '.strzero($rlt[0]['id_pr'],7);
										$texto = '<h1>Solicitação de Quebra de Pré-Requisito</h1>';
										$texto .= $this->prerequisito_nrs($nr);
										$texto .= 'Em caso de dúvida consulta a COMGRAD/BIB por esse e-mail';
										$sx .= 'Email enviado para '.$us_email.'<br>';
										$this->enviar_email($us_email, $titulo, $texto);
										}
								}
						}
					$sx .= $tela;
					return($sx);			
		}
	function prerequisito_analise()
		{
			$tabela = 'prerequisito';
			$sql = "select * from ".$tabela."
						LEFT JOIN person ON pr_estudante = p_cracha";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '<table class="table" width="100%">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$link = '<a href="'.base_url('index.php/main/prerequisito_nr/'.$line['id_pr']).'">';
					$sx .= '<tr valign="top">';
					$sx .= '<td>'.$link.strzero($line['id_pr'],5).'/'.substr($line['pr_data'],2,2).'</a>'.'</td>';
					$sx .= '<td>'.stodbr($line['pr_data']).'</td>';
					$sx .= '<td>'.substr($line['pr_data'],11,5).'</td>';
					$sx .= '<td>'.$line['p_nome'].'</td>';
					$sx .= '</tr>';
				}
			$sx .= '</table>';
			return($sx);
		}

	function comunicacao()
		{
			$txt = '$p_nome ';
			$txt .= '$p_cracha ';
			$txt .= '$p_cpf ';

			$cp = array();
			$form = new form;
			array_push($cp,array('$H8','','',false,false));
			array_push($cp,array('$S100','','Subject',True,True));
			array_push($cp,array('$T80:20','','Texto',True,True));
			array_push($cp,array('$S100','','Teste to',False,True));
			$sx = $form->editar($cp,'');
			
			if ($form->saved > 0)
				{
					
					$dd3 = get("dd3");					
					if (strlen($dd3) == 0)
					{
					$sql = "select * 
							from person 
							INNER JOIN person_indicadores ON i_person = id_p
							INNER JOIN person_contato ON id_p = ct_person
							WHERE ct_contato like '%@%' and i_curso = ".CURSO;
					} else {
						$sql = "select '9000000' as p_cracha, 
										'FULANO DA SILVA SÓ' as p_nome, 
										'".$dd3."' as ct_contato, 
										'CPF' as p_cpf ";
					}
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$titulo = get("dd1");
					$texto = get("dd2");
					//$texto = troca(get("dd2"),chr(13),'<br>');
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$tt = troca($texto,'$p_nome',$line['p_nome']);
							$tt = troca($tt,'$p_cracha',strzero($line['p_cracha'],8));
							$tt = troca($tt,'$p_cpf',$line['p_cpf']);
							$us_email = $line['ct_contato'];
							$sx .= $line['p_nome'].' => ';
							$sx .= $this->enviar_email($us_email, '[BIBEAD] '.$titulo, $tt);							
							$sx .= '<br>';
						}
				}
			return($sx);
		}
	function enviar_email($us_email, $titulo, $texto) {

		//$config = Array('protocol' => 'smtp', 'smtp_host' => 'ssl://smtp.googlemail.com', 'smtp_port' => 465, 'smtp_user' => 'user@gmail.com', 'smtp_pass' => '', 'mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
		$config = array('mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
		$this -> load -> library('email', $config);
		$this -> email -> set_newline("\r\n");
		$this -> email -> from('bibead@ufrgs.br', 'Comgrad de Biblioteconomia EAD/UFRGS');

		$list = array($us_email);
		$this -> email -> to($list);
		$this -> email -> cc(array('bibead.ufrgs@gmail.com'));
		$this -> email -> subject($titulo);
		$this -> email -> message($texto);

		$this -> email -> send();
		return($us_email.' enviado<br>');
	}

	function contact_ed($id)
		{
			$cp = array();
			array_push($cp,array('$H8','id_ct','',false,false));
			array_push($cp,array('$S100','ct_contato','ct_contato',true,true));
			$form = new form;
			$form->id = $id;
			$sx = $form->editar($cp,'person_contato');
			return($sx);
		}
    
    function rel_email()
        {
        $sql = "select p_nome, ct_contato, p_cracha
                        from person_contato
                        INNER JOIN person ON id_p = ct_person
                        INNER JOIN person_indicadores ON i_person = id_p
                        WHERE ct_contato like '%@%' and i_curso = ".CURSO."
                        group by p_nome, ct_contato, p_cracha
                        order by p_nome
                ";
        $rlt = $this->db->query($sql);
        $rlt = $rlt->result_array();
        $tot = 0;
        $totg = 0;
        $totc = 0;
        $xcd = '';
        $sx = '';
        $email = '';
        for ($r=0;$r < count($rlt);$r++)
            {
                $line = $rlt[$r];
                $cd = $line['p_nome'];
				$sx .= $cd;
				$sx .= ' - ';
				$sx .= lowercase($line['p_cracha']);
				$sx .= ' - ';
                $sx .= lowercase($line['ct_contato']);
				$sx .= '<br>';
                
                $tot++;
                $email .= lowercase($line['ct_contato']).'; ';
            }
            $sx .= '<hr>'.$email;
            $sx .= '<hr>Total: '.$tot;
        return($sx);   
        }
    
    function rel_bairros()
    {
		$this->pags->check_address();
        $sql = "select ed_cidade, ed_bairro, count(*) as total 
                        from person_indicadores
						INNER JOIN person_endereco ON i_person = ed_person
						where i_ano = '2020/2' and i_curso = ".CURSO."
                        group by ed_cidade, ed_bairro 
                        order by ed_cidade, ed_bairro";
       $sql = "select ed_cidade, ed_estado, '' as ed_bairro, count(*) as total 
                        from person_indicadores
						INNER JOIN person_endereco ON i_person = ed_person
						where i_ano = '2020/2' and i_curso = ".CURSO."
                        group by ed_cidade, ed_estado 
                        order by ed_estado, ed_cidade";						
        $rlt = $this->db->query($sql);
        $rlt = $rlt->result_array();
        $sx = '<table width="100%" class="table2">';
        $tot = 0;
        $totg = 0;
        $totc = 0;
        $xcd = '';
        for ($r=0;$r < count($rlt);$r++)
            {
                $line = $rlt[$r];
                $cd = $line['ed_cidade'];
                
                if ($xcd != $cd)
                    {
                        if ($totc > 0)
                            {
                                //$sx .= '<tr><td colspan=3 align="right"><b>total cidade '.$totc.'</td></tr>';        
                            }                        
                        $xcd = $cd;
                        $totc = 0;
                    }
                $sx .= '<tr style="border-top: 1px solid #000000">';
                $sx .= '<td>';
                $sx .= $line['ed_cidade'].', '.$line['ed_estado'];
                $sx .= '</td>';
                $sx .= '<td>';
                $sx .= $line['ed_bairro'];
                $sx .= '</td>';
                $sx .= '<td align="right">';
                $sx .= $line['total'];
                $sx .= '</td>';           
                $sx .= '</tr>';
                $tot = $tot + $line['total'];
                $totg = $totg + $line['total'];
                $totc = $totc + $line['total'];
            }
		$sx .= '<tr><td colspan=6>TOTAL: '.$tot.'</td></tr>';
        $sx .= '</table>';
        return($sx);
    }		
}
?>
