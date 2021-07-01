<table width="575" border="0" cellspacing="0">
	<tr>
		<td>
			<img alt="" src="http://www.skechers.cl/img/mailing/mailing_catss_despacho.png" />
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td colspan="6" style="text-align:center;">
						<h2><?= (isset($textos['titulo']) && $textos['titulo'])? $textos['titulo']:'DEVOLUCIÓN DE PRODUCTO VENTA ONLINE'; ?></h2>
					</td>
				</tr>
				<tr>
					<td colspan="6" style="text-align:justufied;">
						<p>
							<?= (isset($textos['info']) && $textos['info'])? $textos['info']:''; ?>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;font-weight:bold;border: 1px solid #444;">
						N° DE COMPRA
					</td>
					<td colspan="2" style="text-align:center;font-weight:bold;border: 1px solid #444;">
						N° DECOLUCION (RA)
					</td>
					<td colspan="2" style="text-align:center;font-weight:bold;border: 1px solid #444;">
						NOMBRE CLIENTE
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;border: 1px solid #444;">
						<?= $compra['Compra']['id']; ?>
					</td>
					<td colspan="2" style="text-align:center;border: 1px solid #444;">
						<?= $compra['Devolucion']['codigo']; ?>
					</td>
					<td colspan="2" style="text-align:center;border: 1px solid #444;">
						<?= $compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno'].' '.$compra['Usuario']['apellido_materno']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:center;font-weight:bold;border: 1px solid #444;">
						DIRECCION ENTREGA
					</td>
					<td colspan="3" style="text-align:center;font-weight:bold;border: 1px solid #444;">
						ATENCION A
					</td>
				</tr>
				<tr>
					<td colspan="3" style="border: 1px solid #444;">
						AVDA. EL PARQUE #1307, PARQUE 8.<br />
						MODULO 3.<br />
						PARQUE ENEA 2. PUDAHUEL
					</td>
					<td colspan="3" style="border: 1px solid #444;">
						SR. ALVARO RIVAS
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">
						Una vez recepcionado el producto en nuestras dependencias se evaluará el cumplimiento de las condiciones para realizar la decolución solicitada. Si ésta es Aprobada el reembolso de dinero se realizara en la misma forma de pago original.<br /><br />
						<b>IMPORTANTE:</b> Al momento de entregar el producto a Chilexpress exige el comprobante del retiro.<br /><br />
						Duras y consultas, favor contactar al mail <b><u>ventas@skechers.com</u></b> o al fono <b>2 342 93 99</b>.
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			Atentamente,
			<br><br>
			<b>Servicio al Cliente</b>
			<br>
			<b>Skechers Chile</b>
			<br>
			<img alt="" src="http://www.skechers.cl/img/mailing/servicio_cliente.png" />
		</td>
	</tr>
</table>