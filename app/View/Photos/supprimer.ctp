<h1>Supprimer une photo</h1>

<p><b>Voulez-vous vraiment supprimer cette photo?</b><br/><br/>
<?=$this->Html->image('gallerie/min_'.$photo['Photo']['Filename']);?> 
<br/><br/></p>

<?=$this->Html->link('Oui',array('action' => 'supprimer',$photo['Photo']['id'],1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'album', $photo['Photo']['IdAlbum'],1), array('class' => 'pure-button')); ?>
      
