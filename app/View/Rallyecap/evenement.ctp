<h1>Événement Rallye Cap</h1>

<span><b>Date: </b><?=$this->request->data['Evenement']['DateFormat']; ?></span><br/><br/>

<span><b>Heures: </b><?=$this->request->data['Evenement']['DebutEvenement'].' - '.$this->request->data['Evenement']['FinEvenement']; ?></span><br/><br/>

<span><b>Lieu: </b><?=$this->request->data['Evenement']['NomTerrain'].', '.$this->request->data['Evenement']['Adresse'].', '.$this->request->data['Evenement']['Ville']; ?></span><br/><br/>

<? if($this->Session->read('User.role') == 'admin') {
    echo $this->Form->create('Evenement',array('class' => 'pure-form pure-form-stacked')); ?>

    <div class="pure-g">
        <div class="pure-u-1-1">
            <?=$this->Form->input('DescEvenement',array(
                'label' => "Description courte",
                'size' => '50'
            )); ?>
        </div> 

        <div class="pure-u-1-1">
            <?=$this->Form->input('Commentaire',array(
                'label' => 'Détails',
                'rows' => '12', 'cols' => '95'
            ));?>
        </div>
    </div>
    <br/>
    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'calendrier'), array('class' => 'pure-button')); ?>
    <?=$this->Form->end(); ?><br/><br/>

<? } else { ?>

    <span><b>Description: </b><?=str_replace("Rallye Cap : ","",$this->request->data['Evenement']['DescEvenement']); ?></span><br/><br/>

    <h2>Détails de l'événement:</h2>
    <p><?=str_replace("\n","<br/>",$this->request->data['Evenement']['Commentaire']); ?></p><br/>
    
    <?=$this->Html->link('Retour au calendrier',array('action' => 'calendrier', date('n',strtotime($this->request->data['Evenement']['DateTime']))), array('class' => 'pure-button')); ?>
<? } ?>