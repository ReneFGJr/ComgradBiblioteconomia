<?php
class pags extends CI_model {
    function le($id)
        {
            $sql = "select * from person where id_p = $id";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            if (count($rlt) > 0)
                {
                    $line = $rlt[0];
                    /* enderecos */
                    $sql = "select * from person_endereco where ed_person = $id";
                    $rlt = $this->db->query($sql);
                    $rlt = $rlt->result_array();
                    $line['endereco'] = $rlt;
                    
                    /* contatos */
                    $sql = "select * from person_contato where ct_person = $id";
                    $rlt = $this->db->query($sql);
                    $rlt = $rlt->result_array();
                    $line['contato'] = $rlt;                    
                                        
                    /* graduacao */
                    $sql = "select * from person_graduacao where g_person = $id";
                    $rlt = $this->db->query($sql);
                    $rlt = $rlt->result_array();
                    $line['graduacao'] = $rlt;   
                    return($line);                 
                }
            return(array());
        }
    function user_add($p_nome, $p_cracha, $p_nasc, $p_cpf, $p_rg) {
        $p_nasc = substr(sonumero($p_nasc), 0, 8);
        $p_nasc = substr($p_nasc, 4, 4) . substr($p_nasc, 2, 2) . substr($p_nasc, 0, 2);
        $p_cpf = strzero($p_cpf, 11);
        $p_cracha = strzero($p_cracha, 8);
        $p_nome = UpperCase($p_nome);
        $p_rg = substr($p_rg,0,15);
        
        if (strlen($p_nome) <= 5)
            {
                return(0);
            }

        if (round($p_cpf) == 0) {
            echo 'ops CPF inválido de '.$p_nome.' CPF:'.$p_cpf;
            exit;
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
        $sx = '<h2>Importação de dados</h2>';
        $f = load_file_local($file);
        $f = utf8_encode($f);

        $f = troca($f, ';', '£');
        $f = troca($f, "'", "´");
        $f = troca($f, '<br/>', '£');
        $f = troca($f, '>', '£');
        $f = troca($f, '<br>', '£');
        $f = troca($f, '\n', '£');
        $f = troca($f, '££', '£-£');

        $f = troca($f, chr(13), ';');
        $f = troca($f, chr(10), '');

        $ln = splitx(';', $f . ';');

        for ($r = 0; $r < count($ln); $r++) {
            $lns = $ln[$r];
            $lns = troca($lns, '£', ';');
            $lns = splitx(';', $lns . ';');

            if (isset($lns[13])) {
                $p_nome = trim($lns[0]);
                $p_cracha = strzero($lns[1], 8);
                $p_nasc = $lns[2];
                $curso = $lns[3];
                $curso2 = $lns[4];
                $p_cpf = $lns[5];
                $p_rg = $lns[6];
                $endereco = $lns[7];
                $bairro = $lns[8];
                $cep = trim(substr($lns[9], 0, strpos($lns[9], '-')));
                $cidade = trim(substr($lns[9], strpos($lns[9], '-') + 1, strlen($lns[9])));
                $telefone = $lns[10];
                $email = $lns[11];
                $es_ano = $lns[12];
                $ingresso = $lns[13];
                $diplomacao = $lns[14];
                $afastado = $lns[15];
                $cred_obe = $lns[16];
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
                $cred_matr = $lns[28];
                $cred_inte = $lns[29];
                $cred_ff = $lns[30];
                if (isset($lns[31])) {
                    $cred_mod = $lns[31];
                } else {
                    $cred_mod = '';
                }
                if (strlen($p_nome) > 5)
                    {
                        $id_us = $this -> user_add($p_nome, $p_cracha, $p_nasc, $p_cpf, $p_rg);
                        $sx .= ' ' . $id_us . '. ';
                        /* curso */
                        $this -> curso($id_us, $curso, $curso2, $es_ano, $ingresso, $diplomacao, $afastado,$cred_mod);
                        $sx .= $p_nome . '.' . $cred_mod . '</br>';
                        
                        /* endereco */
                        $this->endereco($id_us, $endereco, $bairro, $cep, $cidade);

                        /* contato */
                        $this->contato($id_us, 'T', $telefone);
                        $this->contato($id_us, 'E', $email);
                    }
            }
        }
        return($sx);
    }

    function sim_nao($c) {
        switch($c) {
            case 'Sim' :
                return (1);
                break;
            case 'Não' :
                return (0);
                break;
            default :
                echo '===>' . $c;
                exit ;
        }
    }

    function curso_id($curso) {
        switch ($curso) {
            case 'BIBLIOTECONOMIA' :
                $id = 1;
                break;
            default :
                $id = 0;
                echo 'OPS ' . $curso;
                exit ;
        }
        return ($id);
    }
    
    function ingresso_tipo($tp) {
        switch ($tp) {
            case 'Vestibular' :
                $id = 1;
                break;
            case 'Ingresso de Diplomado':
                $id = 2;
                break;
            case 'Transferência Compulsória':
                $id = 3;
                break;
            case 'SISU - Ingresso Edição 1':
                $id = 4;
                break;
            case 'Transferência Interna':
                $id = 5;
                break;
            default :
                $id = 0;
                echo 'OPS ' . $tp;
                exit ;
        }
        return ($id);
    }
    
    function contato($id_us,$tipo,$dado)
        {
            $dado = trim($dado);
            if (strlen($dado) == 0)
                {
                    return(0);
                }
            $sql = "select * from person_contato 
                        where ct_person = $id_us
                        and ct_tipo = '$tipo' 
                        and ct_contato = '$dado' "; 

            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) == 0) {
                $sql = "insert into person_contato
                        (ct_person, ct_tipo, ct_contato)
                        values
                        ($id_us,'$tipo','$dado')";
                $rlt = $this->db->query($sql);
            }                           
        }
            
    
    function endereco($id_us, $endereco, $bairro, $cep, $cidade)
        {
        $cidade = uppercase($cidade);
        $estado = '';
        if (strpos($cidade,'-'))
            {
                $estado = trim(substr($cidade,strpos($cidade,'-')+1,5));
                $cidade = substr($cidade,0,strpos($cidade,'-'));
            }
        $sql = "select * from person_endereco
                        where ed_person = $id_us ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) >0) {
            $line = $rlt[0];
            if ($line['ed_endereco'] != $endereco)
                {
                    $sql = "update person_endereco
                            set ed_status = 0
                            where ed_person = $id_us";
                    $rlt = $rlt -> result_array();
                    $rlt = array();
                }
        }
        if (count($rlt) == 0) {
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
            return (0);
        }
        $c1 = $this -> curso_id($c1);
        $c2 = $this -> curso_id($c2);
        $afastado = $this -> sim_nao($afastado);
        $g_ingresso_modo = $this->ingresso_tipo($g_ingresso_modo);

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
function rel_cidade($tp=1)
    {
        switch($tp)
            {
            case '1':
                $sql = "SELECT count(*) as total, ed_cidade FROM `person_endereco 
                            where ed_status = 1 
                            group by ed_cidade";
                echo $sql;
                break;
            }
    }
}
?>
