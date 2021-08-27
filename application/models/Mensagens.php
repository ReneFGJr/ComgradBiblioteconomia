<?php
class mensagens extends CI_model {
	var $table = 'person_mensagem';
	function mensagens_total($id) {
		$sql = "select count(*) as total from " . $this -> table . " where msg_cliente_id = $id";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt[0]['total']);
	}

	function mostra_mensagens($id) {
		$sql = "select * from " . $this -> table . "
							LEFT JOIN users on id_us = msg_user 
							WHERE msg_cliente_id = $id
							ORDER BY id_msg desc
							";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<table class="table" width="100%">';
		$sx .= '<tr class="small">
							<th width="15%">data</th>
							<th width="55%">assunto</th>
							<th width="30%">responsável</th>
							<th width="5%">e-mail</th>
						</tr>
						';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$t = sonumero($line['msg_data']);
			$sx .= '<tr>';
			$sx .= '<td><nobr>' . stodbr($line['msg_data']) . ' ' . substr($t, 8, 2) . ':' . substr($t, 10, 2) . '</nobr></td>';
			$sx .= '<td><b>' . ($line['msg_subject']) . '</b></td>';
			$sx .= '<td>' . ($line['us_nome']) . '</td>';
			if ($line['msg_tipo'] == 1)
				{
					$sx .= '<td align="center">SIM</td>';
				} else {
					$sx .= '<td align="center">NÃO</td>';
				}
			$sx .= '</tr>' . cr();

			if (strlen($line['msg_text']) > 0) {
				$sx .= '<tr><td></td>';
				$sx .= '<td colspan=3><i>' . mst($line['msg_text']) . '</i></td>';
				$sx .= '</tr>';
			}
		}
		if (count($rlt) == 0) {
			$sx .= '<tr class="middle"><td colspan=10><font color="red">sem mensagens</td></tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function nova_mensagem($id) {
		$sx = '<button type="button" class="btn btn-default" aria-label="Left Align" onclick="newwin(\'' . base_url('index.php/main/cliente_mensagem_edit/0/' . $id) . '\');">';
		$sx .= 'nova mensagem';
		$sx .= '</button>';
        /*
    		$sx .= '&nbsp;';
    		$sx .= '<button type="button" class="btn btn-default" aria-label="Left Align" onclick="newwin(\'' . base_url('index.php/main/cliente_mensagem_aviso_vencimento/0/' . $id) . '\');">';
    		$sx .= 'aviso de vencimento';
    		$sx .= '</button>';
    		$sx .= '&nbsp;';
    		$sx .= '<button type="button" class="btn btn-default" aria-label="Left Align" onclick="newwin(\'' . base_url('index.php/main/cliente_mensagem_vencidas/0/' . $id) . '\');">';
    		$sx .= 'aviso de vencidas';
    		$sx .= '</button>';	
         * 
         */	
		return ($sx);
	}

	function cp($cli = 0) {
		$cp = array();
		if (!isset($_SESSION['id']))
			{
				echo "Não logado";
				exit;
			}
		$id_user = $_SESSION['id'];
		array_push($cp, array('$H8', 'id_msg', '', False, True));
		array_push($cp, array('$HV', 'msg_cliente_id', $cli, False, True));
		array_push($cp, array('$HV', 'msg_user', $id_user, True, True));
		array_push($cp, array('$S80', 'msg_subject', msg('msg_subject'), True, True));
		array_push($cp, array('$T80:5', 'msg_text', msg('msg_content'), True, True));
		array_push($cp, array('$O 0:NÃO&1:SIM', 'msg_tipo', 'Enviar e-mail ao cliente', True, True));
		return ($cp);
	}

}
