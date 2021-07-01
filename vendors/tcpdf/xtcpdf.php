<?php
App::import('Vendor', 'tcpdf/tcpdf');

class XTCPDF extends TCPDF
{
	var $data;
	var $tipo;

	function FooterText($data = null, $tipo = null)
	{
		if ( $data )
			$this->data = $data;
		if ( $tipo )
			$this->tipo = $tipo;
	}

	function Footer()
	{
		$cotizacion	= $this->data['Cliente']['identificador'] . '-' . str_repeat(0, 5 - strlen($this->data['Cotizacion']['id'])) . $this->data['Cotizacion']['id'];
		$pagina		= $this->PageNO() . ' de ' . $this->getAliasNbPages();
		$this->SetFont('helvetica', '', 9);
		$this->SetTextColor(204, 204, 204);
		$this->SetY(-20);
		//$this->Cell(0, 0, 'ANDAIN - DESARROLLO DIGITAL', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->SetY(-15);
		$this->Cell(0, 0, 'CuponWay | www.cuponway.cl', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->SetY(-10);
		$this->Cell(0, 0, 'Página ' . $pagina , 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}
?>