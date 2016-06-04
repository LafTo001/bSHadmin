<h1>Envoyer un message électronique</h1>

<?php echo $this->Form->create('Email', array('class' => 'pure-form pure-form-stacked', 'action' => 'composer')); ?>
    
  <fieldset>
    <div class="pure-g">
        <div class="pure-u-1-3">
            <?=$this->Form->input('Expediteur',array(
                'label' => 'Expéditeur',
                'options' => $expediteurs,
                'empty' => '-- Sélectionner un expéditeur --',
                //'required' => true
            ));?>
        </div> 
        
        <div class="pure-u-2-3">
            <?=$this->Form->input('Destinataire',array(
                'label' => 'Destinataires',
                'options' => $destinataires,
                'empty' => '-- Sélectionner les destinataires --'
            ));?>
        </div> 
        
        <div class="pure-u-1">
            <?=$this->Form->input('Sujet',array(
                'label' => 'Sujet',
                'size' => '80',
                'required' => true
            ));?>
        </div>

        <div class="pure-u-1">
            <?=$this->Form->input('Message',array(
                'label' => 'Message',
                'rows' => '12', 'cols' => '120',
                'required' => true
            ));?>
        </div>

        <div class="pure-u-1">
            <label for="Enregistrer">Je désire enregistrer ce message afin de le réutiliser ultérieurement:</label>
            <?=$this->Form->checkbox('Enregistrer',array('value' => '1', 'hidden' => false));?><br/><br/>
        </div>
  
        <div class="pure-u-5-24">
            <?=$this->Form->submit("Envoyer le message", array('name' => 'Envoyer', 'class' => 'pure-button pure-button-primary'));?>
        </div>
        <div class="pure-u-6-24">
            <?=$this->Form->submit("Enregister sans envoyer", array('name' => 'Enregistrer', 'class' => 'pure-button pure-button-primary'));?>
        </div>
        <div class="pure-u-12-24">
            <?=$this->Html->link('Annuler', array('controller' => 'emails', 'action' => 'index'),array('class' => 'pure-button'));?>
        </div>
        <?=$this->Form->end(); ?><br/><br/>

    </div>
  </fieldset><br/> 
