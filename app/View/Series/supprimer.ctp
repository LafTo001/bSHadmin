<h1>Supprimer un événement</h1>

<p><b>Voulez-vous vraiment supprimer cette série d'événement ?</b><br/><br/>
<?=$serie['VueSerie']['DescriptionSerie'];?> 
<br/><br/>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id,1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'index'), array('class' => 'pure-button')); ?>
      
