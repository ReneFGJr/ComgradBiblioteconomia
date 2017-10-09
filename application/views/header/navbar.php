<?php
$ac = array('', '', '', '', '', '', '', '', '', '', '', '', '');
if (!isset($pag)) { $pag = 0;
}
$ac[$pag] = 'active';
?>
<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="<?php echo base_url('index.php/main'); ?>"><font color="blue">Comgrad/BIB</font></a>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url('index.php/bolsas'); ?>">Divulgação de Estágios e Bolsas <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/about'); ?>">Sobre</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/contact'); ?>">Contato</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acompanhamento discente </a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
				    <a class="dropdown-item" href="<?php echo base_url('index.php/main/persons'); ?>">Estudantes</a>
					<a class="dropdown-item" href="<?php echo base_url('index.php/main/persons_list'); ?>">Lista de acompanhados</a>
					<a class="dropdown-item" href="<?php echo base_url('index.php/main/import_ROD'); ?>">Lançar acompanhamento</a>
				</div>
			</li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Indicadores </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/relatorio'); ?>">Total de estudantes</a>
                </div>
            </li>			
		</ul>
	</div>
</nav>
