<?
if (isset($this->params['controller']) && isset($this->params['action']))
{
	$sku = $idCategory = '';
	if ($this->params['controller'] == 'productos')
	{
		if (isset($producto['Producto']['codigo_completo']) && $producto['Producto']['codigo_completo'])
			$sku = $producto['Producto']['codigo_completo'];
		if ($this->params['action'] == 'inicio')
		{
			$idCategory = 'inicio';
		}
		elseif ($this->params['action'] == 'catalogo')
		{
			if (isset($this->params['pass'][0]) && $this->params['pass'][0])
				$idCategory = $this->params['pass'][0];
		}
		elseif ($this->params['action'] == 'outlet')
		{
			$idCategory = 'outlet';
			if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
				$idCategory.= '-'.$this->params['url']['categoria'];
		}
		elseif ($this->params['action'] == 'escolar')
		{
			$idCategory = 'escolar';
			if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
				$idCategory.= '-'.$this->params['url']['categoria'];
		}
		elseif ($this->params['action'] == 'ropa')
		{
			$idCategory = 'ropa';
			if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
				$idCategory.= '-'.$this->params['url']['categoria'];
		}
		elseif ($this->params['action'] == 'view')
		{
			if (isset($producto['Producto']['outlet']) && $producto['Producto']['outlet'])
				$idCategory.= 'outlet';
			elseif (isset($producto['Producto']['escolar']) && $producto['Producto']['escolar'])
				$idCategory.= 'escolar';
			if (isset($producto['CategoriaPadre']['slug']) && $producto['CategoriaPadre']['slug'])
			{
				$idCategory.= (($idCategory) ? '-':'') .$producto['CategoriaPadre']['slug'];
				if ($producto['CategoriaPadre']['id'] == 58 && isset($producto['Categoria']['slug']) && $producto['Categoria']['slug'])
				{
					$idCategory.= '-'.$producto['Categoria']['slug'];
				}
			}
			elseif (isset($producto['Categoria']['slug']) && $producto['Categoria']['slug'])
			{
				$idCategory.= (($idCategory) ? '-':'') .$producto['Categoria']['slug'];
			}
		}
	}
	echo '<!-- Begin: Groovinads Behavioral-->
	<!--End: Groovinads Behavioral -->';
	if ($this->params['controller'] == 'productos' && $this->params['action'] == 'felicidades')
	{
		$transaction_value = 0;
		if (isset($total) && $total)
			$transaction_value = $total;
		$your_id_transaction = 0;
		if (isset($pago['Pago']['compra_id']) && $pago['Pago']['compra_id'])
			$your_id_transaction = $pago['Pago']['compra_id'];
		echo '<!-- Begin: Groovinads Venta -->
		<img src="https://ssl01.groovinads.com/grv/track/px.os?fgimg=1&idpixel=245&goalvalue='.$transaction_value.'&idtransaction='.$your_id_transaction.'" height="1" width=1>
		<!--End: Groovinads Venta --> ';
	}
} 
?>

<!-- Facebook Pixel Code -->

<script>

!function(f,b,e,v,n,t,s)

{if(f.fbq)return;n=f.fbq=function(){n.callMethod?

n.callMethod.apply(n,arguments):n.queue.push(arguments)};

if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';

n.queue=[];t=b.createElement(e);t.async=!0;

t.src=v;s=b.getElementsByTagName(e)[0];

s.parentNode.insertBefore(t,s)}(window,document,'script',

'https://connect.facebook.net/en_US/fbevents.js');


fbq('init', '2902239849790459'); 

fbq('track', 'PageView');

</script>

<noscript>

<img height="1" width="1" 

src="https://www.facebook.com/tr?id=2902239849790459&ev=PageView

&noscript=1"/>

</noscript>

<!-- End Facebook Pixel Code -->
<script language='JavaScript1.1' async src='//pixel.mathtag.com/event/js?mt_id=1438754&mt_adid=229641&mt_exem=&mt_excl=&v1=&v2=&v3=&s1=&s2=&s3='></script>
