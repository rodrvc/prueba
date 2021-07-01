<?= $this->element('menu'); ?>
<? if ( ! isset($authUser) ) : ?>
<?= $this->element('inscribite_facebook'); ?>
<? elseif($fbUser['creditos'] <= 0) :?>
<?= $this->element('encuentra_facebook'); ?>
<? else : ?>
<?= $this->element('juega_facebook'); ?>
<? endif; ?>
<?= $this->element('estrellas_facebook'); ?>
<?= $this->element('comoparticipa_facebook'); ?>


