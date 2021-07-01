<?
// ejemplo: 
// $lb_promo = array(
// 	'img' => 'imagen.jpg',
// 	'url' => 'http://www.google.cl'
//	'limit' => '2015-11-27 23:59:59'
// );
$lb_promo = false;
if ($this->params['controller'] == 'productos' && $this->params['action'] == 'inicio')
{
	$lb_promo = array(
		'img' => 'lightboxblackfriday.jpg',
		'url' => false,
		'limit' => '2015-11-27 23:59:59'
	);
}
elseif ($this->params['controller'] == 'productos' && $this->params['action'] == 'ropa')
{
	$lb_promo = array(
		'img' => 'lightboxblackfriday.jpg',
		'url' => false,
		'limit' => '2015-11-27 23:59:59'
	);
	if ($this->Session->check('LbPromo') && $this->Session->read('LbPromo') == $lb_promo['img'])
	{
		$lb_promo = false;
	}
}

if ($lb_promo && isset($lb_promo['img']))
{
	if (! file_exists($_SERVER['DOCUMENT_ROOT'].$this->webroot.'webroot/img/lb_promo/'.$lb_promo['img']))
	{
		$lb_promo = false;
	}
	if (isset($lb_promo['limit']) && $timeLimit = strtotime($lb_promo['limit']))
	{
		if (strtotime(date('Y-m-d H:i:s')) > $timeLimit)
		{
			$lb_promo = false;
		}
	}
}
else
{
	$lb_promo = false;
}
?>
<? if ($lb_promo && isset($lb_promo['img']) && $lb_promo['img']) : ?>
	<style>
	#modalPromo > .modal-dialog {
		width: 75% !important;
	}
	#modalPromo .modal-dialog > .modal-content {
		background-color: rgba(33,33,33,0.3);	
	}
	#modalPromo .modal-dialog > .modal-content > .modal-body {
		padding: 20px !important;
	}
	#modalPromo .modal-dialog > .modal-content > .modal-body > .close {
		position: absolute;
		right: 0px;
		top: 0px;
		/*color: #FFF;*/
	}
	</style>
	<script>
	$(document).ready(function() {
		$('#modalPromo').modal('show');
		$(document).on('click','#modalPromo a[rel="closeModal"]',function(e) {
			e.preventDefault();
			$('#modalPromo').modal('hide');
		});
	});
	</script>
	<div id="modalPromo" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
					<?
					if (isset($lb_promo['url']) && $lb_promo['url'])
					{
						if ($lb_promo['url'] == 'close')
						{
							echo '<a href="#" rel="closeModal"><img src="'.$this->Html->url('/img/lb_promo/'.$lb_promo['img']).'" width="100%" /></a>';
						}
						else
						{
							echo '<a href="'.$lb_promo['url'].'"><img src="'.$this->Html->url('/img/lb_promo/'.$lb_promo['img']).'" width="100%" /></a>';
						}
					}
					else
					{
						echo '<img src="'.$this->Html->url('/img/lb_promo/'.$lb_promo['img']).'" width="100%" />';
					}
					?>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<? endif; ?>