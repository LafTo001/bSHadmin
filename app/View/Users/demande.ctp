<!-- /user/perduMotDePasse.ctp -->
<h1>Demande d'acc�s � bSHadmin</h1>

<p>Pour l'instant, envoyer votre demande par courriel au webmaster en indiquant votre nom, �quipe et titre.</p>

<?php echo $this->Form->create('User',array('class' => 'pure-form pure-form-stacked')); ?>

<fieldset>
  <div class="pure-g">
    <div class="pure-u-1-1">
        <?=$this->Form->input('Courriel',array(
            'label' => 'Adresse �lectronique',
            'size' => '50',
            'required' => true
        )); ?>
    </div>
      
    <div class="pure-u-1-1">
        <?=$this->Form->input('Prenom',array(
            'label' => 'Pr�nom',
            'size' => '35',
            'required' => true
        )); ?>
    </div>

    <div class="pure-u-1-1">
        <?=$this->Form->input('NomFamille',array(
            'label' => 'Nom de famille',
            'size' => '40',
            'required' => true
        )); ?>
    </div>
  </div>
</fieldset>

<button type="submit" class="pure-button pure-button-primary">Valider</button>