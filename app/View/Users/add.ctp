<!-- app/View/Users/add.ctp -->
<h1>Ajouter un usager</h1>
 
<?=$this->Form->create('User',
            array('class' => 'pure-form pure-form-stacked', 'url' => array('action' => 'selectParent'))); ?>
    <fieldset>
        <div class="pure-g">
            <div class="pure-u-1-4">
                <?=$this->Form->input('idParent', array(
                    'options' => $liste,
                    'label' => '',
                    'empty' => 'Sélectionner une personne',
                    'selected' => $this->Session->read('NouveauUser.idParent'),
                    'onchange' => "this.form.submit()"
                ));?>
            </div>
            <div class="pure-u-3-4">
                <?=$this->Html->link('Ajouter un membre',
                        array('controller' => 'parents', 'action' => 'ajouter', 'nouvelUsager'),
                        array('class' => 'pure-button pure-button-primary'));?>
            </div>
            <div class="pure-u-3-4">
                <? if($this->Session->read('NouveauUser.idParent') > 0) { ?>
                    <p>Adresse : <?=$parent['VueParent']['AdresseComplete'];?><br/>
                       Nom d'usager : <?=$parent['VueParent']['Courriel1'];?></p>
                <? } else { ?>
                    <p>Aucune personne sélectionnée...</p>
                <? } ?>  
            </div>
        </div>
    </fieldset><br/>
<?=$this->Form->end(); ?>
<?=$this->Form->create('User', array('class' => 'pure-form pure-form-stacked', 'action' => 'add')); ?>
    <fieldset>
        <legend>Sélectionner le(s) role(s) de l'usager</legend>
        <div class="pure-g">
            <div class="pure-u-3-24">
                <label for="parent">Parent</label>
                <?=$this->Form->checkbox('Parent', array('value' => "1",'hidden' => false,'checked' => true));?>
            </div>
            <div class="pure-u-3-24">
                <label for="admin">Adminstrateur</label>
                <?=$this->Form->checkbox('Admin', array('value' => "1",'hidden' => false));?>
            </div>
            <div class="pure-u-3-24">
                <label for="entraineur">Entraineur</label>
                <?=$this->Form->checkbox('Entraineur', array('value' => "1",'hidden' => false));?>
            </div>
            <div class="pure-u-3-24">
                <label for="terrain">Resp. de terrain</label>
                <?=$this->Form->checkbox('Terrain', array('value' => "1",'hidden' => false));?>
            </div>
            <div class="pure-u-3-24">
                <label for="arbitre">Resp. des arbitres</label>
                <?=$this->Form->checkbox('Arbitre', array('value' => "1",'hidden' => false));?>
            </div>
        </div>
      
    </fieldset><br/>  

    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'index'), array('class' => 'pure-button'));?>
    <?=$this->Form->end(); ?>
