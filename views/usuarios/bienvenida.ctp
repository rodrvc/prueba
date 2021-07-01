<!--
Start of DoubleClick Floodlight Tag: Please do not remove
Activity name of this tag: Pixel Conversion Skechers
URL of the webpage where the tag is expected to be placed: http://store.skechers-chile.cl/
This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
Creation Date: 08/19/2014
-->
<script type='text/javascript'>
var axel = Math.random() + '';
var a = axel * 10000000000000;
document.write('<iframe src="https://4493630.fls.doubleclick.net/activityi;src=4493630;type=invmedia;cat=Cj8edNSJ;ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');
</script>
<noscript>
<iframe src="https://4493630.fls.doubleclick.net/activityi;src=4493630;type=invmedia;cat=Cj8edNSJ;ord=1?" width="1" height="1" frameborder="0" style="display:none"></iframe>
</noscript>
<!-- End of DoubleClick Floodlight Tag: Please do not remove -->
<script>
<?
	$url = array('controller' => 'productos', 'action' => 'inicio');
	if ( $this->Session->check('Logueo.estado') )
	{
		$url = array('controller' => $this->Session->read('Logueo.controller'), 'action' => $this->Session->read('Logueo.action'));
	}
?>
location.href = "<?= $this->Html->url($url); ?>";
</script>