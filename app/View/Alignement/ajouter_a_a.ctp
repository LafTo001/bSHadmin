<h1>Ajouter les parties AA</h1>

<?= $this->Form->create('Partie'); ?>
<div class="pure-form pure-form-stacked pure-g">
    
    <div class="pure-u-1-4">
        <?=$this->Form->input('IdCategorie',array(
            'label' => 'Catégorie',
            'class' => 'pure-form',
            'options' => array( 3 => 'Moustique AA',
                                4 => 'Pee-wee AA',
                                5 => 'Bantam AA'),
            'empty' => array('-- Choisir la catégorie --')
        )); ?>
    </div>
    
    <div class="pure-u-3-4">
        <?=$this->Form->input('IdLigue',array(
            'label' => 'Saison',
            'class' => 'pure-form',
            'options' => array( 5 => 'Saison régulière',
                                6 => 'Séries'),
            'empty' => array('-- Choisir la saison --')
        )); ?>
    </div>
 
    <div class="pure-u-1">
        <?=$this->Form->input('Parties',array(
            'label' => '<br/>Extraire les parties du site',
            'rows' => '24',
            'cols' => '120'
        )); ?>
    </div>
</div><br/>     

<div class="pure-u-1">
    <button type="submit" class="pure-button pure-button-primary" value="Submit">Envoyer la demande</button>
    <?=$this->Html->link('Annuler',array('controller' => 'pages', 'action' => 'home'), array('class' => 'pure-button'));?>
</div>
<?= $this->Form->end(); ?>
      
