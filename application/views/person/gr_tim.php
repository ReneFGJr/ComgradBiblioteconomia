<?php
/*
echo '<pre>';
echo '971eb0c0a88edcd829ae0405c5f20c49<br>';
echo md5('18001');
echo '<br>' . md5('00018001');
echo '</pre>';
echo '<hr>';
*/
$ind = 'i_i8';
$mul = 10;
echo '<br/>';

$sx = '<table width="100%" border=0>';
$sx .= '<tr>
        <th>ano</th>
        <th colspan=2 class="text-center">TIM</th>
        <th colspan=2 class="text-center">Sem.Atual</th>
        <th colspan=2 class="text-center">Conceitos</th>
        <th colspan=2 class="text-center">Reprovações</th>
        </tr>';
for ($r = 0; $r < count($indicadores); $r++) {
    $line = $indicadores[$r];

    $sx .= '<tr class="text-center">';
    $sx .= '<td width="10%">';
    $sx .= $line['i_ano'];
    $sx .= '</td>';

    $ind = 'i_i5';
    $sx .= '<td align="right" width="10%">';
    $sx .= $line[$ind];
    $sx .= '</td>';

    $sz = round($line[$ind] * 3);
    $sx .= '<td align="left" width="10%">';
    $sx .= '<div style="background-color: #8080AA; height: 18px; width: ' . $sz . 'px;"></div>';
    $sx .= '</td>';

    $ind = 'i_i6';
    $sx .= '<td align="right" width="10%">';
    $sx .= $line[$ind];
    $sx .= '</td>';

    $sz = round($line[$ind] * 10);
    $sx .= '<td align="left" width="10%">';
    $sx .= '<div style="background-color: #8080AA; height: 18px; width: ' . $sz . 'px;"></div>';
    $sx .= '</td>';

    $ind = 'i_i8';
    $sx .= '<td align="right" width="10%">';
    $sx .= $line[$ind];
    $sx .= '</td>';

    $sz = round($line[$ind]*10);
    $sx .= '<td align="left" width="10%">';
    $sx .= '<div style="background-color: #8080AA; height: 18px; width: ' . $sz . 'px;"></div>';
    $sx .= '</td>';

    $ind = 'i_i9';
    $sx .= '<td align="right" width="10%">';
    $sx .= $line[$ind];
    $sx .= '</td>';

    $sz = round($line[$ind]*10);
    $sx .= '<td align="left" width="10%">';
    $sx .= '<div style="background-color: #8080AA; height: 18px; width: ' . $sz . 'px;"></div>';
    $sx .= '</td>';

    $sx .= '</tr>';
}
$sx .= '</table>';
echo $sx;
?>
<br><br><br>
<h5>RESOLUÇÃO Nº 09/2003</h5>
<table style="font-size: 70%;">
	<tr>
		<td>I1</td>
		<td>expressa a posição dos discentes na seriação
		aconselhada do curso, ordenando-os de forma decrescente a partir dos valores
		atribuídos.</td>
	</tr>

	<tr>
		<td>I1</td>
		<td>expressa a posição dos discentes na seriação
		aconselhada do curso, ordenando-os de forma decrescente a partir dos valores
		atribuídos.</td>
	</tr>

	<tr>
		<td>I2</td>
		<td>diferencia os discentes conforme o grupo ou subgrupo
ao qual pertencem, ordenando-os de forma decrescente a partir dos valores atribuídos.
</td>
	</tr>

	<tr>
		<td>I3</td>
		<td>média harmônica dos valores atribuídos aos
conceitos obtidos em todas as disciplinas do seu curso, os quais correspondem a 10
(dez) para conceito A, 8 (oito) para conceito B, 6 (seis) para conceito C, 3 (três) para
conceito D, 2 (dois) para disciplinas trancadas ou canceladas e 1 (um) para conceito
FF. Os discentes são ordenados de forma decrescente.</td>
	</tr>

	<tr>
		<td>I4</td>
		<td> número de reprovações do discente nos dois últimos
semestres letivos em que esteve regularmente matriculado no seu curso atual. Os
discentes são ordenados de forma crescente.</td>
	</tr>

	<tr>
		<td>I5</td>
		<td>indica o quociente entre o argumento padronizado de
concorrência obtido pelo discente no processo seletivo que o classificou para ingresso
na Universidade e o menor argumento de concorrência do candidato classificado em
primeira chamada com ingresso no mesmo ano e no mesmo processo seletivo.</td>
	</tr>
</table>