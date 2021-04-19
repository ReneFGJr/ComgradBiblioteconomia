<?php
if (count($indicadores[0]) > 0) { $line = $indicadores[0];
    echo '<table width="100%">';
    echo '<tr class="table"><th colspan=2" class="text-center bg-info text-white">Contato</th></tr>'.cr();

    $tpx = '';
    for ($r = 0; $r < count($contato); $r++) {
        $line = $contato[$r];
        $tp = $line['ct_tipo'];
        $vlr = $line['ct_contato'];
        
        if ($tp != $tpx)
            {
                echo '<tr>';
                echo '<td>' .msg('contato_'.$tp) . '</td>';
                echo '</tr>';
                $tpx = $tp;                
            }
            
        if ($tp == 'T')
            {
                $vlr = sonumero($vlr);
                if (strlen($vlr) > 8)
                    {
                        $vlr = '('.substr($vlr,0,2).') '.substr($vlr,2,4).'-'.substr($vlr,6,4);
                    }
            }
        echo '<tr>';
        $link = '<a href="#" onclick="newxy(\''.base_url(PATH.'contact_ed/'.$line['id_ct']).'\',800,600);">';
        $linka = '</a>';

        echo '<td class="text-center"><b>' .  $link.$vlr . $linka. '</b></td>';
        echo '</tr>';
    }
    echo '</table>' . cr();
}
?>