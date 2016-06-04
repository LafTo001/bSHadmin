<script>
    $(function() {
      $( "#PartieDate" ).datepicker();
      $.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
		'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin',
		'Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
		dayNamesMin: ['D','L','M','M','J','V','S'],
		weekHeader: 'Sem.',
		dateFormat: 'yy/mm/dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
		};
	$.datepicker.setDefaults($.datepicker.regional['fr']);
	
	$.timepicker.regional['fr'] = {
		timeOnlyTitle: 'Choisissez l\'heure',
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Seconde',
		millisecText: 'Miliseconde',
		closeText: 'Fermer',
		currentText: 'Actuel',
		ampm: false
	};
	$.timepicker.setDefaults($.timepicker.regional['fr']);
    });
    
    function stopRKey(evt) { 
        var evt = (evt) ? evt : ((event) ? event : null); 
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
        if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
    } 

    document.onkeypress = stopRKey; 
</script>

<aside style="width: 324px; background: #DCE5EA; min-height:420px; border-radius: 10px;">
      <table id="menuhead" style="width: 324px; border-radius: 10px 10px 0 0 ;"><tr><td><center>Disponibilité du terrain</center></td></tr></table>
      <p style="margin-left:0px;">Terrain : <b><?=$terrain['NomTerrain'] ?></b><br>Date : <b><?=$dateFormat; ?></b></p>
<?php
if(!empty($events)) {
    foreach($events as $ev) { ?>
	<p style="border: 2px <? if($ev['VueEvenement']['Confirmation'] % 2 == 1) echo 'solid'; else echo 'dashed'; ?>
            #808080; border-radius: 10px; padding: 10px; margin-left: 20px; margin-right:20px; box-shadow: 5px 5px 5px #555555; background: #EEEEEE;">
            <?=$ev['VueEvenement']['DebutEvenement'].' à '.$ev['VueEvenement']['FinEvenement'] ?><br/>
            <?  if($ev['VueEvenement']['TypeEvenement'] == 3) {
                    echo $ev['VueEvenement']['DescEvenement'];
                } else {
                    echo $ev['VueEvenement']['NomType'];
                } ?></p>
<?  }
} else { ?>
    <br><p style="margin-left: 00px;" >Aucun événement cédulé</p>
<? } ?>
</aside>

<h1>Ajouter ou modifier une partie</h1>

<?=$this->Form->create('Partie', array('class' => 'pure-form pure-form-stacked pure-g')); ?>

    <? if(isset($lstLigues)) { ?>
        <div class="pure-u-1">
            <?=$this->Form->input('IdLigue',array(
                'label' => 'Ligue ou tournoi',
                'class' => 'pure-form',
                'options' => $lstLigues,
                'onchange' => "this.form.submit()"
            )); ?>
        </div>
    <? } else { ?>
        <div class="pure-u-1">
            <label>Tournoi</label>
            <b><?=$tournoi['VueTournoi']['NomTournoi'];?></b>
        </div>
    <? } ?>
    
    <div class="pure-u-9-24">
        <?=$this->Form->input('IdCategorie',array(
            'label' => 'Catégorie',
            'class' => 'pure-form',
            'options' => $lstCategories,
            'onchange' => "this.form.submit()"
        )); ?>
        </div>
    
    <div class="pure-u-15-24">
        <?=$this->Form->input('Classe',array(
            'class' => 'pure-form',
            'options' => $lstClasses,
            'onchange' => "this.form.submit()"
        )); ?>
    </div>
    
    <div class="pure-u-9-24">
        <?=$this->Form->input('Date',array(
            'label' => "Date",
            'type' => 'text',
            'class' => 'pure-form',
            'onchange' => "this.form.submit()"
        )); ?>
    </div>
    
    <div class="pure-u-15-24">
        <?=$this->Form->input('Heure',array(
            'class' => 'pure-form',
            'options' => $lstHeures,
        )); ?>
    </div>

    <div class="pure-u-9-24">
        <?=$this->Form->input('NoPartie',array(
            'label' => 'No de partie',
            'class' => 'pure-form',
            'size' => '4'
        )); ?>
    </div>
     
    <? if(isset($lstTerrains)) { ?>
        <div class="pure-u-15-24">
            <?=$this->Form->input('IdTerrain',array(
                'label' => 'Terrain',
                'class' => 'pure-form',
                'options' => $lstTerrains,
                'onchange' => "this.form.submit()"
                )); ?>
        </div>
    
    <? } else { ?>
        <div class="pure-u-15-24">
            <?=$this->Form->input('NomTerrain',array(
                'label' => 'Nom du terrain',
                'class' => 'pure-form',
                'size' => '40',
                'required' => true
            )); ?>
        </div>
    <? } ?>

    <div class="pure-u-9-24">  
        <?=$this->Form->input('IdEquipeVisiteur',array(
            'label' => 'Équipe visiteur',
            'class' => 'pure-form',
            'options' => $lstEquipes,
            'empty' => '-- Choisir une équipe --'
        )); ?>
    </div>
    
    <div class="pure-u-15-24">
        <?=$this->Form->input('NomEquipeVisiteur',array(
            'label' => 'Autre équipe',
            'class' => 'pure-form',
            'size' => '47'
        )); ?>
    </div>
    
    <div class="pure-u-9-24">  
        <?=$this->Form->input('IdEquipeReceveur',array(
            'label' => 'Équipe receveuse',
            'class' => 'pure-form',
            'options' => $lstEquipes,
            'empty' => '-- Choisir une équipe --'
        )); ?>
    </div>
    
    <div class="pure-u-15-24">
        <?=$this->Form->input('NomEquipeReceveur',array(
            'label' => 'Autre équipe',
            'class' => 'pure-form',
            'size' => '47'
        )); ?><br/>
    </div>

    <? if($this->Session->read('User.role') == 'admin') { ?>
        <div class="pure-u-1"> 
            <?=$this->Form->input('parties',
                    array('label' => 'Enregistrement de plusieurs parties', 'cols' => '120', 'rows' => '10')); ?><br/>
        </div>
    <? } ?>

    <div class="pure-u-1">
        <button type="submit" class="pure-button pure-button-primary" name="Envoyer" value="Submit">Envoyer la demande</button>
        <?=$this->Html->link('Annuler',array('controller' => 'pages', 'action' => 'home'), array('class' => 'pure-button'));?>
    </div>
<?= $this->Form->end(); ?><br/><br/>
      
