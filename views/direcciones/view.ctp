<?
//Configure::write('debug', 1);
//pr($direccion); ?>
<h3><?= $direccion['Direccion']['nombre']; ?></h3>
<p><?= $direccion['Direccion']['calle'].' '.$direccion['Direccion']['numero']; ?></p>
<p>Comuna <?= $direccion['Comuna']['nombre']; ?></p>
<p><?= $direccion['Region']['nombre']; ?></p>
<p>Codigo postal: <?= $direccion['Direccion']['codigo_postal']; ?></p>
<p>Teléfono: <?= $direccion['Direccion']['telefono'].' - '.$direccion['Direccion']['celular']; ?></p>
<p>Otras Indicaciones: <?= $direccion['Direccion']['otras_indicaciones']; ?></p>