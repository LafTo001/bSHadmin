<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Photos',array('action' => 'index'));?></th>
            <th width="25%"><?=$this->Html->link('Albums',array('action' => 'albums'));?></th>
            <th width="25%"><?=$this->Html->link('Créer un album',array('action' => 'ajout_album'));?></th>
            <th width="25%"><?=$this->Html->link('Ajouter une photo',array('action' => 'ajouter'));?></th>
        </tr>
    </table>
</div>

<h1>Ajouter un album</h1>

<?php echo $this->Form->create('Album',array('class' => 'pure-form pure-form-stacked')); ?>

  <div class="pure-g">
    <div class="pure-u-1">
        <?=$this->Form->input('NomAlbum',array(
            'label' => "Nom de l'album",
            'size' => '35',
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('Description',array(
            'label' => 'Description',
            'size' => '80',
            'required' => true
        )); ?>
    </div>
  </div>
  <br/><br/>
  
  <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
  <?=$this->Html->link('Annuler',array('action' => 'index'), array('class' => 'pure-button')); ?>
<?=$this->Form->end(); ?>
      
