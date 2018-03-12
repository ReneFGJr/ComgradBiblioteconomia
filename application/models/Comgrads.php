<?php
class comgrads extends CI_model {
	function about() {
		/* formulário */
		$form = 'Formulário';
		$img = base_url('img/icone/about.png');
		$tela = '
				<div class="col-md-2"><img src="' . $img . '" class="img-responsive"></div>
				<div class="col-md-8">' . $form . '</div>';
		return ($tela);
	}

	function contact() {
		$img = base_url('img/icone/contact-us.png');
		$tela = '
				<div class="col-md-2"><img src="' . $img . '" class="img-responsive"></div>
				<div class="col-md-8">Sobre</div>';
		return ($tela);
	}

	function disciplinas() {
		$cp = array();
		$cp['BIB03016'] = 'FONTES GERAIS DE INFORMAÇÃO';
		$cp['BIB03332'] = 'FUNDAMENTOS DE ORGANIZAÇÃO DA INFORMAÇÃO';
		$cp['BIB03084'] = 'NORMATIZAÇÃO DE DOCUMENTOS';
		$cp['BIB03334'] = 'DOCUMENTOS DIGITAIS';
		$cp['BIB03335'] = 'LINGUAGEM DOCUMENTÁRIA I';
		$cp['BIB03333'] = 'ORGANIZAÇÃO, CONTROLE E AVALIAÇÃO EM AMBIENTES DE INFORMAÇÃO';
		$cp['BIB03336'] = 'REPRESENTAÇÃO DESCRITIVA I';
		$cp['BIB03337'] = 'GESTÃO DE AMBIENTES EM UNIDADES DE INFORMAÇÃO';
		$cp['BIB03338'] = 'LINGUAGEM DOCUMENTÁRIA II';
		$cp['BIB03339'] = 'REPRESENTAÇÃO DESCRITIVA II';
		$cp['BIB03088'] = 'SERVIÇO DE REFERÊNCIA E INFORMAÇÃO';
		$cp['BIB03340'] = 'ESTUDO DE COMUNIDADES, PÚBLICOS E USUÁRIOS';
		$cp['BIB03085'] = 'FUNDAMENTOS DA CIÊNCIA DA INFORMAÇÃO A';
		$cp['BIB03225'] = 'GESTÃO DO CONHECIMENTO';
		$cp['BIB03079'] = 'INFORMACAO ESPECIALIZADA';
		$cp['BIB03341'] = 'LINGUAGEM DOCUMENTÁRIA III';
		$cp['BIB03343'] = 'ÉTICA EM INFORMAÇÃO';
		$cp['BIB03344'] = 'GERENCIAMENTO DA ORGANIZAÇÃO DA INFORMAÇÃO';
		$cp['BIB03342'] = 'MARKETING EM AMBIENTES DE INFORMAÇÃO';
		$cp['BIB03023'] = 'PESQUISA E DESENVOLVIMENTO DE COLEÇÕES';
		$cp['BIB03028'] = 'PLANEJAMENTO E ELABORAÇÃO DE BASES DE DADOS';
		$cp['ESTÁGIO'] = 'CURRICULAR OBRIGATÓRIO - BIB';
		$cp['BIB03345'] = 'PESQUISA EM CIÊNCIAS DA INFORMAÇÃO';
		$cp['BIB03346'] = 'SEMINÁRIO DE PRÁTICA DE ESTÁGIO';
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
		$cp['OBRIGAT'] = 'FALTA DE CRÉDITOS OBRIGATÓRIOS';
		$cp['NAOAPLICA'] = 'NÃO APLICÁVEL';
		
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
					$sx .= '<td colspan=2><hr>';
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
						case '1':
							$st = '<span style="color: red"><b>Indeferido</b></span>';
							break;
						}
					$sx .= '<td>'.$st.'</td>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td colspan=2><hr>';
					$sx .= '</tr>';
					$sx .= '<tr valign="top">';
					$sx .= '<td>Parecer da Comgrad</td>';
					$sx .= '<td>'.mst($line['pr_parecer']).'</td>';
					$sx .= '</tr>';
					
					$sx .= '<tr valign="top">';
					$sx .= '<td>Data do parecer</td>';
					$sx .= '<td>'.stodbr($line['pr_parecer_data']).'</td>';
					$sx .= '</tr>';	
					$sx .= '<tr valign="top">';
					$sx .= '<td colspan=2><hr>';
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
					echo '<hr>';
				}
			$sx .= '</table>';
			return($sx);
		}
	function enviar_email($us_email, $titulo, $texto) {

		//$config = Array('protocol' => 'smtp', 'smtp_host' => 'ssl://smtp.googlemail.com', 'smtp_port' => 465, 'smtp_user' => 'user@gmail.com', 'smtp_pass' => '', 'mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
		$config = array('mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
		$this -> load -> library('email', $config);
		$this -> email -> set_newline("\r\n");
		$this -> email -> from('comgradbib@ufrgs.br', 'Comgrad de Biblioteconomia / UFRGS');

		$list = array($us_email);
		$this -> email -> to($list);
		$this -> email -> cc(array('comgradbib@gmail.com','renefgj@gmail.com'));
		$this -> email -> subject($titulo);
		$this -> email -> message($texto);

		$this -> email -> send();
		return($us_email.' enviado<br>');
	}		
}
?>
