<h1>Nouveau communiqué</h1>

<?php echo $this->Form->create('Communique',
    array('class' => 'pure-form pure-form-stacked', 'action' => 'ajouter')); ?>
    
  <fieldset>
    <div class="pure-g">
      <div class="pure-u-1">
        <?=$this->Form->input('Titre',array(
            'label' => 'Sujet',
            'size' => '80',
            'required' => true
        ));?>
      </div>

      <div class="pure-u-1">
        <?=$this->Form->input('Texte',array(
            'label' => 'Texte du communiqué',
            'rows' => '10', 'cols' => '120',
            'required' => true
        ));?>
      </div>

<? if($this->Session->read('User.role') == 'admin') { ?>
      <div class="pure-u-1">
        <?=$this->Form->input('Suite',array(
            'label' => 'Url de la suite du communiqué',
            'size' => '60', 'rows' => '1'
        ));?>
      </div>

      <div class="pure-u-1-3">
        <?=$this->Form->input('LienImage',array(
            'label' => "Url de l'image",
            'size' => '35'
        ));?>
      </div>

      <div class="pure-u-2-3">
        <?=$this->Form->input('DimImage',array(
            'label' => "Dimension (%)"
        ));?>
      </div>

      <div class="pure-u-1">
        <?=$this->Form->input('CreditImage',array(
            'label' => "Crédit de l'image",
            'size' => '35'
        ));?>
      </div>
<? } ?>
    </div>
  </fieldset><br/> 
  
  <button type="submit" class="pure-button pure-button-primary" value="Submit">Enregistrer</button>
  <?=$this->Html->link('Annuler','/communiques/',array('class' => 'pure-button'));?>
<?=$this->Form->end(); ?><br/><br/>
