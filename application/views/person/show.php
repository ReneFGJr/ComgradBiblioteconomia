<div class="row">
    <div class="col-md-10">
        <h3><?php echo $p_nome;?></h3>
        <?php
        for ($r=0;$r < count($acompanhamento);$r++) {
            echo '<span class="btn btn-outline-danger">'.$acompanhamento[$r]['pat_nome'].'</span>';
            echo '&nbsp;';
        }
        ?>
    </div>
    <div class="col-md-2 text-right">
        cracha<br/>
        <div class="btn btn-default"><?php echo $p_cracha;?></div>
    </div>
</div>
<br/>
