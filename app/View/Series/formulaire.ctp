<script>
    $(function() {
      $( "#SerieDateDebut" ).datepicker();
      $( "#SerieDateFin" ).datepicker();
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
      <p style="margin-left:0px;">Terrain : <b><?=$terrain['NomTerrain'] ?></b><br>Date : <b><?=$date['VueDate']['DateFormat']; ?></b></p>
<?php
if(!empty($events)) {
    foreach($events as $event) { ?>
	<p style="border: 2px solid #888888; border-radius: 10px; padding: 10px; margin-left: 20px; margin-right:20px; box-shadow: 5px 5px 5px #555555; background: #EEEEEE;">
            <?=$event['VueEvenement']['DebutEvenement'].' @ '.$event['VueEvenement']['FinEvenement'] ?><br/>
            <?  if($event['VueEvenement']['TypeEvenement'] == 3) {
                    echo $event['VueEvenement']['DescEvenement'];
                } else {
                    echo $event['VueEvenement']['NomType'];
                } ?></p>
<?  }
} else { ?>
    <br><p style="margin-left: 00px;" >Aucun événement cédulé</p>
<? } ?>
</aside>

<h1>Enregistrement de série</h1>     
        
<?=$this->Form->create('Serie', array('class' => 'pure-form pure-form-stacked pure-g')); ?>
    <?=$this->Form->input('idSerie',array('type' => 'hidden', 'div' => false)); ?>
        
    <div class="pure-u-1">
        <? if(isset($lstTerrains)) {
            echo $this->Form->input('IdTerrain',array(
                    'label' => 'Terrain',
                    'class' => 'pure-form',
                    'options' => $lstTerrains,
                    'onchange' => "this.form.submit()"
                ));
        } else { ?>
            <label>Terrain</label>
            <span style="margin-left:0px; "><b><?=$terrain['NomTerrain'];?></b></span>
        <? } ?>
    </div>

    <div class="pure-u-1-3">
        <?=$this->Form->input('DateDebut',array(
                'label' => "Date de début",
                'type' => 'text',
                'class' => 'pure-form',
                'onchange' => "this.form.submit()"
            )); ?>
    </div>
        
    <div class="pure-u-2-3">
        <?=$this->Form->input('DateFin',array(
                'label' => "Date de fin",
                'type' => 'text',
                'class' => 'pure-form',
                'onchange' => "this.form.submit()"
            )); ?>
    </div>

    <div class="pure-u-1-1">
        <?=$this->Form->input('JourSemaine',array(
                'label' => 'Fréquence',
                'class' => 'pure-form',
                'options' => $lstFrequence
            )); ?>
    </div>
        
    <div class="pure-u-1-3">
        <?=$this->Form->input('HeureDebut',array(
                'label' => 'Heure de début',
                'class' => 'pure-form',
                'options' => $lstHeuresDebut,
                'onchange' => "this.form.submit()"
            )); ?>
    </div>

    <div class="pure-u-2-3">
        <?=$this->Form->input('HeureFin',array(
                'label' => 'Heure de fin',
                'class' => 'pure-form',
                'options' => $lstHeuresFin,
                'onchange' => "this.form.submit()"
            )); ?>
    </div>

    <div class="pure-u-1">
        <?=$this->Form->input('DescriptionSerie',array('label' => 'Courte description', 'size' => 50)); ?>
    </div>
	  
    <div class="pure-u-1"> 
        <?=$this->Form->input('Commentaire',
                array('label' => 'Commentaires :', 'cols' => '80', 'rows' => '5')); ?><br/> 
    </div>
          
    <div class="pure-u-1">
	<button type="submit" class="pure-button pure-button-primary" name="Envoyer" value="Submit">Enregistrer</button>
	<? if($this->request->data['Serie']['idSerie'] == 0) {
            echo $this->Html->link('Réinitialiser',array('action' => 'formulaire'), array('class' => 'pure-button'));
        } else {
            echo $this->Html->link('Annuler',array('controller' => 'serie', 'action' => 'index'), array('class' => 'pure-button'));
        } ?>
    </div>
    <?=$this->Form->end(); ?><br/><br/>
      
