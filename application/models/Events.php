<?php
class events extends CI_model
    {
        function create_event()
            {
                
            }
            
        function lista_inscritos($event)
            {
                $sql = "select * from events_inscritos
                            INNER JOIN events_names ON id_n = i_user 
                            ORDER BY i_date_in desc ";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                $n = 0;
                $sx = '<table width="100%" class="table">'.cr();
                for ($r=0;$r < count($rlt);$r++)
                    {
                        $line = $rlt[$r];
                        $n++;
                        $sx .= '<tr>';
                        $sx .= '<td width="2%" class="text-center">';
                        $sx .= ($r+1);
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
                        $sx .= substr($line['i_date_in'],11,5);
                        $sx .= '</td>';                        

                        $sx .= '</tr>';
                        $sx .= cr();
                    }
                $sx .= '</table>';
                
                $sa = '<br><table width="100%" border="1"><tr><td class="text-center"><h3>'.$n.' Presente(s)</h3></td></tr></table>';
                return($sa.$sx);
            }
            
        function register($event,$name,$cracha,$email='')
            {
                $sql = "select * from events_names where n_nome = '$name' and n_cracha = '$cracha' ";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                if (count($rlt)==0)
                    {
                        $sqli  = "insert into events_names
                                    (n_nome, n_cracha, n_email)
                                    values
                                    ('$name','$cracha','$email')
                                ";
                        $rlt = $this->db->query($sqli);
                        $rlt = $this->db->query($sql);
                        $rlt = $rlt->result_array();
                    }
                $line = $rlt[0];
                $id_us = $line['id_n'];
                
                $sql = "select * from events_inscritos where i_evento = $event and i_user = $id_us";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                
                if (count($rlt) == 0)
                    {
                        $sql = "insert into events_inscritos
                                    ( i_evento, i_user, i_status)
                                    values
                                    ( $event, $id_us, 1)
                                ";
                        $rlt = $this->db->query($sql);
                        return(1);
                    }                
            }
            
        function cadastra_usuario($nome,$email,$cracha)
            {
                $sql = "select * from events_names where n_email = '$email' ";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                if (count($rlt) > 0)
                    {
                        echo "Já existe";
                        exit;
                    } else {
                        $sql = "insert into events_names 
                                (n_nome, n_cracha, n_email)
                                value
                                ('$nome','$cracha','$email') ";
                        $rlt = $this->db->query($sql);
                    }
               return('');
            }
            
        function acao()
            {
                /************************/
                if ((strlen(get("action")) > 0) and (strlen(get("dd_nome").get("dd_cracha")) > 0))
                    {
                        $nome = strtoupper(get("dd_nome"));
                        $email = get("dd_email");
                        $cracha = get("dd_cracha");
                        if (strlen($cracha) < 8) { $cracha = strzero($cracha,8); }
                        $this->cadastra_usuario($nome,$email,$cracha);
                        if (strlen($cracha) > 0)
                            {
                                $id = $cracha;
                            } else {
                                $id = $nome;
                            }
                         redirect(base_url('index.php/main/evento/checkin/'.$id));
                    }                
            }
            
        function event_registra_checkin($id,$arg)
            {
                $event = 1;

                
                if ((strlen($arg) > 0) and (strlen($id) == 0)) { $id = $arg; }
                
                $sql = "insert into events_login
                            (el_usca) value ('$id')";
                $rlt = $this->db->query($sql);
                
                $wh = '';
                $cracha = '';
                $name = '';
                if ($id == sonumero($id))
                    {
                        $id = strzero($id,8);
                        $wh = " p_cracha = '$id' ";
                        $cracha = $id;
                    } else {
                        $nn = troca($id,' ',';');
                        $nn = splitx(';',$nn);                        
                        for ($r = 0;$r < count($nn);$r++)
                            {
                                if ($r > 0) { $wh .= ' AND '; }
                                $wh .= "(p_nome like '%".$nn[$r]."%')";
                            }
                        $name = $id;
                    }

                $sql = "select * from person 
                            LEFT JOIN person_contato ON ct_person = id_p AND ct_tipo = 'E'
                            where $wh
                            limit 20
                            ";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                
                if (count($rlt) == 0)
                    {
                    $sql = "select * from
                                (select n_nome as p_nome, n_cracha as p_cracha 
                                    from events_names ) as tabela 
                                    where $wh
                                limit 20
                                ";
                    $rlt = $this->db->query($sql);
                    $rlt = $rlt->result_array();                                                        
                    }
                $sx = '';
                
                if (count($rlt) > 0)
                    {
                        if (count($rlt) == 1)
                            {
                                $p = array();
                                $line = $rlt[0];
                                $p['name'] = $line['p_nome'];
                                $p['cracha'] = $line['p_cracha'];
                                $p['email'] = '';
                                
                                $this->events->register($event,$line['p_nome'],$line['p_cracha'],$line['ct_contato']);
                                
                                $sx = '
                                <br>
                                <div class="alert alert-success" role="alert">
                                  <strong>Sucesso!</strong> <a href="#" class="alert-link">'.$p['name'].' registrado com sucesso!</a>
                                </div>
                                ';                                 
                            } else {
                                $sx .= '<ul>';
                                for ($r=0;$r < count($rlt);$r++)
                                    {
                                        $line = $rlt[$r];
                                        $sx .= '<li>';
                                        $sx .= '<a href="'.base_url('index.php/main/evento/checkin/'.$line['p_cracha']).'">';
                                        $sx .= $line['p_nome'];
                                        $sx .= ' ('.$line['p_cracha'].')';
                                        $sx .= '</li>';
                                    }
                                $sx .= '<ul>';
                            }
                    } else {
                        $sx = '
                        <br>
                        <div class="alert alert-danger" role="alert">
                          <strong>Erro!</strong> <a href="#" class="alert-link">Nenhuma ocorrencia para esse nome / cracha. ('.$id.')</a>
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
                                  <form method="post" action="'.base_url('index.php/main/evento/checkin/').'">
                                  <div class="modal-body">
                                    
                                        <span>Nome completo</span>
                                        <input type="text" name="dd_nome" class="form-control" value="'.$name.'" style="text-transform: uppercase;">
                                        <span>Cracha</span>
                                        <input type="text" name="dd_cracha" class="form-control" value="'.$cracha.'">
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
                return($sx);
            } 
            
        function le_event($id)
            {
                $sql = "select * from events where id_e = ".round($id);
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                if (count($rlt) > 0)
                    {
                        $line = $rlt[0];
                        return($line);
                    }
                return(array());
            }
        function event_checkin($ch)
            {
                $sx = '
                <br>
                <div class="alert alert-danger" role="alert">
                  <strong>Erro!</strong> <a href="#" class="alert-link">Nome ou cracha não registrado no sistema.
                </div>
                ';                
                return($sx);
            }  
        function event_checkin_form()
            {
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
                return($sx);
            }   
    }
?>
