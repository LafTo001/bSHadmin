<h1>Enregistrement de résultats</h1>

<span>Division: <?=$this->request->data['Partie']['NomCategorie']; ?></span><br/>
<span>No Partie: <?=$this->request->data['Partie']['NoPartie']; ?></span><br/>
<span>Date: <?=$this->request->data['Partie']['DateFormat']; ?></span><br/>


<?=$this->Form->create('Partie', array('class' => 'pure-form pure-form-stacked pure-g')); 

    echo $this->Form->input('Date', array('type' => 'hidden', 'div' => false)); ?>

    <div class="pure-u-1-3">
        <br/><span style="margin-left:0px;"><?=$this->request->data['Partie']['NomEquipeVisiteur']; ?></span>
    </div>

    <div class="pure-u-2-3">
        <?=$this->Form->input('PointsVisiteur',array(
            'label' => 'Pointage:'
        )); ?><br/>
    </div>

    <div class="pure-u-1-3">
        <br/><span style="margin-left:0px;"><?=$this->request->data['Partie']['NomEquipeReceveur']; ?></span>
    </div>

    <div class="pure-u-2-3">
        <?=$this->Form->input('PointsReceveur',array(
            'label' => 'Pointage:'
        )); ?><br/>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('Statut',array(
            'label' => 'Statut de la partie',
            'options' => $statuts
        )); ?><br/>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('Commentaire',array(
            'label' => 'Informations à rajouter',
            'cols' => '110',
            'rows' => '8'
        )); ?><br/>
    </div>
    

    <div class="pure-u-1" style="margin-left:-15px;">
        <button type="submit" class="pure-button pure-button-primary" name="Envoyer" value="Submit">Enregistrer</button>
        <?=$this->Html->link('Annuler',array('controller' => 'pages', 'action' => 'home'), array('class' => 'pure-button'));?>
    </div>
<?= $this->Form->end(); ?>
</div>
      
