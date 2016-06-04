<!-- /parents/ajouter.ctp -->

<h1>Inscription en ligne</h1>

<?=$this->Form->create('Adulte', array('class' => 'pure-form pure-form-stacked')); ?>

  <fieldset>
  <legend style="width:960px;">Étape 1 : Informations du parent</legend>
  <div class="pure-g">
    <div class="pure-u-1">
        <?=$this->Form->input('Prenom',array(
            'label' => 'Prénom',
            'size' => '35',
            'required' => true,
            'autofocus' => true
        )); ?>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('NomFamille',array(
            'label' => 'Nom de famille',
            'size' => '40',
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('Adresse',array(
            'size' => '40',
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('Ville',array(
            'size' => '35',
            'required' => true
        )); ?>  
    </div>

    <div class="pure-u-1-4">
        <?=$this->Form->input('CodePostal',array(
            'label' => 'Code postal',
            'type' => 'tel',
            'size' => '8',
            'required' => true
        )); ?><br/>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('Courriel1',array(
            'label' => 'Adresse courriel principal',
            'type' => 'email',
            'size' => '35',
            'required' => true
        )); ?>  
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('CourrielConfirm',array(
            'label' => "Confirmer l'adresse courriel",
            'type' => 'email',
            'size' => '35',
            'required' => true
        )); ?>  
    </div>

  </div><br/>
  <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
  <button type="reset" class="pure-button" onclick="location.href='/baseball/accueil'" >Annuler</button>
<?=$this->Form->end(); ?>

  </fieldset><br/>