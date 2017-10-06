<?php
if (isset($contato)) {
for ($r=0;$r < count($contato);$r++) {    
?>
<div class="col-md-1 text-right">
	<?php
    $tipo = $contato[$r]['ct_tipo'];
    switch($tipo) {
        case 'T' :
            echo 'Telefone';
            break;
        case 'E' :
            echo 'e-mail';
            break;
    }
	?>
</div>

<div class="col-md-11">
	<?php echo $contato[$r]['ct_contato']; ?>
</div>

<?php } } ?>