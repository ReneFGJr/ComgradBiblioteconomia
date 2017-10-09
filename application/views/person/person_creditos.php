<?php
if (count($indicadores[0]) > 0) { $line = $indicadores[0];
    echo '<table width="100%">';
    echo '<tr class="table"><th colspan=2" class="text-center bg-info text-white">Créditos</th></tr>'.cr();

    $v = array('i_i1', 'i_i2', 'i_i3','i_i4','i_i13','i_i14','i_i15');
    $m = array(0,0,0,0,0,0,0);
    for ($r = 0; $r < count($v); $r++) {
        $vlr = $line[$v[$r]];
        if ($m[$r] > 0)
            {
                $vlr = number_format($vlr,$m[$r],',','.');
            }
        echo '<tr>';
        echo '<td align="right" width="70%">' . msg($v[$r]) . '</td>';
        echo '<td width="30%" class="text-center"><b>' . $vlr . '</b></td>';
        echo '</tr>';
    }
    echo '</table>' . cr();
}
?>