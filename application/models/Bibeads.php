<?php 

class Bibeads extends CI_Model
    {
        function tutor_le($id)
            {
                $sql = "select * from tutores 
                            where tt_ativo = 1 
                            and id_tt = $id
                            order by tt_nome";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                return($rlt[0]);
            }

        function tutor($id)
            {
                $sx = '';
                $dt = $this->tutor_le($id);
                $sx .= '<div class="container">';
                $sx .= '<div class="row">';
                $sx .= '<div class="col-md-12">';
                $sx .= '<h3>'.$dt['tt_nome'].'</h3>';
                $sx .= '</div>';
                $sx .= '</div>';
                $sx .= '</div>';
                return($sx);
            }

        function le_tutor($id)
            {
                $sql = "select * from tutores where id_tt = ".$id;
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                $line = $rlt[0];
                return($line);
            }

        function tutor_muda($ac,$id,$vlr,$conf)
            {
                $dt = $this->pags->le($id);
                
                /* Enviar e-mail */
                $txt = '';
                $nome = $dt['p_nome'];
                $txt .= 'Prezado aluno(a) '.$nome;
                $txt .= '<br>';
                $txt .= '<br>';
                $txt .= 'Por motivos operacionais, houve a necessidade de troca de seu tutor.<br>
                <br>A partir de hoje, o(a) <b>$TUTOR</b> será o responsável pelo seu acompanhamento no curso de Biblioteconomia Ead.<br>
                <br>Caso tenha dúvida, você pode entrar em contato com seu tutor pelo e-mail <b>$EMAIL_TUTOR</b>.<br>
                <br>
                Em caso de dúvida, pode entrar em contato com a coordenação do curso em bibead@ufrgs.br<br>
                <br>                
                <hr>
                Prezado tutor, favor entrar em contato com o aluno e apresentar-se.
                <br>
                <br>
                ** ESTE E-MAIL É ENVIADO AUTOMATICAMENTE
                ';

                if (isset($_POST['dd1']))
                    {
                        $idt = $_POST['dd1'];
                        $sql = "update person set p_tutor = $idt 
                                    where p_tutor = ".$dt['id_tt']." and id_p = ".$id;
                        //$this->db->query($sql);

                        $dtt = $this->le_tutor($idt);
                        $nome2 = $dtt['tt_nome'];

                        $msg = 'Troca de tutor';
                        $mmm = 'A tutora '.$dt['tt_nome'].' foi substituída por '.$nome2;
                        $sql = "insert into person_mensagem
                                (msg_subject, msg_text, msg_cliente_id)
                                values
                                ('$msg','$mmm',$id)";
                        //$this->db->query($sql);
                        //echo '<script>wclose();</script>';


                        echo '<pre>';
                        print_r($dtt);
                        echo '</pre>';                        

                        for ($r=0;$r < count($dt['contato']);$r++)
                        {
                            $tp = $dt['contato'][$r]['ct_tipo'];
                            $nm = $dt['contato'][$r]['ct_contato'];
                            $st = $dt['contato'][$r]['ct_status'];
                            if (($tp == 'E') and ($st == 1))
                                {
                                    $t = utf8_decode($txt);
                                    $ass = utf8_decode('[BIBEAD-UFRGS] - Substituição do tutor');
                                    $t = troca($t,'$TUTOR',$nome2);
                                    $t = troca($t,'$EMAIL_TUTOR',$dtt['tt_email']);

                                    $emails = array('renefgj@gmail.com','rene@sisdoc.com.br');
                                    enviaremail($emails,$ass,$t,6);   
                                }
                        }

                        /******* Enviar e-mail */
                        exit;
                    }


                $sx = '';
                
                $sx .= '<h1>'.$dt['p_nome'].'</h1>';
                $sx .= 'Tutor atual: <b>'.$dt['tt_nome'].'</b>';
                $idt = $dt['id_tt'];

                $sx .= form_open();
                $sx .= '<hr>';
                $sx .= 'Novo tutor:';
                $sx .= '<select name="dd1" class="form-control">';
                $sql = "select * from tutores 
                            where tt_ativo = 1 and id_tt <> $idt
                            order by tt_nome";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                for ($r=0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    $sx .= '<option value="'.$line['id_tt'].'">'.$line['tt_nome'].'</option>';
                }
                $sx .= '</select>';
                $sx .= '<input type="submit" value="'.msg('Modificar').'" name="action">';
                $sx .= form_close();
                echo $sx;
            }

        function tutor_view($id)
            {
                $sql = "select * from person 
                            where p_tutor = $id and (p_ativo = 1 or p_ativo = 0) order by p_nome";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();

                $sx = $this->tutor($id);
                $sx .= '<div class="container"><div class="row"><div class="col-md-12">';
                $sx .= '<table width="100%">';
                $tot = 0;
                for ($r=0;$r < count($rlt);$r++)
                    {
                        $line = $rlt[$r];
                        //$link = '<a href="'.base_url(PATH.'tutor/'.$line['id_tt']).'">';
                        //$linka = '</a>';
                        $link = '<a href="'.base_url(PATH.'person/'.$line['id_p']).'">';
                        $linka = '</a>';                        
                        $sts = '';
                        $stx = '';
                        if ($line['p_ativo']==0)
                            {
                                $sts = '<s><span style="color: red">';
                                $stx = '</span></s>';
                            } else {
                                $sts = '<b>';
                                $stx = '</b>';
                            }
                        $tot++;
                        $sx .= '<tr>';
                        $sx .= '<td width="5%" align="right">'.$tot.'.'.'</td>';
                        $sx .= '<td width="5%">&nbsp;'.$link.$sts.$line['p_cracha'].$stx.$linka.'</td>';
                        $sx .= '<td>&nbsp;'.$link.$sts.$line['p_nome'].$stx.$linka.'</td>';
                        $sx .= '</tr>';
                    }
                $sx .= '</table>';
                $sx .= '</div>';
                $sx .= '</div>';
                $sx .= '</div>'; 


                return($sx);              
            }

        function ativo_inativo($dt)
            {
                $ativo = $dt['p_ativo'];
                $sx = '&nbsp;&nbsp;&nbsp;';
                if ($ativo == 1)
                {
                    $sx .= '<a href="#" class="btn btn-success" onclick="newxy(\''.base_url(PATH.'ajax/estudante/'.$dt['id_p'].'/0').'\',800,300);">Desativar</a>';
                } else {
                    $sx .= '<a href="#" class="btn btn-danger">Desativar</a>';
                }
                return($sx);
            }

        function bt()
            {
                global $js;
                if (!isset($js))
                {
                $sx = '
                <style>                
                .toggle { margin-bottom: 20px; }
                .toggle > input { display: none; }
                .toggle > label { position: relative; display: block; height: 28px; width: 52px; background-color: #f70000; border: 1px #f70000 solid;  border-radius: 100px; cursor: pointer; transition: all 0.3s ease; }
                .toggle > label:after { position: absolute; left: 1px; top: 1px; display: block; width: 23px; height: 23px; border-radius: 100px; background: #fff; box-shadow: 0px 3px 3px rgba(0,0,0,0.5); content: \'\'; transition: all 0.3s ease; }
                .toggle > label:active:after { transform: scale(1.15, 0.85); }
                .toggle > input:checked ~ label { background-color: #4cda64; border-color: #4cda64; }
                .toggle > input:checked ~ label:after { left: 25px; }
                .toggle > input:disabled ~ label { background-color: #d50000; pointer-events: none; }
                .toggle > input:disabled ~ label:after { background-color: rgba(255, 0, 0, 0.3); }
                </style>';
                $js = true;
                }
                $sx .= '
                <div class="toggle">
                    <input type="checkbox" id="bar1" checked>
                    <label for="bar1"></label>
                </div>
                ';

                return($sx);
            }

        function relatorio($tp=0)
            {
                $sql = "select * from tutores 
                        Inner Join (
                                Select * from person
                                where p_ativo = 1 and p_tutor <> 0
                                ) as tb1 ON p_tutor = id_tt
                        where tt_ativo = 1 order by tt_nome, p_nome";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                $sx = '<div class="container">';
                $sx .= '<div class="row">';
                $sx .= '<div class="col-md-12">';
                $sx .= '<h6>RELATÓRIO</h6>';
                $sx .= '<h1 style="border-bottom: 1px solid #000;">TUTORES E ESTUDANTES ATIVOS</h1>';
                $sx .= '<table width="100%">';
                $xtut = '';
                $tot = 0;
                $toa = 0;
                $top = 0;
                for ($r=0;$r < count($rlt);$r++)
                    {
                        $line = $rlt[$r];
                        $tut = $line['tt_nome'];
                        if ($tut != $xtut)
                            {
                                $tot++;
                                $sx .= '<tr><td colspan=3><h4>'.$tot.'. '.$line['tt_nome'].'</h4></td></tr>';
                                $xtut = $tut;                                
                                $top = 0;
                            }
                        $top++;
                        $link = '<a href="'.base_url(PATH.'person/'.$line['id_p']).'">';
                        $linka = '</a>';
                        $sx .= '<tr>';
                        $sx .= '<td align="right">&nbsp;'.$top.'.&nbsp;</td>';
                        $sx .= '<td width="9%" align="center">'.$link.$line['p_cracha'].$linka.'</td>';
                        $sx .= '<td width="87%">'.$link.$line['p_nome'].$linka.'</td>';
                        $sx .= '</tr>';
                        $toa++;
                        
                    }
                $sx .= '</table>';
                $sx .= 'Total de '.$toa.' com '.$tot.' tutores.';
                $sx .= '</div></div></div>';
                return($sx);
            }

        function tutores()
            {
                $t = array(0,0,0);
                $sql = "select * from tutores 
                        Inner Join (
                                Select count(*) as ativos, p_tutor 
                                from person
                                where p_ativo = 1 and p_tutor <> 0 group by p_tutor
                                ) as tb1 ON tb1.p_tutor = id_tt
                        left Join (
                                Select count(*) as inativo, p_tutor 
                                from person
                                where p_ativo = 0 and p_tutor <> 0 group by p_tutor
                                ) as tb2 ON tb2.p_tutor = id_tt  
                        left Join (
                                Select count(*) as hold, p_tutor 
                                from person
                                where p_ativo = 2 and p_tutor <> 0  group by p_tutor
                                ) as tb3 ON tb3.p_tutor = id_tt
                        where tt_ativo = 1 order by tt_nome";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                $sx = '';
                $sx .= '<div class="container"><div class="row"><div class="col-md-12">';
                $sx .= '<table class="table2" width="100%">';
                $sx .= '<tr>
                        <td width="2%" align="center" style="border: 1px solid #333;">#</td>
                        <td style="border: 1px solid #333;">Tutor</td>
                        <td align="center" style="border: 1px solid #333;">Ativos</td>
                        <td align="center" style="border: 1px solid #333;">Inativo</td>
                        <td align="center" style="border: 1px solid #333;">Retido</td>
                        </tr>';
                for ($r=0;$r < count($rlt);$r++)
                    {
                        $line = $rlt[$r];
                        $link = '<a href="'.base_url(PATH.'tutor/'.$line['id_tt']).'">';
                        $linka = '</a>';
                        $sx .= '<tr>';
                        $sx .= '<td>'.($r+1).'</td>';
                        $sx .= '<td>'.$link.$line['tt_nome'].$linka.'</td>';
                        $sx .= '<td align="center">'.$link.$line['ativos'].$linka.'</td>';
                        $sx .= '<td align="center">'.$link.$line['inativo'].$linka.'</td>';
                        $sx .= '<td align="center">'.$link.$line['hold'].$linka.'</td>';
                        $sx .= '</tr>';
                        $t[0] = $t[0] + round($line['ativos']);
                        $t[1] = $t[1] + round($line['inativo']);
                        $t[2] = $t[2] + round($line['hold']);
                    }

                $sx .= '<tr>';
                $sx .= '<td#</td>';
                $sx .= '<td align="right" style="border-top: 1px solid #333;">Total: <b>';
                $sx .= ($t[0]+$t[1]+$t[2]);
                $sx .= '</b></td>
                        <td align="center" style="border-top: 1px solid #333;"><b>'.$t[0].'</b></td>
                        <td align="center" style="border-top: 1px solid #333;"><b>'.$t[1].'</b></td>
                        <td align="center" style="border-top: 1px solid #333;"><b>'.$t[2].'</b></td>
                        </tr>';
                $sx .= '</table>';
                $sx .= '<a href="'.base_url(PATH.'rel').'" class="btn btn-outline-primary">Relatório Completo</a>';
                $sx .= '</div>';
                $sx .= '</div>';
                $sx .= '</div>';

                return($sx);
            }
        function painel()
            {
                $sx = '';
                $sql = "select count(*) as total from tutores where tt_ativo = 1";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();

                $link = base_url(PATH.'tutor');
                $dt = array();
                $dt['title'] = 'TUTORES';
                $dt['img'] = '';
                $dt['description'] = 'Total de '.$rlt[0]['total'].' total';
                $dt['link'] = $link;
                $dt['button'] = 'VISUALIZAR';
                $sx .= bscard($dt);
                

                $curso = $_SESSION['CURSO'];
                $sql = "select count(*) as total from person_graduacao where g_curso_1 = $curso";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                $link = base_url(PATH.'persons');
                $dt = array();
                $dt['title'] = 'ESTUDANTES';
                $dt['img'] = '';
                $dt['description'] = 'Total de '.$rlt[0]['total'].' total';
                $dt['link'] = $link;
                $dt['button'] = 'VISUALIZAR';
                $sx .= bscard($dt);

                return($sx);
            }
    }