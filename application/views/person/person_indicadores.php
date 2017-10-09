<?php
if (count($indicadores[0]) > 0) { $line = $indicadores[0];
    echo '<table width="100%">';
    echo '<tr class="table"><th colspan=2" class="text-center bg-info text-white">Indicadores</th></tr>'.cr();
    $v = array('i_i5', 'i_i6', 'i_i7','i_i8', 'i_i9', 'i_i10', 'i_i11');
    $m = array(2,0,0,2,2,2,'','','','','','','','','','');
    for ($r = 0; $r < count($v); $r++) {
        $vlr = $line[$v[$r]];
        if ($m[$r] > 0)
            {
                $vlr = number_format($vlr,$m[$r],',','.');
            }
        echo '<tr>';
        echo '<td width="50%" class="text-center">' . msg($v[$r]) . '</td>';
        echo '<td width="50%" class="text-center"><b>' . $vlr . '</b></td>';
        echo '</tr>';
    }
    echo '</table>' . cr();
}
?>