<h1>Éditer une équipe</h1>

<?=$this->Form->create('Equipe',array('class' => 'pure-form pure-form-stacked')); ?>

  <fieldset>
    <div class="pure-g">
      <div class="pure-u-1-5">
        <?=$this->Form->input('idCategorie',array(
            'label' => 'Catégorie',
            'options' => $listeCategories,
            'required' => true
        )); ?>
      </div>

      <div class="pure-u-1-5">
        <?=$this->Form->input('Classe',array(
            'size' => '5',
            'required' => true
        )); ?>
      </div>
        
      <div class="pure-u-3-5">
        <?=$this->Form->input('NomEquipe',array(
            'label' => 'Nom de l\'équipe',
            'size' => '15',
            'required' => true
        )); ?>
      </div>
    </div><br/>
    
    <div class="pure-g">
      <div class="pure-u-1-4">
        <?=$this->Form->input('Ent1',array(
            'label' => 'Entraineur-chef',
            'options' => $listeParents,
            'empty' => array(0 => ' -- Choisir un entraineur --'),
            'selected' => $ent1
        )); ?>
      </div>
    </div>
  <fieldset><br/><br/>
        
      <div class="pure-u-3-5">

        <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
        <?=$this->Html->link('Annuler',array('action' => 'liste'), array('class' => 'pure-button')); ?>
      </div>
<?=$this->Form->end(); ?>
