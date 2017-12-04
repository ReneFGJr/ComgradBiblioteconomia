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
            
        function event_registra_checkin($id,$arg)
            {
                $event = 1;
                if ((strlen($arg) > 0) and (strlen($id) == 0)) { $id = $arg; }
                $sql = "select * from person 
                            LEFT JOIN person_contato ON ct_person = id_p AND ct_tipo = 'E'
                            where p_nome like '%".$id."%' or p_cracha like '%".sonumero($id)."%'
                            limit 20
                            ";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
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
                                  <strong>Sucesso!</strong> <a href="#" class="alert-link">'.$p['name'].' registrado com sucesso!
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
                          <strong>Erro!</strong> <a href="#" class="alert-link">Nenhuma ocorrencia para esse nome / cracha.
                        </div>
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
