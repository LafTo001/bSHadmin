<h1>Éditer les informations de <?=$this->request->data['Terrain']['NomTerrain']; ?></h1>

<?php echo $this->Form->create('Terrain',array('class' => 'pure-form pure-form-stacked')); ?>

<fieldset>
    <legend>Coordonnées</legend>
    <div class="pure-g">
      <div class="pure-u-1-1">
        <?=$this->Form->input('Organisme',array(
            'size' => '40'
        )); ?>
      </div>
        
      <div class="pure-u-1-2">
        <?=$this->Form->input('Adresse',array(
            'size' => '50'
        )); ?>
      </div>
        
      <div class="pure-u-1-2">
        <?=$this->Form->input('Ville',array(
            'size' => '40'
        )); ?>
      </div>
        
      <div class="pure-u-1-4">
        <?=$this->Form->input('Telephone',array(
            'size' => '15'
        )); ?>
      </div>
    </div>
</fieldset>

<fieldset>
    <legend>Responsable</legend>
    <div class="pure-g">
      <div class="pure-u-1-2">
        <?=$this->Form->input('Responsable',array(
            'label' => 'Coordonnateur',
            'size' => '40'
        )); ?>
      </div>
        
      <div class="pure-u-1-2">
        <?=$this->Form->input('Courriel',array(
            'size' => '40'
        )); ?>
      </div>
        
      <div class="pure-u-1-2">
        <?=$this->Form->input('Adjoint',array(
            'size' => '40'
        )); ?>
      </div>
        
      <div class="pure-u-1-4">
        <?=$this->Form->input('CourrielAdjoint',array(
            'label' => 'Courriel',
            'size' => '40'
        )); ?>
      </div>
    </div>
</fieldset><br/>

    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler','/terrain/', array('class' => 'pure-button')); ?>
<?=$this->Form->end(); ?><br/><br/>