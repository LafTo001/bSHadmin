<!-- /user/profil.ctp -->
<h1>Profil de l'usager</h1>

<? if($type != 'infos') { ?>
    <p><?=$this->Html->link('Modifier les informations',array('action' => 'profil', 'infos'));?></p>
<? } if($type != 'password') { ?>
    <p><?=$this->Html->link('Modifier le mot de passe',array('action' => 'profil', 'password'));?></p>
<? } if($this->Session->read('User.role') != 'terrain' /*&& 1 == 0*/) { ?>
    <p><?=$this->Html->link('Inscrire un joueur',array('controller' => 'parents', 'action' => 'fiche'));?></p>
<? } ?>
    
<p><?=$this->Html->link('Mes photos',array('controller' => 'photos', 'action' => 'index'));?></p>
    
<? if($type == 'infos') { ?>

    <?=$this->Form->create('User',array('class' => 'pure-form pure-form-stacked', 'action' => '/profil/infos')); ?>

    <fieldset>
      <legend>Informations de l'usager</legend>
      <div class="pure-g">
        <div class="pure-u-3-8">
            <?=$this->Form->input('Prenom',array(
                'label' => 'Prénom',
                'size' => '35',
                'value' => $data['Prenom'],
                'required' => true
            )); ?>
        </div>

        <div class="pure-u-5-8">
            <?=$this->Form->input('NomFamille',array(
                'label' => 'Nom de famille',
                'size' => '40',
                'value' => $data['NomFamille'],
                'required' => true
            )); ?>
        </div>

        <div class="pure-u-3-8">
            <?=$this->Form->input('Adresse',array(
                'size' => '40',
                'value' => $data['Adresse']
            )); ?>
        </div>

        <div class="pure-u-1-3">
            <?=$this->Form->input('Ville',array(
                'size' => '35',
                'value' => $data['Ville']
            )); ?>  
        </div>

        <div class="pure-u-1-4">
            <?=$this->Form->input('CodePostal',array(
                'label' => 'Code postal',
                'type' => 'tel',
                'size' => '8',
                'value' => preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $data['CodePostal'])
            )); ?> 
        </div>

        <div class="pure-u-1-4">
            <?=$this->Form->input('TelMaison',array(
                'label' => 'Tel. Maison',
                'size' => '18',
                'value' => preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $data['TelMaison'])
            )); ?> 
        </div>

        <div class="pure-u-1-4">
            <?=$this->Form->input('TelMobile',array(
                'label' => 'Tel. Mobile',
                'size' => '18',
                'value' => preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $data['TelMobile'])
            )); ?> 
        </div>

        <div class="pure-u-1-4">
            <?=$this->Form->input('TelTravail',array(
                'label' => 'Tel. Travail',
                'size' => '18',
                'value' => $data['TelTravail']
            )); ?> 
        </div>

        <div class="pure-u-3-8">
            <?=$this->Form->input('Courriel1',array(
                'label' => 'Courriel principal',
                'type' => 'email',
                'size' => '35',
                'value' => $data['Courriel1'],
                'required' => true
            )); ?>  
        </div>

        <div class="pure-u-5-8">
            <?=$this->Form->input('Courriel2',array(
                'label' => 'Courriel secondaire',
                'type' => 'email',
                'size' => '35',
                'value' => $data['Courriel2']
            )); ?> 
        </div>
      </div>
    </fieldset><br/>

<? } elseif($type == 'password') { ?>

    <?=$this->Form->create('User',array('class' => 'pure-form pure-form-stacked', 'action' => 'profil/password')); ?>
        
      <fieldset>
          <legend>Changement de mot de passe</legend>
      <div class="pure-g">
        <div class="pure-u-1">
            <? /*= $this->Form->input('oldPassword',array(
                'label' => 'Ancien mot de passe',
                'type' => 'password',
                'size' => '15',
                'pattern' => '.{7,12}',
            ));*/ ?>
        </div>
          
        <div class="pure-u-1">
            <?=$this->Form->input('password',array(
                'label' => 'Nouveau mot de passe',
                'type' => 'password',
                'size' => '15',
                'pattern' => '.{7,12}',
            )); ?>
        </div>
          
        <div class="pure-u-1">
            <?=$this->Form->input('confirmPassword',array(
                'label' => 'Confirmer le nouveau mot de passe',
                'type' => 'password',
                'size' => '15',
                'pattern' => '.{7,12}',
            )); ?>
        </div>
      </div><br/>
      </fieldset>   
<? }

if($type == 'infos' || $type == 'password') { ?>
    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'profil'), array('class' => 'pure-button')); ?>
    <?=$this->Form->end(); ?>
<? } ?>