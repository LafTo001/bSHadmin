<h1>Supprimer un communiqu�</h1>

<p><b>Voulez-vous vraiment supprimer ce communiqu� ?</b><br/><br/>
Titre : <?=$titre;?> 
<br/><br/></p>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id,1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non','/communiques/', array('class' => 'pure-button')); ?>
      
