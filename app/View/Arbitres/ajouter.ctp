<h1>Ajouter un arbitre</h1>

<?php echo $this->Form->create('Arbitre', array('class' => 'pure-form pure-form-stacked')); ?>
        
    <fieldset>
      <div class="pure-g">
        <div class="pure-u-3-8">
            <?=$this->Form->input('Prenom',array(
                'label' => 'Prénom',
                'size' => '35',
                'required' => true
            ));?>
        </div>

        <div class="pure-u-5-8">
            <?=$this->Form->input('NomFamille',array(
                'label' => 'Nom de famille',
                'size' => '40',
                'required' => true
            ));?>
        </div>
          
        <div class="pure-u-1-4">
            <?=$this->Form->input('TelMaison',array(
                'label' => 'Tel. maison',
                'size' => '18'
            ));?>
        </div>
          
        <div class="pure-u-1-4">
            <?=$this->Form->input('TelMobile',array(
                'label' => 'Tel. mobile',
                'size' => '18'
            ));?>
        </div>
          
        <div class="pure-u-1-4">
            <?=$this->Form->input('TelTravail',array(
                'label' => 'Tel. travail',
                'size' => '18'
            ));?>
        </div>
          
        <div class="pure-u-3-8">
            <?=$this->Form->input('Courriel1',array(
                'label' => 'Courriel principal',
                'size' => '35'
            ));?>
        </div>
          
        <div class="pure-u-5-8">
            <?=$this->Form->input('Courriel2',array(
                'label' => 'Courriel secondaire',
                'size' => '35'
            ));?>
        </div>
          
        <div class="pure-u-1-4">
            <?=$this->Form->input('Type',array(
                'label' => 'Type',
                'options' => $listeTypes
            ));?>
        </div>
          
        <div class="pure-u-3-4">
            <?=$this->Form->input('Grade',array(
                'label' => 'Grade',
                'options' => $listeGrades,
                'empty' => array(null => '')
            ));?>
        </div>
      </div>
    </fieldset><br/>
       
    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'index'),array('class' => 'pure-button'));?>
<?=$this->Form->end(); ?><br/><br/>