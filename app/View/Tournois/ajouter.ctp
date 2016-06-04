<!-- app/View/Tournois/ajouter.ctp -->
<h1>Ajouter un tournoi provincial</h1>
 
<?=$this->Form->create('Ligue', array('class' => 'pure-form pure-form-stacked')); ?>
    <fieldset>
        <div class="pure-g">
            <div class="pure-u-1-1">
                <?=$this->Form->input('IdCategorie',array(
                    'label' => 'Catégorie',
                    'options' => $categories,
                    'selected' => $this->Session->read('Tournoi.Categorie'),
                    'empty' => 'Choisir une catégorie'
                ));?><br/>
            </div>
            <div class="pure-u-1-1">
                <?=$this->Form->input('NomLigue', array(
                    'label' => 'Nom complet du tournoi (sur Baseball Québec)',
                    'size' => '60'
                ));?>
            </div>
        </div>
    </fieldset><br/>

    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'index'), array('class' => 'pure-button'));?>
    <?=$this->Form->end(); ?>
