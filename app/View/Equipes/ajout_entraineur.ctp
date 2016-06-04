<h1>Ajouter un entraineur</h1>

<?=$this->Form->create('Entraineur',array('class' => 'pure-form pure-form-stacked')); ?>

  <fieldset>
    <div class="pure-g">
      <div class="pure-u-1-4">
        <?=$this->Form->input('Titre',array(
            'label' => 'Titre',
            'options' => $listeTitres,
        )); ?>
      </div>

      <div class="pure-u-3-4">
        <?=$this->Form->input('IdParent',array(
            'label' => 'Parent',
            'options' => $listeParents,
            'empty' => array(0 => ' -- Choisir un parent --')
        )); ?>      
      </div>
    </div>
  <fieldset><br/><br/>
        
      <div class="pure-u-3-5">

        <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
        <?=$this->Html->link('Annuler',array('action' => 'fiche'), array('class' => 'pure-button')); ?>
      </div>
<?=$this->Form->end(); ?>

