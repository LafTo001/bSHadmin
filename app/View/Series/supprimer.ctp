<h1>Supprimer un �v�nement</h1>

<p><b>Voulez-vous vraiment supprimer cette s�rie d'�v�nement ?</b><br/><br/>
<?=$serie['VueSerie']['DescriptionSerie'];?> 
<br/><br/>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id,1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'index'), array('class' => 'pure-button')); ?>
      
