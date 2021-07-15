<?php
if (count($indicadores[0]) > 0) { $line = $indicadores[0];
    echo '<table width="100%">';
    echo '<tr class="table"><th colspan=3" class="text-center bg-info text-white">Curso</th></tr>'.cr();

    $last_m = $indicadores[0]['i_i12'];
    $ano_1 = round(substr($last_m,0,4));
    $ano_2 = round(substr($last_m,4,1));    
    
    for ($r=0;$r < count($graduacao);$r++)
        {
        $l = $graduacao[$r];
       
        echo '<tr class="table">';
        echo '<td><b>' . $l['pc_nome'] . '</b></td>';
        switch($p_ativo)
            {
                case '0':
                echo '  <td align="right">
                        <span class="btn btn-danger" style="width: 100%;">Inativo</span>
                        </td>';
                break;

                case '1':
                echo '  <td align="right">
                        <span class="btn btn-outline-success" style="width: 100%;">Ativo</span>
                        </td>';
                break;

                case '9':
                echo '  <td align="right">
                        <span class="btn btn-outline-danger" style="width: 100%;">Sem acesso</span>
                        </td>';
                break;                
            }
        
        echo '</tr>'.cr();
        echo '<tr>'.cr();    
        echo '<td class="text-right">' . msg('g_ingresso') . '</td>';        
        echo '<td class="text-center"><b>' . $l['g_ingresso'] . '/'.$l['g_ingresso_sem'].'</b></td>';
        
        
        if ($l['g_afastado']==1)
            {
                echo '<tr>
                        <td colspan=3>
                        <button type="button" class="btn btn-outline-danger" style="width: 100%;">Afastado</button>
                        </td>
                      </tr>'.cr();            
            }
        echo '</tr>';
        
        $a = $ano_1 - $l['g_ingresso'] + 1;
        if ($ano_2 == $l['g_ingresso_sem'])
            { $a = $a + 0.5; }
        echo '<tr>'.cr();    
        echo '<td class="text-right">' . msg('g_integralizacao') . '</td>';        
        echo '<td class="text-center"><b>' . number_format($a,1,',','.').' anos</b></td>';
        echo '</tr>'.cr();
        
        if ($a >= 7)
            {
                echo '<tr>
                        <td colspan=3>
                        <button type="button" class="btn btn-outline-danger" style="width: 100%;">Alerta de Jubilamento</button>
                        </td>
                      </tr>'.cr();    
            }
        }
    }
    
    echo '<tr><td class="text-right">'.msg('i_i12').'</td>';
    echo '<td class="text-center"><b>'.$last_m.'</b></td></tr>'.cr();
    
    echo '</table>' . cr();
?>