<?php
class events extends CI_model {
    function inport_event_incritos($a = '', $b = '') {
        $d1 = get("dd1");
        $cp = array();
        array_push($cp, array('$Q id_e:e_name:select * from events where e_status = 1 order by e_data_i desc', '', 'Evento', true, true));
        array_push($cp, array('$T80:10', '', 'Lista de inscritos', true, true));

        $form = new form;
        $sx = $form -> editar($cp, '');
        if ($form -> saved > 0) {
            $l = get("dd1");
            $evento = get("dd0");
            $l = troca($l, ';', '£');
            $l = troca($l, chr(13), ';');
            $l = troca($l, chr(10), '');
            $ln = splitx(';', $l);
            for ($r = 0; $r < count($ln); $r++) {
                $ll = $ln[$r];
                $ll = troca($ll, '£', ';');
                $ll = splitx(';', $ll . ';');
                if (strpos($ll[1], '@')) {
                    $email = $ll[1];
                    $cracha = md5($email);
                    $nome = $ll[0];
                    $sx .= '<br>' . $nome . ' (' . $email . ') ';
                    $sql = "select * from events_names where n_email = '$email' ";
                    $rlt = $this -> db -> query($sql);
                    $rlt = $rlt -> result_array();
                    if (count($rlt) == 0) {
                        $xsql = "insert into events_names
                                                    (n_nome, n_email, n_cracha)
                                                    values
                                                    ('$nome','$email','$cracha')";
                        $rlt = $this -> db -> query($xsql);
                        $rlt = $this -> db -> query($sql);
                        $rlt = $rlt -> result_array();
                        $sx .= ' <font color=green>Inserido!</font>';
                    } else {
                        $sx .= ' <font color=red>Já existe!</font>';
                    }
                    $line = $rlt[0];
                    $idu = $line['id_n'];
                    $sx .= ' <font color=red>Já existe!</font>';
                    
                    
                    $sql = "select * from events_inscritos
                                where i_evento = $evento AND i_user = $idu ";
                    $zrlt = $this->db->query($sql);
                    $zrlt = $zrlt->result_array();
                    if (count($zrlt) == 0)
                        {
                            $sql = "insert into events_inscritos
                                        (i_evento, i_user, i_status)
                                        values
                                        ($evento,$idu,1) ";
                            $erlt = $this->db->query($sql);
                            $sx .= ' <font color="green">Inscrito</font>';
                        } else {
                            $sx .= ' <font color="red">Já inscrito</font>';
                        }

                }
            }
        } else {

        }
        return ($sx);

    }

    function select($id = '') {
        if (strlen($id) > 0) {
            $_SESSION['event_id'] = $id;
            redirect(base_url('index.php/main/evento/checkin'));
        }
        $sql = "select * from events where e_status = 1 order by e_data_i";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<div class="container">' . cr();
        $sx .= '<div class="row">' . cr();
        $sx .= '<div class="col-md-12">' . cr();
        $sx .= '<h1>Eventos abertos para inscrição</h1>';
        $sx .= '<ul>' . cr();

        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];

            $sx .= '<li>';
            $sx .= '<a href="' . base_url('index.php/main/evento/select/' . $line['id_e']) . '" style="font-size: 200%;">';
            $sx .= $line['e_name'];
            $sx .= ' (';
            $sx .= stodbr($line['e_data_i']);
            if ($line['e_data_f'] > $line['e_data_i']) {
                $sx .= ' à ';
                $sx .= stodbr($line['e_data_f']);
            }
            $sx .= ')';
            $sx .= '</a>';
            $sx .= '</li>';
        }
        $sx .= '</ul>' . cr();
        $sx .= '</div>' . cr();
        $sx .= '</div>' . cr();
        return ($sx);
    }

    function valida($arg, $arg2) {
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

        $data = $this -> events -> le($arg);
        $sx = '<br><br>';
        $sx .= '<h1>Validação de declaração/certificado</h1>';
        $sx .= '<br><p>';
        $sx .= 'Nome: ' . $data['n_nome'] . '<br>';
        $sx .= 'Evento: ' . $data['e_name'] . '<br>';
        $sx .= 'Cidade: ' . $data['e_cidade'] . '<br>';
        $sx .= 'Data: ' . $data['e_data'] . '<br>';

        $sx .= '
                        <div class="alert alert-success" role="alert">
                          Documento validado com sucesso!
                        </div>
                ';
        $data['content'] = $sx;
        $this -> load -> view('content', $data);
        return ('');
    }

    function le($id) {
        $sql = "select * from events_inscritos
                            INNER JOIN events_names ON i_user = id_n 
                            INNER JOIN events ON i_evento = id_e
                            where id_i = '$id' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line);
        } else {
            $line = array();
            return ($line);
        }
    }

    function inscritos($event) {
        $sql = "select * from events_inscritos
                            INNER JOIN events_names ON id_n = i_user 
                            WHERE i_evento = $event
                            ORDER BY i_date_in desc ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $n = 0;
        $email = '';
        $sx = '<table width="100%" class="table">' . cr();
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $n++;
            $sx .= '<tr>';
            $sx .= '<td width="2%" class="text-center">';
            $sx .= ($r + 1);
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= $line['n_nome'];
            $sx .= '</td>';

            $sx .= '<td width="10%">';
            $sx .= $line['n_cracha'];
            $sx .= '</td>';

            $sx .= '<td width="15%" class="text-right">';
            $sx .= stodbr($line['i_date_in']);
            $sx .= ' ';
            $sx .= substr($line['i_date_in'], 11, 5);
            $sx .= '</td>';

            $sx .= '<td width="15%" class="text-right">';
            $sx .= stodbr($line['i_certificado']);
            $sx .= ' ';
            $sx .= substr($line['i_certificado'], 11, 5);
            $sx .= '</td>';

            $sx .= '</tr>';
            $sx .= cr();

            $email .= trim($line['n_email']) . '; ';
        }
        $sx .= '</table>';

        $sa = '<br><table width="100%" border="1"><tr><td class="text-center"><h3>' . $n . ' Presente(s)</h3></td></tr></table>';
        return ($sa . $sx . '<h4>e-mail dos inscritos</h4>' . $email);
    }

    function certificados() {
        $sx = '<br><br><br><br><br>';
        $sx .= '<div class="row">' . cr();
        $sx .= '<div class="col-md-12">' . cr();
        $sx .= '<h2>Emissão de declarações/certificados</h2>' . cr();
        $sx .= '<p>Informe o número de seu cracha, nome completo ou e-mail para emissão de sua declaração ou certificado de participação.</p>' . cr();
        $sx .= '<form method="post">' . cr();
        $sx .= '
                        <div class="input-group">
                        <input type="text" class="form-control" name="dd1" value="' . get("dd1") . '"  placeholder="Cracha, nome ou e-mail" aria-label="Cracha, nome ou e-mail">
                          <span class="input-group-btn">
                            <input type="submit" class="btn btn-danger" type="button" value="Emissão">
                          </span>
                        </div>                
                ' . cr();
        $sx .= '';
        $sx .= '</form>' . cr();
        $sx .= '</div>' . cr();
        $sx .= '</div>' . cr();

        /************************************************************/
        $n = get("dd1");
        $sql = "select * from events_inscritos
                            INNER JOIN events_names ON i_user = id_n 
                            INNER JOIN events ON i_evento = id_e
                            where n_nome = '$n' OR
                                  n_cracha = '$n' OR
                                  n_email = '$n' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx .= '<div class="row">' . cr();
        $sx .= '<div class="col-md-12">' . cr();
        if (count($rlt) == 0) {
            if (strlen($n) > 0) {
                $sx .= '
                        <br>
                        <div class="alert alert-danger" role="alert">
                          Nenhuma declaração ou certificado disponível para "<b>' . $n . '</b>".
                        </div>
                        ';
            }
        } else {
            $sx .= '<br><br>';
            $sx .= '<h2><b>' . $rlt[0]['n_nome'] . ' (' . $rlt[0]['n_cracha'] . ')</b></h2>';
            $sx .= 'Certificados / declarações disponíveis:';
        }

        $sx .= '<table class="table" width="100%">' . cr();
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $id = $line['id_i'];

            $sx .= '
                            <tr>
                            <td valign="center" style="font-size: 150%;">
                            ' . $line['e_name'] . '
                            </td>
                            <td width="20%">
                              <span class="input-group-btn">
                                <a href="' . base_url('index.php/main/evento/print/' . $id . '/' . checkpost_link($id)) . '" class="btn btn-danger" target="_new' . $line['id_i'] . '">
                                    Emitir!
                                </a>
                              </span>
                            </td>
                            </tr>                         
                        ';
            $sx .= '</div>';
        }
        $sx .= '</table>' . cr();
        $sx .= '</div>' . cr();
        $sx .= '</div>' . cr();
        return ($sx);
    }

    function create_event() {

    }

    function lista_inscritos($event) {
        $sql = "select * from events_inscritos
                            INNER JOIN events_names ON id_n = i_user 
                            where i_evento = $event
                            ORDER BY i_date_in desc ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $n = 0;
        $sx = '<table width="100%" class="table">' . cr();
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $n++;
            $sx .= '<tr>';
            $sx .= '<td width="2%" class="text-center">';
            $sx .= ($r + 1);
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= $line['n_nome'];
            $sx .= '</td>';

            $sx .= '<td width="10%">';
            $sx .= $line['n_cracha'];
            $sx .= '</td>';

            $sx .= '<td width="15%" class="text-right">';
            $sx .= stodbr($line['i_date_in']);
            $sx .= ' ';
            $sx .= substr($line['i_date_in'], 11, 5);
            $sx .= '</td>';

            $sx .= '</tr>';
            $sx .= cr();
        }
        $sx .= '</table>';

        $sa = '<br><table width="100%" border="1"><tr><td class="text-center"><h3>' . $n . ' Presente(s)</h3></td></tr></table>';
        return ($sa . $sx);
    }

    function register($event, $name, $cracha, $email = '') {
        $sql = "select * from events_names where n_nome = '$name' and n_cracha = '$cracha' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            $sqli = "insert into events_names
                                    (n_nome, n_cracha, n_email)
                                    values
                                    ('$name','$cracha','$email')
                                ";
            $rlt = $this -> db -> query($sqli);
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
        }
        $line = $rlt[0];
        $id_us = $line['id_n'];

        $sql = "select * from events_inscritos where i_evento = $event and i_user = $id_us";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        if (count($rlt) == 0) {
            $sql = "insert into events_inscritos
                                    ( i_evento, i_user, i_status)
                                    values
                                    ( $event, $id_us, 1)
                                ";
            $rlt = $this -> db -> query($sql);
            return (1);
        } else {
            $line = $rlt[0];
            $sql = "update events_inscritos set i_status = 1 
                        where id_i = " . $line['id_i'];
            $rlt = $this -> db -> query($sql);
        }
    }

    function cadastra_usuario($nome, $email, $cracha) {
        $sql = "select * from events_names where n_email = '$email' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            echo "Já existe";
            exit ;
        } else {
            $sql = "insert into events_names 
                                (n_nome, n_cracha, n_email)
                                value
                                ('$nome','$cracha','$email') ";
            $rlt = $this -> db -> query($sql);
        }
        return ('');
    }

    function acao() {
        /************************/
        if ((strlen(get("action")) > 0) and (strlen(get("dd_nome") . get("dd_cracha")) > 0)) {
            $nome = strtoupper(get("dd_nome"));
            $email = get("dd_email");
            $cracha = get("dd_cracha");
            if (strlen($cracha) < 8) { $cracha = strzero($cracha, 8);
            }
            $this -> cadastra_usuario($nome, $email, $cracha);
            if (strlen($cracha) > 0) {
                $id = $cracha;
            } else {
                $id = $nome;
            }
            redirect(base_url('index.php/main/evento/checkin/' . $id));
        }
    }

    function event_registra_checkin($id, $arg) {
        $event = $_SESSION['event_id'];

        if ((strlen($arg) > 0) and (strlen($id) == 0)) { $id = $arg;
        }

        $sql = "insert into events_login
                            (el_usca) value ('$id')";
        $rlt = $this -> db -> query($sql);

        $wh = '';
        $cracha = '';
        $name = '';
        if ($id == sonumero($id)) {
            $id = strzero($id, 8);
            $wh = " p_cracha = '$id' ";
            $cracha = $id;
        } else {
            $nn = troca($id, ' ', ';');
            $nn = splitx(';', $nn);
            for ($r = 0; $r < count($nn); $r++) {
                if ($r > 0) { $wh .= ' AND ';
                }
                $wh .= "(p_nome like '%" . $nn[$r] . "%')";
            }
            $name = $id;
        }

        $sql = "select * from person 
                            LEFT JOIN person_contato ON ct_person = id_p AND ct_tipo = 'E'
                            where $wh
                            limit 20
                            ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        if (count($rlt) == 0) {
            $sql = "select * from
                                (select n_nome as p_nome, n_cracha as p_cracha, n_email as ct_contato 
                                    from events_names ) as tabela 
                                    where $wh OR (p_email = '" . $nn[0] . "')
                                limit 20
                                ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
        }
        $sx = '';

        if (count($rlt) > 0) {
            if (count($rlt) == 1) {
                $p = array();
                $line = $rlt[0];
                $p['name'] = $line['p_nome'];
                $p['cracha'] = $line['p_cracha'];
                $p['email'] = '';

                $this -> events -> register($event, $line['p_nome'], $line['p_cracha'], $line['ct_contato']);

                $sx = '
                                <br>
                                <div class="alert alert-success" role="alert">
                                  <strong>Sucesso!</strong> <a href="#" class="alert-link">' . $p['name'] . ' registrado com sucesso!</a>
                                </div>
                                ';
            } else {
                $sx .= '<ul>';
                for ($r = 0; $r < count($rlt); $r++) {
                    $line = $rlt[$r];
                    $sx .= '<li>';
                    $sx .= '<a href="' . base_url('index.php/main/evento/checkin/' . $line['p_cracha']) . '">';
                    $sx .= $line['p_nome'];
                    $sx .= ' (' . $line['p_cracha'] . ')';
                    $sx .= '</li>';
                }
                $sx .= '<ul>';
            }
        } else {
            $sx = '
                        <br>
                        <div class="alert alert-danger" role="alert">
                          <strong>Erro!</strong> <a href="#" class="alert-link">Nenhuma ocorrencia para esse nome / cracha. (' . $id . ')</a>
                        </div>
                        
                        <br>
                        
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                              Cadastrar Visitante
                            </button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Cadastrar Visitante</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form method="post" action="' . base_url('index.php/main/evento/checkin/') . '">
                                  <div class="modal-body">
                                    
                                        <span>Nome completo</span>
                                        <input type="text" name="dd_nome" class="form-control" value="' . $name . '" style="text-transform: uppercase;">
                                        <span>Cracha</span>
                                        <input type="text" name="dd_cracha" class="form-control" value="' . $cracha . '">
                                        <span>e-mail</span>
                                        <input type="text" name="dd_email" class="form-control" style="text-transform: lowercase;">
                                    
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <input type="submit" name="action" class="btn btn-primary" value="salvar">                                    
                                  </div>
                                  </form>
                                </div>
                              </div>
                            </div>                        
                        <br>
                        ';
        }
        return ($sx);
    }

    function le_event($id) {
        $sql = "select * from events where id_e = " . round($id);
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line);
        }
        return ( array());
    }

    function event_checkin($ch) {
        $sx = '
                <br>
                <div class="alert alert-danger" role="alert">
                  <strong>Erro!</strong> <a href="#" class="alert-link">Nome ou cracha não registrado no sistema.
                </div>
                ';
        return ($sx);
    }

    function event_checkin_form($ev = 0) {
        $sx = '<form method="post">';
        $sx .= '
                  Informe o nome ou cracha
                  <div class="input-group">
                      <input id="checkin" name="checkin" type="text" class="form-control" placeholder="Informe o nome ou cracha">
                      <span class="input-group-btn">
                        <input type="submit" class="btn btn-primary" type="button" value="Check-in">
                      </span>
                    </div>
                    <script>
                        jQuery("#checkin").focus();
                    </script>
                ';
        $sx .= '</form>';
        return ($sx);
    }

}
?>
