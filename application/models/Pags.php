<?php
class pags extends CI_model {
    var $tabela = 'person';
    var $file_name = '';
    function cp_campanhas($id) {
        $cp = array();
        array_push($cp, array('$H8', 'id_ca', '', false, true));
        array_push($cp, array('$S100', 'ca_nome', 'Nome da campanha', true, true));
        array_push($cp, array('$T80:6', 'ca_descricao', 'Descrição', false, true));
        $sql = "select * from person_acompanhamento_tipo where pat_status = 1";
        array_push($cp, array('$Q id_pat:pat_nome:' . $sql, 'ca_acompanhamento', 'Acompanhamento', true, true));
        $sql = "select * from mensagem_own where m_ativo = 1";
        array_push($cp, array('$Q id_m:m_descricao:' . $sql, 'ca_own', 'Responsável', true, true));        
        array_push($cp, array('$Q id_pc:pc_nome:select * from person_curso', 'ca_curso', 'Curso', true, true));
        array_push($cp, array('$B8', '', 'Salvar >>>', false, true));
        return ($cp);
    }
    
    function row($obj) {
        $obj -> fd = array('id_p', 'p_nome', 'p_cracha');
        $obj -> lb = array('ID', 'Nome', 'Cracha');
        $obj -> mk = array('', 'L', 'C', 'C', 'C', 'C');
        return ($obj);
    }
    
    function row_campanhas($obj) {
        $obj -> fd = array('id_ca', 'ca_nome');
        $obj -> lb = array('ID', 'Nome');
        $obj -> mk = array('', 'L', 'C');
        return ($obj);
    }
    
    function link_questionario($id, $user) {
        $link = base_url('index.php/main/questionario/' . $id . '/' . $user . '/' . checkpost_link($id . $user));
        return ($link);
    }
    
    function enviar_email($us_email, $titulo, $texto) {
        
        //$config = Array('protocol' => 'smtp', 'smtp_host' => 'ssl://smtp.googlemail.com', 'smtp_port' => 465, 'smtp_user' => 'user@gmail.com', 'smtp_pass' => '', 'mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
        $config = array('mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => TRUE);
        $this -> load -> library('email', $config);
        $this -> email -> set_newline("\r\n");
        $this -> email -> from('comgradbib@ufrgs.br', 'Comgrad de Biblioteconomia / UFRGS');
        
        $list = array($us_email);
        $this -> email -> to($list);
        $this -> email -> subject($titulo);
        $this -> email -> message($texto);
        
        $this -> email -> send();
        return ($us_email . ' enviado<br>');
    }
    
    function campanha_enviar_email($id, $titulo, $texto) {
        $data = $this -> le_campanha($id);
        $sql = "select distinct ct_contato, p_nome, id_p, i_curso, pc_nome, pc_email 
        FROM campanha_respostas
        INNER JOIN person ON id_p = cr_user
        INNER JOIN person_indicadores on i_person = cr_user
        INNER JOIN person_curso ON i_curso = id_pc
        LEFT JOIN person_contato ON id_p = ct_person and ct_tipo = 'E'
        WHERE cr_campanha = $id and cr_situacao = 0";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $us_email = $line['ct_contato'];
            $nome = $line['p_nome'];
            $user = $line['id_p'];
            $email_send = $line['pc_email'];
            $link = $this -> link_questionario($id, $user);
            $texto2 = $texto . cr() . cr() . 'Link para o questionário:' . cr() . $link;
            
            $sx .= '#' . ($r + 1) . ' ' . $nome . ' &lt;' . $us_email . '&gt;';
            if (strlen($us_email) > 0) {
                $sx .= ' <font color="green">enviado</font>';
                $email = new email;
                $email -> email = $email_send;
                $email -> titulo = $titulo . ' - ' . $nome;
                $email -> texto = $texto2;
                $email -> to = $us_email;
                $email -> cc = 'renefgj@gmail.com';
                $email -> method_ufrgs();
                //$email -> to = $email_send;
                //$email -> method_ufrgs();
            } else {
                $sx .= ' <font color="red">sem e-mail registrado</font>';
            }
            $sx .= '<br>';
        }
        return ($sx);
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
    
    function le_campanha($id) {
        $sql = "select * from campanha where id_ca = $id";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
        } else {
            $line = array();
        }
        return ($line);
    }
    
    function le($id) {
        $sql = "select * from person 
                    LEFT JOIN tutores ON p_tutor = id_tt
                    where id_p = $id";
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
            left join person_curso on id_pc = g_curso_1 
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
    
    function user_add($p_nome, $p_cracha, $p_nasc, $p_cpf, $p_rg) {
        
        $p_nasc = substr(sonumero($p_nasc), 0, 8);
        $p_nasc = substr($p_nasc, 4, 4) . substr($p_nasc, 2, 2) . substr($p_nasc, 0, 2);
        $p_cpf = strzero($p_cpf, 11);
        $p_cracha = strzero($p_cracha, 8);
        $p_nome = UpperCase($p_nome);
        $p_rg = substr($p_rg, 0, 15);
        
        if (strlen($p_nome) <= 5) {
            return (0);
        }
        
        $sql = "select * from person 
        where p_nome = '$p_nome' and p_cpf = '$p_cpf'";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        
        if (count($rlt) == 0) {
            $sqli = "insert into person
            (p_nome, p_cracha, p_cpf, p_rg, p_nasc)
            values
            ('$p_nome','$p_cracha','$p_cpf','$p_rg','$p_nasc')
            ";
            $rlt = $this -> db -> query($sqli);
            
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
        }
        $line = $rlt[0];
        return ($line['id_p']);
    }
    
    function inport($file = '') {
        $sx = '<h2>Importação de dados - Sistema UFRGS</h2>';
        $f = load_file_local($file);
        /* Formato XLS */
        $f = utf8_encode($f);
        $f = html_entity_decode($f);
        $f = ascii($f);
        $f = troca($f,"'","´");
        
        $f = trim($f);        
        while(strpos($f,'  ') > 0)
        {
            $f = trim(troca($f,'  ',' '));        
        }
        $f = troca($f,';','.,');
        $f = troca($f,'\n','');
        $f = troca($f,chr(13),';');
        $f = troca($f,chr(10),';');
        $ln = splitx(';', $f . ';');
        
        $to = 0;
        for ($r = 0; $r < count($ln); $r++) {
            $lns = $ln[$r];
            $lns = troca($lns, '.,', ';');
            $lns = troca($lns, ';;', ';0;');
            $lns = troca($lns, ';;', ';0;');
            $lns = troca($lns, ';;', ';0;');
            $lnsx = troca($lns, '.,', ';');
            $lns = splitx(';', $lnsx . ';');
            if (count($lns) == 32) {
                $p_nome = trim($lns[0]);
                $p_nome = troca($p_nome,"'","´");
                $p_cracha = strzero($lns[1], 8);
                $p_nasc = $lns[2];
                $curso = trim($lns[3]);
                $curso2 = trim($lns[4]);
                $p_cpf = $lns[5];
                if (round($p_cpf) == 0) {
                    $p_cpf = strzero($p_nasc,9).'00';
                }
                $p_rg = $lns[6];
                $endereco = $lns[7];
                $endereco = troca($endereco,"'","´");
                $bairro = $lns[8];
                $bairro = troca($bairro,"'","´");
                $cep = trim(substr($lns[9], 0, strpos($lns[9], '-')));
                $cidade = trim(substr($lns[9], strpos($lns[9], '-') + 1, strlen($lns[9])));
                $cidade = troca($cidade,"'","´");
                $telefone = $lns[10];
                $email = $lns[11];
                $es_ano = $lns[12];
                $ingresso = $lns[13];
                $diplomacao = $lns[14];
                $afastado = $lns[15];
                if ($afastado == '-') { $afastado = 0; }
                $cred_obe = $lns[16];
                $cred_obe = 0;
                $cred_obr = $lns[17];
                $cred_elt = $lns[18];
                $cred_com = $lns[19];
                $cred_tim = $lns[20];
                $cred_i1 = $lns[21];
                $cred_i2 = $lns[22];
                $cred_i3 = $lns[23];
                $cred_i4 = $lns[24];
                $cred_i5 = $lns[25];
                $cred_i6 = $lns[26];
                $cred_ult_let = $lns[27];
                if (trim($cred_ult_let) == '-')
                    {
                        $cred_ult_let = $lns[13];
                    }
                $cred_matr = $lns[28];
                $cred_inte = $lns[29];
                $cred_ff = $lns[30];
                
                if (isset($lns[31])) {
                    $cred_mod = $lns[31];
                } else {
                    $cred_mod = '';
                }
                if (strlen($p_nome) > 5) {
                    $to++;
                    $id_us = $this -> user_add($p_nome, $p_cracha, $p_nasc, $p_cpf, $p_rg);
                    $sx .= ' ' . $id_us . '. ';
                    /* curso */
                    $ok = $this -> curso($id_us, $curso, $curso2, $es_ano, $ingresso, $diplomacao, $afastado, $cred_mod);
                    if ($ok == (-1)) {
                        echo 'ERRO NO CURSO (' . $curso . ') (' . $curso2 . ')<br>';
                    } else {
                        $id_curso = $this -> curso_id($curso);
                        $sx .= $p_nome . '.' . $cred_mod . ' - '.$curso.'('.$id_curso.')</br>';
                        
                        /* endereco */
                        $this -> endereco($id_us, $endereco, $bairro, $cep, $cidade);
                        /* contato */
                        $this -> contato($id_us, 'T', $telefone);
                        $this -> contato($id_us, 'E', $email);
                        
                        /* indicadores */                        
                        $this -> indicadores($id_us,$id_curso, $cred_ult_let, $cred_obe, $cred_obr, $cred_elt, $cred_com, $cred_tim, $cred_i1, $cred_i2, $cred_i3, $cred_i4, $cred_i5, $cred_i6, $cred_ult_let, $cred_matr, $cred_inte, $cred_ff);
                    }
                }
            } else {
                if (count($lns) > 10) {
                    $sx .= '<hr>Erro em '.$ln[$r].'=='.count($lns).'<hr>';
                }
            }
        }
        $sx .= '</pre>';
        $sx = '<h4>Total de '.$to.' registros processados</h4>'.$sx;
        return ($sx);
    }
    
    function indicadores($id_us, $curso, $cred_ult_let, $i1, $i2, $i3, $i4, $i5, $i6, $i7, $i8 = '', $i9 = '', $i10 = '', $i11 = '', $i12 = '', $i13 = '0', $i14 = '0', $i15 = '0', $i16 = '0', $i17 = '0', $i18 = '0', $i19 = '0', $i20 = '0', $i21 = '0', $i22 = '0') {
        $sql = "select * from person_indicadores 
                where i_person = $id_us
                    and i_ano = '$cred_ult_let' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if ($i10 = '-') { $i10 = 0; } 
        $i5 = troca($i5,',','.');
        $i8 = troca($i8,',','.');
        $i9 = troca($i9,',','.');
        if (count($rlt) == 0) {
            $sql = "insert into person_indicadores
            (i_person, i_ano, 
            i_i1, i_i2, i_i3, i_i4, i_i5, 
            i_i6, i_i7, i_i8, i_i9, i_i10, 
            i_i11, i_i12, i_i13, i_i14, i_i15, 
            i_i16, i_i17, i_i18, i_i19, i_i20,
            i_i21, i_i22, i_curso 
            ) values (
                $id_us, '$cred_ult_let',
                $i1, $i2, $i3, $i4, $i5, 
                $i6, $i7, $i8, $i9, $i10, 
                $i11, '$i12', $i13, $i14, $i15, 
                $i16, $i17, $i18, $i19, $i20,
                $i21, $i22, ".$curso."                                         
                )";
                $rlt = $this -> db -> query($sql);
            }
        }
        
        function sim_nao($c) {
            $c = trim($c);
            switch($c) {
                case 'Sim' :
                    return (1);
                break;
                case 'Não' :
                    return (0);
                break;
                case 'Nao' :
                    return (0);
                break;                
                case '-' :
                    return (0);
                break;                
                default :
                return (-1);
                exit ;
            }
        }
        
        function curso_id($curso) {
            switch ($curso) {
                case 'BIBLIOTECONOMIA' :
                    $id = 1;
                break;
                case 'ARQUIVOLOGIA' :
                    $id = 2;
                break;   
                case 'BACHARELADO EM MUSEOLOGIA' :
                    $id = 3;
                break;  
                case 'BIBLIOTECONOMIA - ENSINO A DISTANCIA':
                    $id = 5;
                break;                            
                case 'PPGCIN' :
                    $id = 4;
                break;                              
                default :
                $id = 0;
                echo 'OPS Curso:' . $curso;
                exit ;
            }
            return ($id);
        }
        
        function ingresso_tipo($tp) {
            switch ($tp) {
                case '0' :
                    $id = 99;
                break;
                case 'Vestibular' :
                    $id = 1;
                break;
                case 'Ingresso de Diplomado' :
                    $id = 2;
                break;
                case 'Transferência Compulsória' :
                    $id = 3;
                break;
                case 'SISU - Ingresso Edição 1' :
                    $id = 4;
                break;
                case 'Transferência Interna' :
                    $id = 5;
                break;
                case 'Aluno Convênio' :
                    $id = 6;
                break;
                case 'Transferência Interna - Aluno Convênio' :
                    $id = 7;
                break;
                case 'Transferência Voluntária' :
                    $id = 8;
                break;
                case '-':
                    $id = 9;
                break;
                default :
                $id = 0;
            }
            return ($id);
        }
        
        function contato($id_us, $tipo, $dado) {
            $dado = trim($dado);
            if (strlen($dado) == 0) {
                return (0);
            }
            $sql = "select * from person_contato 
            where ct_person = $id_us
            and ct_tipo = '$tipo' 
            and ct_contato = '$dado' ";
            
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) == 0) {
                if (strlen($dado) > 3) {
                    $sql = "insert into person_contato
                    (ct_person, ct_tipo, ct_contato)
                    values
                    ($id_us,'$tipo','$dado')";
                    $rlt = $this -> db -> query($sql);
                }
            }
        }

        function check_address()
            {
                $sql = "
                SELECT ed_person, count(*) as total, max(id_ed) as max
                    FROM  person_endereco
                    group by ed_person
                    order by total desc";
                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();
                $t=0;
                for ($r=0;$r < count($rlt);$r++)
                    {
                        $line = $rlt[$r];
                        if ($line['total'] > 1)
                            {
                                $sql = "delete from person_endereco where id_ed = ".$line['max'];
                                $this->db->query($sql);
                                $t++;
                            }
                    }
                if ($t > 0) { $this->check_address(); }
            }
        
        function endereco($id_us, $endereco, $bairro, $cep, $cidade) {            
            $cidade = uppercase($cidade);
            $estado = '';
            if (strpos($cidade, '-')) {
                $estado = trim(substr($cidade, strpos($cidade, '-') + 1, 5));
                $cidade = substr($cidade, 0, strpos($cidade, '-'));
            }
            $sql = "select * from person_endereco where ed_person = $id_us ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) > 0) {
                $line = $rlt[0];
                if ($line['ed_endereco'] != $endereco) {
                    $sql = "update person_endereco
                    set ed_status = 0
                    where ed_person = $id_us";
                    $rlt = $this -> db -> query($sql);
                    $rlt = array();
                    sleep(1);
                }
            } else {
                $sql = "insert into person_endereco
                (ed_person, ed_endereco, ed_bairro, ed_cep, ed_cidade, ed_estado)
                values
                ($id_us, '$endereco', '$bairro','$cep','$cidade','$estado')";
                $rlt = $this -> db -> query($sql);
            }
        }
        
        function curso($id_us, $c1, $c2, $es_ano, $ingresso, $diplomacao, $afastado, $g_ingresso_modo) {
            $semestre = substr($ingresso, 5, 1);
            $ingresso = substr($ingresso, 0, 4);
            if ($id_us <= 0) {
                echo '<br>=>id_us is zero';
                return (0);
            }
            $c1 = $this -> curso_id($c1);
            $c2 = $this -> curso_id($c2);
            
            $afastado = $this -> sim_nao($afastado);
            if ($afastado == -1) {
                ECHO '=>'.$semestre.'/';
                ECHO ''.$ingresso.'<br>=afastado=';
                $afastado = 0;
                //return (-1);
            }
            $g_ingresso_modo = $this -> ingresso_tipo($g_ingresso_modo);
            
            $sql = "select * from person_graduacao
            where g_person = $id_us
            and g_curso_1 = $c1
            and g_curso_2 = $c2
            and g_ingresso = '$ingresso'
            ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) == 0) {
                $sql = "insert into person_graduacao
                (g_curso_1, g_curso_2, g_ano_em, g_ingresso, g_ingresso_sem, g_diplomacao, g_person, g_afastado, g_ingresso_modo)
                values
                ($c1, $c2, '$es_ano','$ingresso','$semestre','$diplomacao',$id_us, $afastado, '$g_ingresso_modo')";
                $rlt = $this -> db -> query($sql);
            }
        }
        
        function list_acompanhamento($limit = 20, $ord = 'id_rod desc') {
            $sql = "select * from person_rod
            INNER JOIN person ON rod_person = id_p 
            INNER JOIN person_acompanhamento_tipo ON id_pat = rod_tipo
            order by $ord
            limit $limit";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            
            $sx = '<table width="100%">';
            $sx .= '<tr><th>#</th><th>Cracha</th><th>Nome</th><th>Programa</th></tr>' . cr();
            
            $xname = "";
            $id = 0;
            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];
                $name = trim($line['p_cracha']);
                $sx .= '<tr>';
                
                if ($xname == $name) {
                    $sx .= '<td width="2%">';
                    $sx .= ' ';
                    $sx .= '</td>';
                    
                    $sx .= '<td width="10%">';
                    $sx .= '&nbsp;';
                    $sx .= '</td>';
                    
                    $sx .= '<td width="60%">';
                    $sx .= '&nbsp;';
                    $sx .= '</td>';
                } else {
                    $xname = $name;
                    $id++;
                    $sx .= '<td width="2%" style="border-top: 1px solid #808080;">';
                    $sx .= ($id);
                    $sx .= '</td>';
                    
                    $link = '<a href="' . base_url('index.php/main/person/' . $line['rod_person'] . '/' . md5($line['rod_person'])) . '">';
                    $sx .= '<td width="10%" style="border-top: 1px solid #808080;">';
                    $sx .= $link . $name . '</a>';
                    $sx .= '</td>';
                    
                    $sx .= '<td width="60%" style="border-top: 1px solid #808080;">';
                    $sx .= $link . $line['p_nome'] . '</a>';
                    $sx .= '</td>';
                }
                
                $sx .= '<td width="28%" style="border-top: 1px solid #808080;">';
                $sx .= $line['pat_nome'];
                $sx .= '</td>';
                
                $sx .= '</tr>';
            }
            $sx .= '</table>';
            return ($sx);
        }
        
        function incluir_acompanhamento($c, $t) {
            $id_us = $this -> le_cracha($c);
            $sql = "select * from person_rod 
            where rod_person = $id_us and rod_tipo = $t
            order by rod_update desc ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            
            if (count($rlt) == 0) {
                $sql = "insert into person_rod
                (rod_person, rod_tipo)
                values
                ($id_us, $t)";
                $rlt = $this -> db -> query($sql);
                return (1);
            }
            return (0);
        }
        
        function rel_cidade($tp = 1) {
            switch($tp) {
                case '1' :
                    $sql = "SELECT count(*) as total, ed_cidade FROM `person_endereco 
                    where ed_status = 1 
                    group by ed_cidade";
                    echo $sql;
                break;
            }
        }
        
        function rel_tempo_medio_integralizacao($arg1 = '', $arg2 = '') {
            $arg0 = '';
            $sql = "SELECT g_ingresso, g_ingresso_sem, 
            i_i12, 1 as total, g_person 
            FROM person_indicadores 
            INNER JOIN person_graduacao on i_person = g_person 
            where i_i6 >= 8 AND i_curso = ".CURSO."
            group by g_ingresso, g_ingresso_sem, i_i12, g_person";

            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            $rs = array();
            for ($r = 0; $r < 15; $r = $r + 0.5) {
                $rs[strzero($r * 10, 3)] = 0;
            }
            $t = 0;
            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];
                $ano1 = round($line['g_ingresso']);
                $ano1s = round($line['g_ingresso_sem']);
                $ano2 = round(substr($line['i_i12'], 0, 4));
                $ano2s = round(substr($line['i_i12'], 5, 2));
                $ano = $ano2 - $ano1;
                $t = $t + 1;
                if ($ano2s == $ano1s) {
                    $ano = $ano + 0.5;
                }
                $ano = strzero($ano * 10, 3);
                if ($ano > 0) {
                    if (!isset($rs[$ano])) {
                        $rs[$ano] = $line['total'];
                    } else {
                        $rs[$ano] = $rs[$ano] + $line['total'];
                    }
                }
            }
            
            $data1 = '';
            $data2 = '';
            foreach ($rs as $key => $value) {
                if (strlen($data1) > 0) {
                    $data1 .= ', ';
                    $data2 .= ', ';
                }
                $data1 .= '"' . ($key / 10) . '"';
                $data2 .= '' . ($value / 1) . '';
            }
            $sx = '
            <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
            <script src="https://code.highcharts.com/highcharts.js"></script>
            <script src="https://code.highcharts.com/modules/annotations.js"></script>            
            <div id="container" style="height: 400px; min-width: 380px"></div>
            <center>Contabilizado ' . $t . ' históricos, cálculo do tempo do estudante para chegar no 8º período.</center>
            <style>
            #container {
                max-width: 800px;
                height: 400px;
                margin: 1em auto;
                border: 1px solid #000000;
            }
            </style>
            
            <script>
            Highcharts.chart(\'container\', {
                chart: {
                    type: \'column\'
                },
                title: {
                    text: \'Tempo de Integralização do Curso' . $arg0 . '\'
                },
                xAxis: {
                    categories: [ ' . $data1 . ' ],
                    crosshair: true
                },
                
                yAxis: {
                    min: 0,
                    title: {
                        text: \'Estudantes\'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.01,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: \'Estudantes\',
                    data: [' . $data2 . ']
                    
                }]
            }); 
            </script>
            ';
            return ($sx);
        }
        
        function rel_idade_media($arg1 = '', $arg2 = '') {
            $arg0 = '';
            $sql = "select i_ano from person_indicadores where i_curso = ".CURSO." order by i_ano desc limit 1";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array($rlt);
            if (count($rlt) == 0)
            {
                return("Sem dados para calcular");
            }
            $ano = $rlt[0]['i_ano'];
            
            $sql = "select (".date("Y")." - nascimento) as idade, count(*) as total
            from (
                SELECT substr(p_nasc,1,4) as nascimento, id_p
                FROM `person_indicadores` 
                INNER JOIN person_graduacao on i_person = g_person
                INNER JOIN person ON i_person = id_p
                where i_curso = ".CURSO." and i_ano = '$ano'
                group by id_p, nascimento
                ) as tabela 
                group by idade
                order by idade";
                
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                
                $data1 = '';
                $data2 = '';
                $t = 0;
                $id = 0;
                for ($r=0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    if (strlen($data1) > 0) {
                        $data1 .= ', ';
                        $data2 .= ', ';
                    }
                    $data1 .= '"' . ($line['idade']) . '"';
                    $data2 .= '' . ($line['total']) . '';
                    $t = $t + $line['total'];
                    $id = $id + $line['idade'] * $line['total'];
                }
                $sx = 'Média de idade = '.number_format($id / $t,1,',','.'). ' anos';
                $sx .= '
                <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/annotations.js"></script>            
                <div id="container" style="height: 400px; min-width: 380px"></div>
                <center>Contabilizado ' . $t . ' estudantes matriculados em '.$ano.'</center>
                <style>
                #container {
                    max-width: 800px;
                    height: 400px;
                    margin: 1em auto;
                    border: 1px solid #000000;
                }
                </style>
                
                <script>
                Highcharts.chart(\'container\', {
                    chart: {
                        type: \'column\'
                    },
                    title: {
                        text: \'Idade média dos estudantes' . $arg0 . '\'
                    },
                    xAxis: {
                        categories: [ ' . $data1 . ' ],
                        crosshair: true
                    },
                    
                    yAxis: {
                        min: 0,
                        title: {
                            text: \'Estudantes\'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.01,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: \'Idade\',
                        data: [' . $data2 . ']
                        
                    }]
                }); 
                </script>
                ';
                return ($sx);
            }                    
            
            function export_answer($arg1) {
                $sql = "select * from person
                LEFT JOIN campanha_respostas ON id_p = cr_user 
                where cr_campanha = $arg1 and cr_situacao <> 9
                ";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $rsp = array();
                $sx = '<table border=1>';
                $sx .= '<tr>';
                foreach ($rlt[0] as $key => $value) {
                    $sx .= '<th>'.$key.'</th>';
                }
                $sx .= '</tr>'.cr();
                for ($r = 0; $r < count($rlt); $r++) {
                    $ln = $rlt[$r];
                    $sx .= '<tr>';
                    foreach ($ln as $key => $value) {
                        $sx .= '<td>' . $value . '</td>';
                    }
                    $sx .= '</tr>';
                }
                $sx .= '</table>';
                return($sx);
            }
            
            function campanha_situacao($arg1) {
                
                $sql = "select * from campanha_respostas
                LEFT JOIN person ON id_p = cr_user 
                where cr_campanha = $arg1 and cr_situacao <> 9
                ORDER BY p_nome";
                
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $rsp = array();
                $tot = 0;
                $sx = '<table width="100%">';
                $sx .= '<tr><th width="1%">#</th>
                <th>nome</th>
                <th width="10%">cracha</th>
                <th width="15%">situação</th>
                </tr>';
                
                for ($r = 0; $r < count($rlt); $r++) {
                    $tot++;
                    $line = $rlt[$r];
                    $link = '<a href="' . base_url('index.php/main/person/' . $line['id_p'] . '/' . checkpost_link($line['id_p'])) . '" target="_new">';
                    $sx .= '<tr>';
                    $sx .= '<td>' . ($r + 1) . '</td>';
                    $sx .= '<td>' . $link . $line['p_nome'] . '</a>' . '</td>';
                    $sx .= '<td>' . $line['p_cracha'] . '</td>';
                    $link .= '<a name="">';
                    
                    if ($line['cr_situacao'] != '0') {
                        $link = '<a href="#" onclick="newwin(\'' . base_url('index.php/main/questionario_ver/' . $line['id_cr'] . '/' . checkpost_link($line['id_cr'])) . '\');">';
                    }
                    $sx .= '<td>' . $link . msg('q_situacao_' . $line['cr_situacao']) . '</a></td>';
                    $sit = 'q_situacao_' . $line['cr_situacao'];
                    if (isset($rsp[$sit])) {
                        $rsp[$sit] = $rsp[$sit] + 1;
                    } else {
                        $rsp[$sit] = 1;
                    }
                }
                $sx .= '</table>';
                $sa = '<ul>';
                foreach ($rsp as $key => $value) {
                    $sa .= '<li>' . msg($key) . ' (' . $value . ')</li>';
                }
                $sa .= '<a href="' . base_url('index.php/main/campanha_xls/' . $arg1) . '">Exportar respostas</a>';
                $sa .= '</ul>';
                return ($sa . $sx);
                
            }
            
            function cancela_campanha($arg1 = '') {
                $sql = "update campanha_respostas 
                set cr_situacao  = 9
                where cr_campanha = $arg1 ";
                $rlt2 = $this -> db -> query($sql);
                return (0);
            }
            
            function alunos_select($arg1 = '') {
                $sql = "select * from campanha where id_ca = " . $arg1;
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $line = $rlt[0];
                $tipo = $line['ca_acompanhamento'];
                $curso = $line['ca_curso'];
                
                $sql = "";
                $y = date("Y");
                $s = '1';
                $s = $y . '/' . $s;
                if (date("m") > 7) { $s = '2';
                }
                switch ($tipo) {
                    case 5 :
                        /* 2 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 1";
                    break;                
                    case 6 :
                        /* 2 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 2";
                    break;
                    case 7 :
                        /* 3 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 3";
                    break; 
                    case 8 :
                        /* 6 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 4";
                    break;                                
                    case 9 :
                        /* 6 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 5";
                    break;                                               
                    case 10 :
                        /* 6 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 6";
                    break;
                    case 11 :
                        /* 7 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 = 7";
                    break;
                    case 12 :
                        /* 8 etapa */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and i_i6 > 7";
                    break;
                    case 13 :
                        /* teste */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE p_nome = 'Comgrad de Biblioteconomia'";
                    break;
                    case 14 :
                        /* 8 etapa ou formado */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 <> '$s' and i_i6 >= 8";
                    break;                    
                    case 15 :
                        /* todos os alunos */
                        $sql = "SELECT * FROM person_indicadores 
                        INNER JOIN person on i_person = id_p 
                        WHERE i_i12 = '$s' and (i_i6 >= 0 AND i_i6 <= 8)";
                    break;                
                    default :
                    echo 'Não implementado Regra #' . $tipo;
                    exit ;
                }
                if (strlen($sql) > 0) {
                    $sql .= " AND (i_curso = ".$curso.")";
                    $rlt = $this -> db -> query($sql);
                    $rlt = $rlt -> result_array();
                    for ($r = 0; $r < count($rlt); $r++) {
                        $line = $rlt[$r];
                        $arg3 = $line['id_p'];
                        $sql = "select * from campanha_respostas 
                        where cr_user = $arg3 
                        and cr_campanha = $arg1";
                        $rlt2 = $this -> db -> query($sql);
                        $rlt2 = $rlt2 -> result_array();
                        if (count($rlt2) == 0) {
                            $sql = "insert into campanha_respostas 
                            (cr_user, cr_campanha)
                            values
                            ('$arg3','$arg1')";
                            $rlt2 = $this -> db -> query($sql);
                        } else {
                            $line = $rlt2[0];
                            $sql = "update campanha_respostas
                            set cr_situacao = 0
                            where cr_situacao = 9 and id_cr = ".$line['id_cr'];
                            $rlt3 = $this -> db -> query($sql);
                            
                        }
                    }
                }
                
                return ('');
            }
            
            function campanha_prepara($arg1 = '', $arg2 = '') {
                $sql = "select * from person_rod 
                where rod_tipo = $arg2 and rod_status = 1";
                
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                for ($r = 0; $r < count($rlt); $r++) {
                    $line = $rlt[$r];
                    $arg3 = $line['rod_person'];
                    $sql = "select * from campanha_respostas
                    WHERE   cr_user = $arg3
                    AND cr_campanha = $arg1 ";
                    $rlt2 = $this -> db -> query($sql);
                    $rlt2 = $rlt2 -> result_array();
                    if (count($rlt2) == 0) {
                        $sql = "insert into campanha_respostas 
                        (cr_user, cr_campanha)
                        values
                        ('$arg3','$arg1')";
                        $rlt2 = $this -> db -> query($sql);
                    } else {
                        $ln = $rlt2[0];
                        $sql = "update campanha_respostas set cr_situacao = 0
                        where id_cr = " . $ln['id_cr'];
                        $rlt2 = $this -> db -> query($sql);
                        
                    }
                }
                return ('');
            }
            
            function questionario($arg1, $arg2) {
                
                $data = $this -> le($arg2);
                $tl = $this -> load -> view('person/show', $data, true);
                
                $id = 0;
                $sql = "select * from campanha_respostas
                WHERE cr_user = $arg2
                AND cr_campanha = $arg1 ";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                if (count($rlt) > 0) {
                    $line = $rlt[0];
                    $id = $line['id_cr'];
                    $sit = $line['cr_situacao'];
                    
                }
                
                if ($sit == 0) {
                    $sql = "select * from campanha_questionario 
                    WHERE qs_campanha = $arg1
                    ORDER BY qs_ordem";
                    $rlt = $this -> db -> query($sql);
                    $rlt = $rlt -> result_array();
                    $cp = array();
                    
                    array_push($cp, array('$H8', 'id_cr', '', false, true));
                    array_push($cp, array('$HV', 'cr_user', $arg2, false, true));
                    array_push($cp, array('$HV', 'cr_campanha', $arg1, false, true));
                    array_push($cp, array('$HV', 'cr_saved', date("Y-m-d H:i:s"), false, true));
                    array_push($cp, array('$HV', 'cr_situacao', 1, true, true));
                    
                    for ($r = 0; $r < count($rlt); $r++) {
                        $line = $rlt[$r];
                        $ps = mst($line['qs_pergunta']);
                        array_push($cp, array($line['qs_query'], 'cr_p' . $r, '<b>' . $ps . '</b>', false, true));
                    }
                    array_push($cp, array('$B8', '', 'Finalizar questionário >>>', false, true));
                    $form = new form;
                    $form -> id = $id;
                    $tela = $form -> editar($cp, 'campanha_respostas');
                    
                    if ($form -> saved > 0) {
                        $tela = '
                        <div style="margin-top: 100px;">
                        <center>
                        <h1>Sucesso!</h1>
                        <p>Questionário enviado com sucesso!</p>
                        </div>';
                    }
                } else {
                    $tela = '
                    <div style="margin-top: 100px;">
                    <center>
                    <h1>Questionário já finalizado!</h1>
                    <p>Esse questionário já foi enviado!</p>
                    </div>';
                }
                
                return ($tl . $tela);
            }
            
            function questionario_editar_cp($id2, $id1 = 0) {
                $cp = array();
                $sx = '';
                array_push($cp, array('$H8', 'id_qs', '', false, false));
                array_push($cp, array('$T80:5', 'qs_pergunta', 'Pergunta', true, true));
                if ($id1 != 0) {
                    array_push($cp, array('$HV', 'qs_campanha', $id1, true, true));
                }
                array_push($cp, array('$[1-99]', 'qs_ordem', 'Ordem', true, true));
                array_push($cp, array('$T80:10', 'qs_query', 'Tipo', true, true));
                $m = '$R Nenhuma:Nenhuma&Pouca:Pouca&Média:Média&Muita:Muita&Totalmente Motivado:Totalmente Motivado';
                $m .= '<hr>$S100';
                $m .= '<hr>$T80:4';
                $form = new form;
                $form -> id = $id2;
                $sx = $form -> editar($cp, 'campanha_questionario');
                $sx .= '<tt>' . $m . '</tt>';
                if ($form -> saved > 0) {
                    $sx = '<script> wclose(); </script>';
                }
                return ($sx);
            }
            
            function questionario_editar($id) {
                $sql = "select * from campanha_respostas
                WHERE id_cr = $id";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                
                if (count($rlt) > 0) {
                    $line = $rlt[0];
                    $id = $line['id_cr'];
                    $sit = $line['cr_situacao'];
                    $arg1 = $line['cr_campanha'];
                }
                
                $sql = "select * from campanha_questionario 
                WHERE qs_campanha = $id
                ORDER BY qs_ordem";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $sx = '';
                for ($r = 0; $r < count($rlt); $r++) {
                    $line2 = $rlt[$r];
                    $idi = $line2['id_qs'];
                    $sx .= '<a href="#" onclick="newxy(\'' . base_url('index.php/main/campanhas_questionario_editar/' . $id . '/' . $idi) . '\',800,600);">';
                    $sx .= '[ed]';
                    $sx .= '</a> - ';
                    
                    $sx .= $line2['qs_pergunta'];
                    $sx .= '<br>' . '<b>' . $line['cr_p' . $r] . '</b>';
                    $sx .= '<hr>';
                }
                
                $sx .= '<a href="#" onclick="newxy(\'' . base_url('index.php/main/campanhas_questionario_editar/' . $id . '/0') . '\',800,600);">NOVO</a>';
                return ($sx);
                
            }
            
            function questionario_ver($id) {
                
                $sql = "select * from campanha_respostas
                WHERE id_cr = $id";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                
                if (count($rlt) > 0) {
                    $line = $rlt[0];
                    $id = $line['id_cr'];
                    $sit = $line['cr_situacao'];
                    $arg1 = $line['cr_campanha'];
                }
                
                $sql = "select * from campanha_questionario 
                WHERE qs_campanha = $arg1
                ORDER BY qs_ordem";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $sx = '';
                for ($r = 0; $r < count($rlt); $r++) {
                    $line2 = $rlt[$r];
                    $sx .= $line2['qs_pergunta'];
                    $sx .= '<br><b>' . $line['cr_p' . $r] . '</b>';
                    $sx .= '<hr>';
                }
                echo $sx;
                exit ;
                
                return ($tela);
            }
            
            function rel_alunos_periodo($arg1 = '', $arg2 = '') {
                $sx = '';
                $arg0 = '';
                $sql = "SELECT i_ano 
                FROM person_indicadores
                where i_i6 <> '-' AND i_curso = ".CURSO."
                GROUP BY i_ano
                ORDER BY i_ano desc";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                
                for ($r = 0; $r < count($rlt); $r++) {
                    $line = $rlt[$r];
                    if (strlen($arg0) == 0) {
                        $arg0 = $line['i_ano'];
                    }
                    if (strlen($sx) > 0) { $sx .= ' | ';
                    }
                    $sx .= '<a href="' . base_url('index.php/main/relatorio/2/' . $line['i_ano']) . '" style="font-size: 70%;">' . $line['i_ano'] . '</a>';
                }
                
                /********************************************************************/
                if (strlen($arg2) > 0) {
                    $arg0 = $arg1 . '/' . $arg2;
                }
                $sql = "SELECT i_i6, count(*) as total 
                FROM person_indicadores 
                WHERE i_ano = '$arg0' 
                GROUP BY i_i6
                ORDER BY i_i6";
                
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $data1 = '';
                $data2 = '';
                for ($r = 0; $r < count($rlt); $r++) {
                    $line = $rlt[$r];
                    if (strlen($data1) > 0) {
                        $data1 .= ', ';
                        $data2 .= ', ';
                    }
                    $data1 .= '"' . $line['i_i6'] . '"';
                    $data2 .= '' . $line['total'] . '';
                }
                
                $sx .= '
                <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/annotations.js"></script>            
                <div id="container" style="height: 400px; min-width: 380px"></div>
                <style>
                #container {
                    max-width: 800px;
                    height: 400px;
                    margin: 1em auto;
                    border: 1px solid #000000;
                }
                </style>
                
                <script>
                Highcharts.chart(\'container\', {
                    chart: {
                        type: \'column\'
                    },
                    title: {
                        text: \'Estudantes ativos pelo período ' . $arg0 . '\'
                    },
                    xAxis: {
                        categories: [ ' . $data1 . ' ],
                        crosshair: true
                    },
                    
                    yAxis: {
                        min: 0,
                        title: {
                            text: \'Estudantes\'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.01,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: \'Estudantes\',
                        data: [' . $data2 . ']
                        
                    }]
                }); 
                </script>
                ';
                
                /* PARTE II */
                $sql = "SELECT i_i6, count(*) as total 
                FROM person_indicadores
                where i_i6 <> 0 
                group by i_i6 
                order by i_i6";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                $data1 = '';
                $data2 = '';
                for ($r = 0; $r < count($rlt); $r++) {
                    $line = $rlt[$r];
                    if (strlen($data1) > 0) {
                        $data1 .= ', ';
                        $data2 .= ', ';
                    }
                    $data1 .= '"' . $line['i_i6'] . '"';
                    $data2 .= '' . $line['total'] . '';
                }
                
                $sx .= '          
                <div id="container2" style="height: 400px; min-width: 380px"></div>
                <div id="container3" style="height: 400px; min-width: 380px">
                Metodologia: Em todos os anos de análise calcula-se a frequencia do indicado i6, mostrando o tempo que os estudantes passam em cada período.
                </div>
                
                <style>
                #container2 {
                    max-width: 800px;
                    height: 400px;
                    margin: 1em auto;
                    border: 1px solid #000000;
                }
                #container3 {
                    max-width: 800px;
                    margin: 1em auto;
                }
                </style>
                
                <script>
                Highcharts.chart(\'container2\', {
                    chart: {
                        type: \'column\'
                    },
                    title: {
                        text: \'Concentração de períodos do curso - Desde 2008\'
                    },
                    xAxis: {
                        categories: [ ' . $data1 . ' ],
                        crosshair: true
                    },
                    
                    yAxis: {
                        min: 0,
                        title: {
                            text: \'Estudantes\'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.01,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: \'Estudantes\',
                        data: [' . $data2 . ']
                        
                    }]
                }); 
                </script>
                ';
                
                return ($sx);
            }
            
            function rel_alunos_matriculados() {
                $sql = "select * from (
                    SELECT count(*) as total, i_ano 
                    FROM `person_indicadores` 
                    WHERE i_ano <> '-' AND i_curso = ".CURSO."                       
                    group by i_ano 
                    ) as tabela where total > 20
                    order by i_ano";
                    $rlt = $this -> db -> query($sql);
                    $rlt = $rlt -> result_array();
                    $data1 = '';
                    $data2 = '';
                    for ($r = 0; $r < count($rlt); $r++) {
                        $line = $rlt[$r];
                        if (strlen($data1) > 0) {
                            $data1 .= ', ';
                            $data2 .= ', ';
                        }
                        $data1 .= '"' . $line['i_ano'] . '"';
                        $data2 .= '' . $line['total'] . '';
                    }
                    
                    $sx = '
                    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/annotations.js"></script>            
                    <div id="container" style="height: 400px; min-width: 380px"></div>
                    <style>
                    #container {
                        max-width: 800px;
                        height: 400px;
                        margin: 1em auto;
                        border: 1px solid #000000;
                    }
                    </style>
                    
                    <script>
                    Highcharts.chart(\'container\', {
                        chart: {
                            type: \'column\'
                        },
                        title: {
                            text: \'Estudantes ativos por semestre\'
                        },
                        xAxis: {
                            categories: [ ' . $data1 . ' ],
                            crosshair: true
                        },
                        
                        yAxis: {
                            min: 0,
                            title: {
                                text: \'Estudantes\'
                            }
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.01,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: \'Estudantes\',
                            data: [' . $data2 . ']
                            
                        }]
                    }); 
                    </script>
                    ';
                    
                    return ($sx);
                }
                function form_file($title='')
                {
                    if (strlen($title) > 0)
                    {
                        $sx = '<h4>'.$title.'</h4>'.cr();
                    } else {
                        $sx = '';
                    }
                    $sx .= '
                    <form action="" method="post" enctype="multipart/form-data">
                    <!-- MAX_FILE_SIZE deve preceder o campo input -->
                    <!-- O Nome do elemento input determina o nome da array $_FILES -->
                    Enviar esse arquivo: <input name="userfile" type="file" />
                    <br><br>
                    <input type="submit" value="Enviar arquivo" class="btn btn-primary" />
                    </form>               
                    ';                
                    if (isset($_FILES['userfile']['tmp_name']))
                    {
                        $file_name = $_FILES['userfile']['tmp_name'];
                        if (file_exists(($file_name)))
                        {
                            $this->file_name = $file_name;
                        }
                    }
                    return($sx);
                }
                
            }
            
            
            ?>
