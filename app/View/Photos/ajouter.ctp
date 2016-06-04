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

<h1>Ajouter une nouvelle photo</h1>

<?= $this->Form->create('Photo',array('type' => 'file')); ?>
<div class="pure-form pure-form-stacked pure-g">
    
    <div class="pure-u-1-3">
        <?=$this->Form->input('files.', array(
                'label' => 'Téléversement des photos',
                'type' => 'file', 'multiple'
        ));?>
    </div>

    <div class="pure-u-2-3">
        <?=$this->Form->input('IdAlbum',array(
                'label' => 'Album',
                'options' => $albums,
                'empty' => array(0 => '-- Aucun album sélectionné --'),
                'selected' => $idAlbum
        )); ?>
    </div>
    
    <div class="pure-u-1-1">
        <?=$this->Form->input('Description',array(
                'label' => 'Description de la photo',
                'size' => '80'
        )); ?>
    </div>
    
</div>   
<br/>
    
<button type="submit" class="pure-button pure-button-primary" value="Submit">Enregister</button>
<?=$this->Html->link('Annuler',array('controller' => 'photos', 'action' => 'index'), array('class' => 'pure-button'));?>
 
<?= $this->Form->end(); ?>
      
