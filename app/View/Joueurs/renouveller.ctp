<h1>Inscription du joueur</h1>

<div class="pure-form pure-form-stacked">
    
    <fieldset>
      <legend>Informations du joueur</legend>
      <div class="pure-g">
        <div class="pure-u-1">    
          <h2><?=$joueur['Prenom'].' '.$joueur['NomFamille'].' &nbsp; '.
                $this->Html->image('/img/glyphicons/glyphicons_030_pencil.png', 
                        array('url' => array('action' => 'edit', $joueur['id']),
                            'width' => '16px', 'height' => '16px')); ?></h2>
          <span>Date de naissance : <?=$joueur['DateNaissance'];?></span></br>
          <span>Carte accès loisirs : <?=$joueur['NoCAL'];?></span></br>
          <span>Exp. : <?=$joueur['DateExpCAL'];?></span></br></br>
        </div>
      </div>
    </fieldset>
    
    <fieldset>
      <legend>Fiche santé</legend>
      <div class="pure-g">
        <div class="pure-u-1">
          <span>Carte assurance maladie : <?=$joueur['CarteRAMQ'] ?></span><br/>
          <span>Allergies : <?=$joueur['DescAllergies'] ?></span><br/>
          <span>Épipen : <?=$joueur['AdminEpipen'] ?></span><br/>
          <span>Maladies : <?=$joueur['DescMaladies'] ?></span><br/>
          <span>Médicaments : <?=$joueur['DescMedicaments'] ?></span><br/>
        </div></div>
    </fieldset><br/>

<?=$this->Form->create('Inscription', array('class' => 'pure-form pure-form-stacked'));?>

  <fieldset>
    <legend>Renouvellement d'inscription</legend>
    <div class="pure-g">
      <div class="pure-u-1-1">
            <? if($this->Session->read('User.role') == 'admin') {
                echo $this->Form->input('IdCategorie',array(
                    'label' => 'Catégorie',
                    'options' => $categories,
                    'empty' => 'Choisir la catégorie'
                ));
            } else { ?>
                <span><b>Catégorie : <?=$categorieParDefaut;?></b></span><br/>
                <span style="font-size: 12px; font-style:italic;">* Si vous voulez changer de catégorie, vous devez communiquer avec l'administation de Baseball St-Hyacinthe.</span>
                <br/><br/>
            <? } ?>
          </div> 

      <div class="pure-u-7-24">
        <?=$this->Form->input('Chandail',array(
            'options' => $chandail,
            'empty' => '-- Choisir une taille --'
        ));?>
      </div> 

      <div class="pure-u-3-24">
        <?=$this->Form->input('Choix1',array(
            'label' => 'Choix #1',
            'type' => 'text',
            'size' => '3'
        )); ?>
      </div>
      <div class="pure-u-3-24">
        <?=$this->Form->input('Choix2',array(
            'label' => 'Choix #2',
            'type' => 'text',
            'size' => '3'
        )); ?>
      </div>
      <div class="pure-u-11-24">
        <?=$this->Form->input('Choix3',array(
            'label' => 'Choix #3',
            'type' => 'text',
            'size' => '3'
        )); ?>
      </div>

    <? if($this->Session->read('User.role') == 'admin') { ?>
      <div class="pure-u-3-8">
        <?=$this->Form->input('Paiement'); ?>
      </div>

      <div class="pure-u-5-8">
        <?=$this->Form->input('modePaiement',array(
            'label' => 'Mode de paiement',
            'options' => $modePaiement,
            'empty' => '-- Choisir un mode --'
        ));?>
      </div>
    <? } ?>
        
    </div>
  </fieldset><br/>
  
  <?=$this->Form->input('id',array('type' => 'hidden'));?>
  <?=$this->Form->input('IdJoueur',array('type' => 'hidden'));?>
  <?=$this->Form->input('Saison',array('type' => 'hidden'));?>
  
  <?=$this->Html->link('< Étape 1 : Modifier le joueur',array('controller' => 'joueurs', 'action' => 'edit', $joueur['id']), array('class' => 'pure-button pure-button-primary')); ?>
  <button type="submit" class="pure-button pure-button-primary" value="Submit">Enregistrer l'inscription</button>
  <?=$this->Html->link('Retour sans enregistrer',array('controller' => 'joueurs', 'action' => 'index'), array('class' => 'pure-button')); ?>
<?=$this->Form->end(); ?><br/><br/>
