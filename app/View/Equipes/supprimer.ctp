<h1>Supprimer une �quipe</h1>

<p><b>Voulez-vous vraiment supprimer cette �quipe ?</b><br/><br/>
<?=$equipe['VueEquipe']['NomComplet'];?> 
<br/><br/>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id,1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'liste'), array('class' => 'pure-button')); ?>
      
