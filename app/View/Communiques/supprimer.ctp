<h1>Supprimer un communiqué</h1>

<p><b>Voulez-vous vraiment supprimer ce communiqué ?</b><br/><br/>
Titre : <?=$titre;?> 
<br/><br/></p>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id,1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non','/communiques/', array('class' => 'pure-button')); ?>
      
