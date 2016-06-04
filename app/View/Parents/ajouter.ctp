<!-- /parents/ajouter.ctp -->

<h1>Ajout d'un parent</h1>

<?php echo $this->Form->create('Parent', 
    array('class' => 'pure-form pure-form-stacked', 'action' => 'ajouter/'.$source)); ?>

  <fieldset>
  <legend>Informations du parent</legend>
  <div class="pure-g">
    <div class="pure-u-3-8">
      <label for="Prenom">Prénom * </label>
      <input id="Prenom" type="text" name="Prenom" size="35" required>
    </div>

    <div class="pure-u-5-8">
      <label for="NomFamille">Nom de famille * </label>
      <input id="NomFamille" type="text" name="NomFamille" size="40" required>
    </div>

    <div class="pure-u-3-8">
      <label for="Adresse">Adresse</label>
      <input id="Adresse" type="text" name="Adresse" size="40">
    </div>

    <div class="pure-u-1-3">
      <label for="Ville">Ville</label>
      <input id="Ville" type="text" name="Ville" size="35">
    </div>

    <div class="pure-u-1-4">
      <label for="CodePostal">Code postal</label>
      <input id="CodePostal" type="text" name="CodePostal" size="8">
    </div>

    <div class="pure-u-1-4">
      <label for="TelMaison">Tel. maison</label>
      <input id="TelMaison" type="text" name="TelMaison" size="18">
    </div>

    <div class="pure-u-1-4">
      <label for="TelMobile">Tel. mobile</label>
      <input id="TelMobile" type="text" name="TelMobile" size="18">
    </div>

    <div class="pure-u-1-4">
      <label for="TelTravail">Tel. travail</label>
      <input id="TelTravail" type="text" name="TelTravail" size="18">
    </div>

    <div class="pure-u-3-8">
      <label for="Courriel1">Courriel principal (nom d'usager) *</label>
      <input id="Courriel1" type="email" name="Courriel1" size="35" required>
    </div>

    <div class="pure-u-5-8">
      <label for="Courriel2">Courriel secondaire</label>
      <input id="Courriel2" type="email" name="Courriel2" size="35">
    </div>
  </div>
  </fieldset>

  <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
  <?=$this->Html->link('Annuler', array('controller' => $controller), array('class' => "pure-button")); ?>
<?=$this->Form->end(); ?>