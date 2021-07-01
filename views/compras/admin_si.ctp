<style>
ul.pestanas {
	float: left;
	width: 100%;
}
ul.pestanas li {
	float: left;
}
ul.pestanas li a {
	text-decoration: none;
	font-size: 14px;
	padding: 5px 10px;
	border: 1px solid #cacaca;
	margin-right: 5px;
	background-color: #cacaca;
	color: #6a6a6a;
	border-top-right-radius: 5px;
	border-top-left-radius: 5px;
	opacity: 0.5;
}
ul.pestanas li a.activo {
	border-bottom: 1px solid #fff;
	/*font-weight: bold;*/
	background-color: #fff;
	opacity: 1;
}
.contenido {
	float: left;
	border: 1px solid #cacaca;
	border-top-right-radius: 10px;
	margin-top: 5px;
	padding: 30px 5px 5px 5px;
	width: 100%;
}
</style>
<?
if (! $compra['Compra']['picking_number'] )
{
	$tab = array('id' => 'tabPickingNumber',
				 'texto' => 'Picking Number',
				 'contenido' => $this->element('admin_compras/admin_si_picking'));
	
}
elseif (! $compra['Compra']['despachado'] )
{
	$tab = array('id' => 'tabDespacho',
				 'texto' => 'Despacho',
				 'contenido' => $this->element('admin_compras/admin_si_despacho'));
}
elseif(! $compra['Compra']['enviado'] )
{
	$tab = array('id' => 'tabEnvio',
				 'texto' => 'Envio',
				 'contenido' => $this->element('admin_compras/admin_si_envio'));
}
else
{
	$tab = array('id' => 'tabEnviado',
				 'texto' => 'Datos de envio',
				 'contenido' => $this->element('admin_compras/admin_si_enviado'));
}
?>
<div class="col02">
	<ul class="pestanas">
		<li><a href="#<?= $tab['id']; ?>" class="activo"><?= $tab['texto']; ?></a></li>
		<li><a href="#tabPrevisualizacion">Previsualización</a></li>
		<li><a href="#tabComentarios">Comentarios</a></li>
	</ul>

	<div class="contenido">
		<div id="<?= $tab['id']; ?>">
			<?= $tab['contenido']; ?>
		</div>
		<div id="tabPrevisualizacion">
			<?= $this->element('admin_compras/admin_si_previsualizacion'); ?>
		</div>
		<div id="tabComentarios">
			<?= $this->element('admin_compras/admin_si_comentarios'); ?>
		</div>
	</div>
</div>
<script>
function activar_pestana() {
	var activo = $('.pestanas li a.activo').attr('href');
	$('.contenido>div').hide();
	$('.contenido>div'+activo).show();
	return false;
}
$('.pestanas a').click(function(e) {
	e.preventDefault();
	var activo = $(this);
	$('.pestanas a').removeClass('activo');
	activo.addClass('activo');
	activar_pestana();
});
activar_pestana();
</script>
