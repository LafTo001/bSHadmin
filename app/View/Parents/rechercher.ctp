<!-- /parents/ajouter.ctp -->

<h1>Rechercher un parent</h1>

<? if(isset($parents)) { ?>
<div class="pure-form pure-form-stacked">
    <h2>Confirmer le parent</h2>
    <table>
    <? foreach($parents as $parent) : ?>
        <tr>
            <td width="140"><? if($idJoueur == 0) {
                echo $this->Html->link('Sélectionner',
                array('action' => 'fiche', $parent['Adulte']['id']), array('class' => 'pure-button pure-button-primary'));
            } else {
                echo $this->Html->link('Sélectionner',
                array('action' => 'lierParentJoueur', $parent['Adulte']['id'],$idJoueur), array('class' => 'pure-button pure-button-primary'));
            } ?>
            </td>
            <td><?=$parent['Adulte']['Prenom'].' '.$parent['Adulte']['NomFamille'].', '.
                $parent['Adulte']['Adresse'].', '.$parent['Adulte']['Ville'].', '.$parent['Adulte']['Courriel1'];?></td>
        </tr>
    <? endforeach; ?>
    </table><br/>
</div>

    <? if($idJoueur == 0) {
        echo $this->Form->create('Adulte',array('class' => 'pure-form pure-form-stacked','action' => 'ajouter'));
    } else {
        echo $this->Form->create('Adulte',array('class' => 'pure-form pure-form-stacked','action' => 'ajouter/joueur/'.$idJoueur));
    }
} else {

    echo $this->Form->create('Adulte',array('class' => 'pure-form pure-form-stacked'));
} ?>

  <fieldset>
  <legend>Informations du parent</legend>
  <div class="pure-g">
    <div class="pure-u-3-8">
        <?=$this->Form->input('Prenom',array(
            'label' => 'Prénom',
            'size' => '35',
            'required' => true,
            'autofocus' => true
        )); ?>
    </div>

    <div class="pure-u-5-8">
        <?=$this->Form->input('NomFamille',array(
            'label' => 'Nom de famille',
            'size' => '40',
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-3-8">
        <?=$this->Form->input('Adresse',array(
            'size' => '40'
        )); ?>
    </div>

    <div class="pure-u-1-3">
        <?=$this->Form->input('Ville',array(
            'size' => '35'
        )); ?>  
    </div>

    <div class="pure-u-1-4">
        <?=$this->Form->input('CodePostal',array(
            'label' => 'Code postal',
            'type' => 'tel',
            'size' => '8'
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('TelMaison',array(
            'label' => 'Tel. Maison',
            'size' => '18'
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('TelMobile',array(
            'label' => 'Tel. Mobile',
            'size' => '18'
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('TelTravail',array(
            'label' => 'Tel. Travail',
            'size' => '18'
        )); ?> 
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('Courriel1',array(
            'label' => 'Courriel principal',
            'type' => 'email',
            'size' => '35',
            'required' => $this->Session->read('User.role') != 'admin'
        )); ?>  
    </div>

    <div class="pure-u-8-24">
        <?=$this->Form->input('Courriel2',array(
            'label' => '2e courriel',
            'type' => 'email',
            'size' => '35'
        )); ?> 
    </div>
      
    <div class="pure-u-8-24">
        <?=$this->Form->input('Courriel3',array(
            'label' => '3e courriel',
            'type' => 'email',
            'size' => '35'
        )); ?> 
    </div> 
  </div>
  </fieldset>

<? /*
<p>* Obligatoire pour la recherche d'un parent déjà existant</p><br/>
 */ ?>
<br/>

  <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
  <?=$this->Html->link('Annuler',array('controller' => 'pages', 'action' => 'home'), array('class' => 'pure-button')); ?>
  <?=$this->Form->end(); ?><br/><br/>