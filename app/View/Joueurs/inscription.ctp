<!-- /parents/fiche.ctp -->

<h1>Inscrire un joueur</h1>

<span>Joueur : <?=$joueur['VueJoueur']['nomComplet'];?></span><br/>
<span>Date de naissance : <?=$joueur['VueJoueur']['DateNaissance'];?></span>

<h2>Étape 2 : Inscription du joueur pour la saison <?=$saison; ?></h2>

<?php echo $this->Form->create('Joueur',
    array('class' => 'pure-form pure-form-stacked', 'controler' => 'joueurs', 'action' => 'inscription/'.$joueur['VueJoueur']['idJoueur'])); ?>
      
      <fieldset>
        <legend>Inscription</legend>
        <div class="pure-g">
            <div class="pure-u-1-1">
                  <? if($this->Session->read('User.role') == 'admin') {
                      echo $this->Form->input('IdCategorie',array(
                          'label' => 'Catégorie',
                          'options' => $categories,
                          'selected' => $this->request->data['Joueur']['IdCategorie'],
                          'empty' => array(0 => "Annuler l'inscription")
                      ));
                  } else { 
                      echo $this->Form->input('IdCategorie',array('type' => 'hidden')); ?>
                      <span><b>Catégorie : <?=$this->request->data['Joueur']['NomCategorie'];?></b></span><br/>
                      <span style="font-size: 12px; font-style:italic;">* Si vous voulez changer de catégorie, vous devez communiquer avec l'administation de Baseball St-Hyacinthe.</span>
                      <br/><br/>
                  <? } ?>
            </div> 
            
            <div class="pure-u-1-1">
                <? if($this->Session->read('User.role') != 'admin') { ?>
                <p>Le joueur recevra un chandail personnalisé en début de saison.  
                      Vous devez choisir la grandeur du chandail et nous fournir des choix de numéros.</p> 
                <? } ?>
            </div> 
            
            <div class="pure-u-7-24">
              <?=$this->Form->input('Chandail',array(
                  'label' => 'Taille du chandail',
                  'options' => $chandail,
                  'empty' => '-- Choisir une taille --'
              ));?>
            </div> 

            <div class="pure-u-3-24">
              <?=$this->Form->input('Choix1',array(
                  'label' => 'Choix #1',
                  'type' => 'number',
                  'min' => 1,
                  'max' => 99
              )); ?>
            </div>
            <div class="pure-u-3-24">
              <?=$this->Form->input('Choix2',array(
                  'label' => 'Choix #2',
                  'type' => 'number',
                  'min' => 1,
                  'max' => 99
              )); ?>
            </div>
            <div class="pure-u-10-24">
              <?=$this->Form->input('Choix3',array(
                  'label' => 'Choix #3',
                  'type' => 'number',
                  'min' => 1,
                  'max' => 99
              )); ?>
            </div>

          <? if($this->Session->read('User.role') == 'admin') { ?>
            <div class="pure-u-7-24">
              <?=$this->Form->input('Paiement'); ?>
            </div>

            <div class="pure-u-16-24">
              <?=$this->Form->input('modePaiement',array(
                  'label' => 'Mode de paiement',
                  'options' => $modePaiement,
                  'empty' => '-- Choisir un mode --'
              ));?>
            </div>
            
            <div class="pure-u-1-1">
              <?=$this->Form->input('PaiementEcole', array('label' => 'Paiement École de baseball')); ?>
            </div>
          <? } ?>

          </div>
      </fieldset><br/>
	  
      <button type="submit" class="pure-button pure-button-primary" value="Submit">Enregistrer l'inscription</button>
      <?=$this->Html->link('Annuler',array('controller' => 'joueurs', 'action' => 'fiche', $joueur['VueJoueur']['idJoueur']), 
            array('class' => 'pure-button'));?>
    <?=$this->Form->end(); ?><br/><br/>