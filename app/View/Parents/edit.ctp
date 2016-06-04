<!-- /parents/edit.ctp -->

<h1>Édition des coordonnées du parent</h1>

<?php echo $this->Form->create('Adulte',array('class' => 'pure-form pure-form-stacked')); ?>

<fieldset>
  <div class="pure-g">
    <div class="pure-u-3-8">
        <?=$this->Form->input('Prenom',array(
            'label' => 'Prénom',
            'size' => '35',
            'value' => $content['Prenom'],
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-5-8">
        <?=$this->Form->input('NomFamille',array(
            'label' => 'Nom de famille',
            'size' => '40',
            'value' => $content['NomFamille'],
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-3-8">
        <?=$this->Form->input('Adresse',array(
            'size' => '40',
            'value' => $content['Adresse'],
        )); ?>
    </div>

    <div class="pure-u-1-3">
        <?=$this->Form->input('Ville',array(
            'size' => '35',
            'value' => $content['Ville'],
        )); ?>  
    </div>

    <div class="pure-u-1-4">
        <?=$this->Form->input('CodePostal',array(
            'label' => 'Code postal',
            'type' => 'tel',
            'size' => '8',
            'value' => preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $content['CodePostal']),
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('TelMaison',array(
            'label' => 'Tel. Maison',
            'size' => '18',
            'value' => preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $content['TelMaison']),
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('TelMobile',array(
            'label' => 'Tel. Mobile',
            'size' => '18',
            'value' => preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $content['TelMobile']),
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('TelTravail',array(
            'label' => 'Tel. Travail',
            'size' => '18',
            'value' => $content['TelTravail'],
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('Courriel1',array(
            'label' => 'Courriel principal',
            'type' => 'email',
            'size' => '35',
            'value' => $content['Courriel1']
        )); ?>  
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('Courriel2',array(
            'label' => '2e courriel',
            'type' => 'email',
            'size' => '35',
            'value' => $content['Courriel2']
        )); ?> 
    </div>
      
    <div class="pure-u-8-24">
        <?=$this->Form->input('Courriel3',array(
            'label' => '3e courriel ',
            'type' => 'email',
            'size' => '35',
            'value' => $content['Courriel3']
        )); ?> 
    </div>
  </div>
</fieldset><br/>

<fieldset>
  <legend>Section entraineur</legend>
  <div class="pure-g">
    <div class="pure-u-1-4">
        <?=$this->Form->input('NoMembre',array(
            'label' => 'No Membre BQ',
            'size' => '10',
            'value' => $content['NoMembre']
        )); ?>
    </div>

    <div class="pure-u-1-4">
        <?=$this->Form->input('PNCE',array(
            'label' => 'No PNCE',
            'size' => '10',
            'value' => $content['PNCE']
        )); ?>
    </div>
  
    <div class="pure-u-1-2">
        <?=$this->Form->input('DateNaissance',array(
            'label' => 'Date de naissance (AAAA-MM-JJ)',
            'type' => 'text',
            'value' => $content['DateNaissance']
        )); ?>
    </div>
  </div>
</fieldset><br/>

<button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
<?=$this->Html->link('Annuler',array('controller' => 'parents', 'action' => ($this->Session->read('User.role') == 'admin') ? 'fiche/'.$content['id'] : 'fiche'), 
    array('class' => 'pure-button')); ?>
<?=$this->Form->end(); ?>