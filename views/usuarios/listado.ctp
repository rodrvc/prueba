
<?php
echo 'Name,LastName,email,gender,Gen_Comp,rut,F_Nac,Comuna,Region';
	echo '<br>';
	foreach ($usuarios_arr as $usuario)
	{
		echo $usuario['nombre'].','.$usuario['apellido_paterno'].','.$usuario['email'].','.$usuario['sexo'].','.$usuario['categorias'].',,'.$usuario['fecha_nacimiento'].','.$usuario['comuna'].','.$usuario['region'].'<br>';
		

		
	}
?>