<?php
$ac = array('', '', '', '', '', '', '', '', '', '', '', '', '');
if (!isset($pag)) { $pag = 0;
}
$ac[$pag] = 'active';
$socials = new socials;
?>
<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="<?php echo base_url(PATH); ?>"><font color="blue">Comgrad/BIB</font></a>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url(PATH.'bolsas'); ?>">Divulgação de Estágios e Bolsas <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url(PATH.'about'); ?>">Sobre</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url(PATH.'contact'); ?>">Contato</a>
			</li>
			<?php 
			if (perfil("#ADM#USR")) { ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acompanhamento discente </a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
				    <a class="dropdown-item" href="<?php echo base_url(PATH.'persons'); ?>">Estudantes</a>
					<a class="dropdown-item" href="<?php echo base_url(PATH.'persons_list'); ?>">Lista de acompanhados</a>
					<a class="dropdown-item" href="<?php echo base_url(PATH.'import_ROD'); ?>">Lançar acompanhamento</a>
					<a class="dropdown-item" href="<?php echo base_url(PATH.'prerequisito_analise'); ?>">Pre-requisito</a>
				</div>
			</li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Indicadores </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'relatorio/1'); ?>">Total de estudantes</a>
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'relatorio/2'); ?>">Período dos estudantes</a>
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'relatorio/3'); ?>">Tempo de integralização</a>
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'relatorio/6'); ?>">Idade dos estudantes (ativos)</a>                    
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'relatorio/4'); ?>">Bairros (ativos)</a>
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'relatorio/5'); ?>">Estudantes e e-mail</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Campanhas </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'campanhas'); ?>">Campanhas</a>
                </div>
            </li> 
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Gerenciamento </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url(PATH.'pag'); ?>">Importar Arquivos</a>
                </div>
            </li>            
            <?php } ?>
            <li class="nav-item navbar-toggler-right">
                <?php echo $socials -> menu_user(); ?>
            </li>                       			
		</ul>
	</div>
</nav>
