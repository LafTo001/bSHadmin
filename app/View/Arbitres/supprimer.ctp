<h1>Supprimer une équipe</h1>

<p>Voulez-vous vraiment supprimer <?=$arbitre['VueArbitre']['NomComplet'];?> ?<br/><br/>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$id,1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'index'), array('class' => 'pure-button')); ?>
      
