<h1>Supprimer un �v�nement</h1>

<? if($id > 0) ?>
<p><b>Voulez-vous vraiment supprimer cet �v�nement ?</b><br/><br/>
<?=$event['VueEvenement']['DateEvenement'];?> de 
<?=$event['VueEvenement']['DebutEvenement'];?> � 
<?=$event['VueEvenement']['FinEvenement'];?> sur 
<?=$event['VueEvenement']['NomTerrain'];?> &nbsp;
<br/><br/>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id, 1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'reservation',$id), array('class' => 'pure-button')); ?>
      
