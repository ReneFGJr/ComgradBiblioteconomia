<?php
class pags extends CI_model {
    var $tabela = 'person';
    
    function row($obj) {
        $obj -> fd = array('id_p', 'p_nome', 'p_cracha');
        $obj -> lb = array('ID', 'Nome', 'Cracha');
        $obj -> mk = array('', 'L', 'C', 'C', 'C', 'C');
        return ($obj);
    }

    function le_cracha($id)
        {
            $id = strzero(sonumero($id),8);
            $sql ="select * from person where p_cracha = '$id' ";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            if (count($rlt) > 0)
                {
                    $line = $rlt[0];
                    return($line['id_p']);
                } else {
                    return(0);
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

        if (round($p_cpf) == 0) {
            echo 'ops CPF inválido de ' . $p_nome . ' CPF:' . $p_cpf;
            exit ;
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
        $f = troca($f, '<br>', chr(13));
        $f = troca($f, '<br/>', chr(13));
        $f = troca($f, '; ;', ';-;');

        $f = utf8_encode($f);

        $f = troca($f, ';', '£');
        $f = troca($f, "'", "´");
        
        $f = troca($f, '>', '£');
        
        $f = troca($f, '\n', '£');
        $f = troca($f, '££', '£0£');
        $f = troca($f, '££', '£0£');
        $f = troca($f, '££', '£0£');

        $f = troca($f, chr(13), ';');
        $f = troca($f, chr(10), '');

        $ln = splitx(';', $f . ';');
        $sx .= '<pre>';
        for ($r = 0; $r < count($ln); $r++) {
            $lns = $ln[$r];
            $lns = troca($lns, '£', ';');
            $lns = troca($lns, ',', '.');
            $lns = splitx(';', $lns . ';');

            if (count($lns) > 30) {
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
                if (strlen($p_nome) > 5) {
                    $id_us = $this -> user_add($p_nome, $p_cracha, $p_nasc, $p_cpf, $p_rg);
                    $sx .= ' ' . $id_us . '. ';
                    /* curso */
                    $ok = $this -> curso($id_us, $curso, $curso2, $es_ano, $ingresso, $diplomacao, $afastado, $cred_mod);
                    if ($ok==(-1))
                        {
                            echo 'ERRO NO CURSO ('.$curso.') ('.$curso2.')<br>';
                            
                            print_r($lns);
                            exit;
                        }
                    $sx .= $p_nome . '.' . $cred_mod . '</br>';

                    /* endereco */
                    $this -> endereco($id_us, $endereco, $bairro, $cep, $cidade);

                    /* contato */
                    $this -> contato($id_us, 'T', $telefone);
                    $this -> contato($id_us, 'E', $email);
                    
                    /* indicadores */
                    $this -> indicadores($id_us, $cred_ult_let, $cred_obe, $cred_obr, $cred_elt, $cred_com,
                            $cred_tim, $cred_i1, $cred_i2, $cred_i3, $cred_i4, $cred_i5, $cred_i6,
                            $cred_ult_let, $cred_matr, $cred_inte, $cred_ff );
                }
            } else {
                if (count($lns) > 10)
                    {
                        print_r($lns);
                        echo '<hr>';
                    }
            }
        }
        $sx .= '</pre>';
        return ($sx);
    }

    function indicadores($id_us, $cred_ult_let, $i1 , $i2 , $i3 , $i4 , $i5 , $i6 , $i7 
                            , $i8='' , $i9='' , $i10='' , $i11='' , $i12='' 
                            , $i13='0' , $i14='0' , $i15='0' , $i16='0' 
                            , $i17='0' , $i18='0' , $i19='0' , $i20='0' , $i21='0' , $i22='0' )
                {
                    $sql = "select * from person_indicadores 
                                where i_person= $id_us
                                and i_ano = '$cred_ult_let' ";  
                    $rlt = $this->db->query($sql);
                    $rlt = $rlt->result_array();
                    
                    if (count($rlt) == 0)
                        {
                            $sql = "insert into person_indicadores
                                        (i_person, i_ano, 
                                        i_i1, i_i2, i_i3, i_i4, i_i5, 
                                        i_i6, i_i7, i_i8, i_i9, i_i10, 
                                        i_i11, i_i12, i_i13, i_i14, i_i15, 
                                        i_i16, i_i17, i_i18, i_i19, i_i20,
                                        i_i21, i_i22 
                                    ) values (
                                        $id_us, '$cred_ult_let',
                                        $i1, $i2, $i3, $i4, $i5, 
                                        $i6, $i7, $i8, $i9, $i10, 
                                        $i11, '$i12', $i13, $i14, $i15, 
                                        $i16, $i17, $i18, $i19, $i20,
                                        $i21, $i22                                         
                                    )";
                            $rlt = $this -> db -> query($sql);
                        }  
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
                return(-1);
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
                echo 'OPS Curso:' . $curso;
                exit ;
        }
        return ($id);
    }

    function ingresso_tipo($tp) {
        switch ($tp) {
            case '0':
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
            case 'Transferência Interna - Aluno Convênio':
                $id = 7;
                break;
            case 'Transferência Voluntária':
                $id = 8;
                break;
            default :
                $id = 0;
                echo 'OPS - Entrada: ' . $tp;
                exit ;
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
            if (strlen($dado) > 3)
            {
            $sql = "insert into person_contato
                        (ct_person, ct_tipo, ct_contato)
                        values
                        ($id_us,'$tipo','$dado')";
            $rlt = $this -> db -> query($sql);
            }
        }
    }

    function endereco($id_us, $endereco, $bairro, $cep, $cidade) {
        $cidade = uppercase($cidade);
        $estado = '';
        if (strpos($cidade, '-')) {
            $estado = trim(substr($cidade, strpos($cidade, '-') + 1, 5));
            $cidade = substr($cidade, 0, strpos($cidade, '-'));
        }
        $sql = "select * from person_endereco
                        where ed_person = $id_us ";
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
        if ($afastado == -1)
            {
                return(-1);
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

    function list_acompanhamento($limit = 20,$ord='id_rod desc')
        {
            $sql = "select * from person_rod
                        INNER JOIN person ON rod_person = id_p 
                        INNER JOIN person_acompanhamento_tipo ON id_pat = rod_tipo
                        order by $ord
                        limit $limit";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            
            $sx = '<table width="100%">';
            $sx .= '<tr><th>#</th><th>Cracha</th><th>Nome</th><th>Programa</th></tr>'.cr();
            
            $xname= "";
            $id = 0;
            for ($r=0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    $name = trim($line['p_cracha']);
                    $sx .= '<tr>';
                    
                    if ($xname == $name)
                        {                            
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
                            
                            $link = '<a href="'.base_url('index.php/main/person/'.$line['rod_person'].'/'.md5($line['rod_person'])).'">';
                            $sx .= '<td width="10%" style="border-top: 1px solid #808080;">';
                            $sx .= $link.$name.'</a>';
                            $sx .= '</td>';
                                                                    
                            $sx .= '<td width="60%" style="border-top: 1px solid #808080;">';
                            $sx .= $link.$line['p_nome'].'</a>';
                            $sx .= '</td>';
                        }
                    
                    $sx .= '<td width="28%" style="border-top: 1px solid #808080;">';
                    $sx .= $line['pat_nome'];
                    $sx .= '</td>';
                    
                    $sx .= '</tr>';
                }
            $sx .= '</table>';
            return($sx);
        }

    function incluir_acompanhamento($c,$t)
        {
            $id_us = $this->le_cracha($c);
            $sql = "select * from person_rod 
                        where rod_person = $id_us and rod_tipo = $t
                        order by rod_update desc ";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            
            if (count($rlt) == 0)
                {
                    $sql = "insert into person_rod
                            (rod_person, rod_tipo)
                            values
                            ($id_us, $t)";
                    $rlt = $this->db->query($sql);
                    return(1);
                }
           return(0);
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
    
    function rel_alunos_matriculados()
        {
            $sql = "select * from (
                        SELECT count(*) as total, i_ano 
                        FROM `person_indicadores` 
                        WHERE i_ano <> '-'                        
                        group by i_ano 
                        ) as tabela where total > 20
                        order by i_ano";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            $data1 = '';
            $data2 = '';
            for ($r=0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    if (strlen($data1) > 0)
                        {
                            $data1 .= ', ';
                            $data2 .= ', ';
                        }
                    $data1 .= '"'.$line['i_ano'].'"';
                    $data2 .= ''.$line['total'].'';
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
                        categories: [ '.$data1.' ],
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
                        data: ['.$data2.']
                
                    }]
                }); 
            </script>
            ';

            return($sx);
        }

}
?>
