<?php
class pags extends CI_model
	{
	function inport($file='')
		{
			$f = load_file_local($file);
			$f = utf8_encode($f);
			
			$f = troca($f,';','£');
			$f = troca($f,'<br/>','£');
			$f = troca($f,'>','£');
			$f = troca($f,'<br>','£');
			$f = troca($f,'\n','£');
			
			
			$f = troca($f,chr(13),';');
			$f = troca($f,chr(10),'');
			
			$ln = splitx(';',$f.';');
			
			for ($r=0;$r < count($ln);$r++)
				{
					$lns = $ln[$r];
					$lns = troca($lns,'£',';');
					$lns = splitx(';',$lns.';');
					
					if (isset($lns[13]))
						{
							$nome = $lns[0];
							$cracha = strzero($lns[1],8);
							$nasc = $lns[2];
							$curso = $lns[3];
							$curso2 = $lns[4];
							$cpf = $lns[5];
							$rg = $lns[6];
							$endereco = $lns[7];
							$bairro = $lns[8];
							$cep = trim(substr($lns[9],0,strpos($lns[9],'-')));
							$cidade = trim(substr($lns[9],strpos($lns[9],'-')+1,strlen($lns[9])));
							$telefone = $lns[10];
							$email = $lns[11];
							$es_ano = $lns[12];
							$ingresso = $lns[13];
							$diplomacao = $lns[14];
							$cred = $lns[15];
							$cred_obe = $lns[16];
							$cred_obr = $lns[17];
							$cred_elt = $lns[18];
							$cred_com = $lns[19];
							$cred_tim = $lns[20];
							$cred_i1 = $lns[21];
							$cred_i2 = $lns[22];
							$cred_i3 = $lns[23];
							$cred_i4 = $lns[24];
							$cred_i5 = $lns[25];
							$cred_i6 = $lns[26];
							$cred_ult_let = $lns[27];
							$cred_matr = $lns[28];
							$cred_inte = $lns[29];
							$cred_ff = $lns[30];
							if (isset($lns[31]))
							{
								$cred_mod = $lns[31];
							} else {
								$cred_mod = '';
							}
							
							echo $nome.'.'.$cred_mod.'</br>';
							
							
						}
				}
				print_r($lns);
		}	
	}
?>
