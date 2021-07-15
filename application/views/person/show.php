<?php
$style = '';
if ($p_ativo == 0)
    {
        $style = ' style="color: red;" ';
    }
if ($p_ativo == 9)
    {
        $style = ' style="color: orange;" ';
    }    
?>
<div class="row">
    <div class="col-md-10">
        <h3 <?php echo $style;?>><?php echo $p_nome;?></h3>
        <i><?php 
        for ($r=0;$r < count($graduacao);$r++)
            {
                echo $graduacao[$r]['pc_nome'];
            }
        ?></i>

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

    <?php
    if (strlen($tt_nome) > 0)
        {
            $alt = '<a href="#" class="nopr small" style="color: blue;" onclick="newxy(\''.base_url(PATH.'ajax/tutor/'.$id_p).'\',600,300);">[mudar]</a>';
            echo '<div class="col-md-12">Tutor: <b>'.$tt_nome.'</b> '.$alt.'</div>';
        }
    ?>
</div>
<br/>
