<?php
class comgrads extends CI_model
	{
		function about()
			{
				/* formulário */
				$form = 'Formulário';
				$img = base_url('img/icone/about.png');
				$tela = '
				<div class="col-md-2"><img src="'.$img.'" class="img-responsive"></div>
				<div class="col-md-8">'.$form.'</div>';
				return($tela);
			}
		function contact()
			{
				$img = base_url('img/icone/contact-us.png');
				$tela = '
				<div class="col-md-2"><img src="'.$img.'" class="img-responsive"></div>
				<div class="col-md-8">Sobre</div>';
				return($tela);
			}			
	}
?>
