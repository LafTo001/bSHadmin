<h1>Éditer un joueur</h1>

<?php echo $this->Form->create('Joueur',array('class' => 'pure-form pure-form-stacked')); ?>

  <fieldset>
    <legend>Informations du joueur</legend>
    <div class="pure-g">
      <? if($this->Session->read('User.role') == 'admin') { ?>
        <div class="pure-u-1-1">
          <?=$this->Form->input('NoMembre',array(
              'label' => 'No. membre BQ',
              'size' => '10',
              'value' => $content['NoMembre']
          )); ?>
        </div> 
      <? } ?>

      <div class="pure-u-3-8">
        <?=$this->Form->input('Prenom',array(
            'label' => 'Prénom',
            'size' => '30',
            'value' => $content['Prenom'],
            'required' => true
        )); ?>
      </div>
      <div class="pure-u-1-2">
        <?=$this->Form->input('NomFamille',array(
            'label' => 'Nom de famille',
            'size' => '35',
            'value' => $content['NomFamille'],
            'required' => true
        )); ?>
      </div>

          <div class="pure-u-3-8">
            <?=$this->Form->input('Sexe',array(
                'options' => array(
                    'M' => 'Masculin',
                    'F' => 'Féminin'),
                'selected' => $content['Sexe']
                )); ?>
          </div>
            
          <div class="pure-u-5-8">
              <?=$this->Form->input('DateNaissance',array(
                  'label' => 'Date de naissance (AAAA-MM-JJ)',
                  'type' => 'text',
                  'value' => $content['DateNaissance'],
                  )); ?>
          </div>
            
          <div class="pure-u-3-8">
            <?=$this->Form->input('NoCAL',array(
                'label' => 'Carte Accès Loisirs',
                'value' => $content['NoCAL'],
                )); ?>
          </div>
            
          <div class="pure-u-5-8">
              <?=$this->Form->input('DateExpCAL',array(
                  'label' => 'Date exp. (AAAA-MM)',
                  'type' => 'text',
                  'value' => date_format(new Datetime($content['DateExpCAL']),"Y-m"),
                  'div' => false
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
                'value' => $content['CarteRAMQ']
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Allergies',array(
                'options' => $ouiNon,
                'selected' => $content['Allergies']
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('DescAllergies',array(
                'label' => 'Si oui, veuillez spécifier',
                'size' => '50',
                'value' => $content['DescAllergies']
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Epipen',array(
                'options' => $ouiNon,
                'selected' => $content['Epipen']
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('AdminEpipen',array(
                'label' => 'Si oui, qui doit administrer',
                'size' => '50',
                'value' => $content['AdminEpipen']
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Maladies',array(
                'options' => $ouiNon,
                'selected' => $content['Maladies']
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('DescMaladies',array(
                'label' => 'Si oui, veuillez spécifier',
                'size' => '50',
                'value' => $content['DescMaladies']
            )); ?>
          </div>
            
          <div class="pure-u-4-24">
            <?=$this->Form->input('Medicaments',array(
                'label' => 'Médicaments',
                'options' => $ouiNon,
                'selected' => $content['Medicaments']
            ));?>
          </div>
          <div class="pure-u-20-24">
            <?=$this->Form->input('DescMedicaments',array(
                'label' => 'Si oui, veuillez spécifier',
                'size' => '50',
                'value' => $content['DescMedicaments']
            )); ?>
          </div>
        </div> 
      </fieldset><br/>
      
      <fieldset>
          <legend>Parent pour la facturation</legend>
          <table>
            <? foreach($parents as $parent): ?>
            <tr>
                <td style="width:40px; text-align:center;"><input type="radio" name="data[Joueur][idParentPrincipal]" value="<?=$parent['P']['id'];?>" 
                        <? if($parent['P']['id'] == $content['idParentPrincipal']) echo 'checked="checked"' ?> /></td>
                <td><?=$parent['P']['Prenom'].' '.$parent['P']['NomFamille'].' - '.$parent['P']['Adresse'].', '.$parent['P']['Ville'].', '.$parent['P']['CodePostal']; ?></td>
            </tr>
            <? endforeach; ?>
          </table>

          
      </fieldset>
      
      <fieldset>
        <legend>Autres informations</legend>
        <div class="pure-g">
            <?=$this->Form->input('Informations',array(
                'label' => '',
                'rows' => '8', 'cols' => '110',
                'value' => $content['Informations']
            ));?>
        </div>
      </fieldset><br/>

    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('controller' => 'joueurs', 'action' => 'fiche', $content['id']), array('class' => 'pure-button')); ?>
<?=$this->Form->end(); ?><br/><br/>
