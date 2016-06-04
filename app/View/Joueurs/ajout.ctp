<!-- /joueurs/ajout.ctp -->
<h1>Inscrire un joueur</h1>

<h2>Étape 1 : Enregistrement du joueur</h2>

<?php echo $this->Form->create('Joueur',
    array('class' => 'pure-form pure-form-stacked')); ?>

<? //'controler' => 'joueurs', 'action' => 'ajout/'.$idParent ?>

      <fieldset>
        <legend>Informations du joueur</legend>
        <div class="pure-g">
          <div class="pure-u-3-8">
            <?=$this->Form->input('Prenom',array(
                'label' => 'Prénom',
                'size' => '30',
                'required' => true,
                'autofocus' => true
            )); ?>
          </div>
            
          <div class="pure-u-5-8">
            <?=$this->Form->input('NomFamille',array(
                'label' => 'Nom de famille',
                'size' => '30',
                'required' => true
            )); ?>
          </div>
            
          <div class="pure-u-3-8">
            <?=$this->Form->input('Sexe',array(
                'options' => array(
                    'M' => 'Masculin',
                    'F' => 'Féminin'),
                )); ?>
          </div>
            
          <div class="pure-u-5-8">
              <?=$this->Form->input('DateNaissance',array(
                  'label' => 'Date de naissance (AAAA-MM-JJ)',
                  'type' => 'text',
                  'required' => true
                  )); ?>
          </div>
            
          <div class="pure-u-3-8">
            <?=$this->Form->input('NoCAL',array(
                'label' => 'Carte Accès Loisirs',
                  'required' => $this->Session->read('User.role') != 'admin'
                )); ?>
          </div>
            
          <div class="pure-u-5-8">
              <?=$this->Form->input('DateExpCAL',array(
                  'label' => 'Date exp. (AAAA-MM)',
                  'type' => 'text',
                  'required' => $this->Session->read('User.role') != 'admin'
                  )); ?>
          </div>
        </div>
      </fieldset><br/>
      
      <fieldset>
        <legend>Fiche-santé</legend>
        <div class="pure-g">
          <div class="pure-u-1-1">
            <?=$this->Form->input('CarteRAMQ',array(
                'label' => 'Carte assurance maladie',
                'size' => '15',
                'required' => $this->Session->read('User.role') != 'admin'
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Allergies',array(
                'options' => $ouiNon
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('DescAllergies',array(
                'label' => 'Si oui, veuillez spécifier',
                'size' => '50'
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Epipen',array(
                'options' => $ouiNon
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('AdminEpipen',array(
                'label' => 'Si oui, qui doit administrer',
                'size' => '50'
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Maladies',array(
                'options' => $ouiNon
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('DescMaladies',array(
                'label' => 'Si oui, veuillez spécifier',
                'size' => '50'
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Medicaments',array(
                'label' => 'Médicaments',
                'options' => $ouiNon
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('DescMedicaments',array(
                'label' => 'Si oui, veuillez spécifier',
                'size' => '50'
            )); ?>
          </div>
        </div> 
      </fieldset><br/>
      
      <fieldset>
        <legend>Autres informations</legend>
        <div class="pure-g">
            <?=$this->Form->input('Informations',array(
                'label' => '',
                'rows' => '8', 'cols' => '110'
            ));?>
        </div>
      </fieldset><br/>
	  
      <button type="submit" class="pure-button pure-button-primary" value="Submit">Enregistrer et passer à l'étape suivante ></button>
      <?=$this->Html->link("Annuler l'inscription",array('controller' => 'parents', 'action' => 'fiche', ($this->Session->read('User.role') == 'admin') ? $idParent : ''), 
            array('class' => 'pure-button'));?>
    <?=$this->Form->end(); ?><br/><br/>