<script>
    $(function() {
      $( "#EvenementDateEvenement" ).datepicker();
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

<h1>Réservation de terrain</h1>
<? if($this->request->data['Evenement']['idEvenement'] > 0) { ?>
    <p><b>Demande originale:</b><br/>
    <?=$event['VueEvenement']['DateEvenement'];?> de 
    <?=$event['VueEvenement']['DebutEvenement'];?> à 
    <?=$event['VueEvenement']['FinEvenement'];?> sur 
    <?=$event['VueEvenement']['NomTerrain'];?> 
    <? if($this->request->data['Evenement']['idEvenement'] > 0) {
        echo '&nbsp;'.$this->Html->link('Supprimer', array('action' => 'supprimer',$this->request->data['Evenement']['idEvenement']));
    } ?>
    <br/>
    <? if($event['VueEvenement']['IdSerie'] > 0) { ?>
    <br/>Fait partie d'une série d'événement : <b><?=$event['VueEvenement']['DescriptionSerie'];?></b><br/>
        <?=$this->Html->link('Modifier la série au complet',
                        array('controller' => 'series', 'action' => 'editer',$event['VueEvenement']['IdSerie']));?><br/><br/>
        <span style="font-size:12px; margin-left:0px;">* Si vous modifiez seulement cet événement, celui-ci sera exclu de la série d'événement.</span>
    <? } ?>
    </p>
    <? if(isset($partie)) { ?>
        <p><b>Description de la partie</b> : <?=$partie['VuePartie']['NomLigue'].' partie #'.$partie['VuePartie']['NoPartie'];?><br/>
            <?=$partie['VuePartie']['NomEquipeVisiteur'].' vs. '.$partie['VuePartie']['NomEquipeReceveur'];?></p>
    <? } ?>
    <p>Responsable : <a href="mailto:<?=$event['VueEvenement']['CourrielDemandeur'];?>"><?=$event['VueEvenement']['NomDemandeur'];?></a></p>
<? } ?>
   
<?=$this->Form->create('Evenement', array('class' => 'pure-form pure-form-stacked pure-g')); ?>
    <?=$this->Form->input('idEvenement',array('type' => 'hidden', 'div' => false)); ?>
        
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

    <div class="pure-u-1">
        <?=$this->Form->input('DateEvenement',array(
                'label' => "Date de l'évenement",
                'type' => 'text',
                'class' => 'pure-form',
                'onchange' => "this.form.submit()"
            )); ?>
    </div>
        
    <div class="pure-u-1-4">
        <?=$this->Form->input('DebutEvenement',array(
                'label' => 'Heure de début',
                'class' => 'pure-form',
                'options' => $lstHeuresDebut,
                'onchange' => "this.form.submit()"
                
            )); ?>
    </div>
          
        <div class="pure-u-3-4">
            <?=$this->Form->input('FinEvenement',array(
                    'label' => 'Heure de fin',
                    'class' => 'pure-form',
                    'options' => $lstHeuresFin,
                    'onchange' => "this.form.submit()"
                )); ?>
        </div>

    <? if(!isset($partie)) { ?>
	<div class="pure-u-1"> 
            <? if ($this->request->data['Evenement']['idEvenement'] == 0) {
                echo $this->Form->input('TypeEvenement',array(
                        'label' => "Type d'événement",
                        'class' => 'pure-form',
                        'options' => $lstTypes,
                        'onchange' => "this.form.submit()"
                    ));
            } else { 
                echo "<label>Type d'événement</label><span><b>".$event['VueEvenement']['NomType'].'</b></span>';
                echo $this->Form->input('TypeEvenement',array('type' => 'hidden', 'div' => false));
            } ?>
        </div>

        <? if(!empty($lstEquipesReserve) && $this->request->data['Evenement']['TypeEvenement'] < 3) { 
            $labelEquipe = 'Équipe';
            if($this->request->data['Evenement']['TypeEvenement'] == 1) {
                $labelEquipe .= ' receveuse';
            } ?>
            <div class="pure-u-10-24">
                <?=$this->Form->input('IdEquipe',array(
                        'label' => $labelEquipe,
                        'class' => 'pure-form',
                        'options' => $lstEquipesReserve,
                        'empty' => '-- Choisir une équipe --',
                        'onchange' => "this.form.submit()"
                    )); ?>
            </div>
    
            <? if($this->request->data['Evenement']['TypeEvenement'] == 2 && $this->Session->read('User.role') == 'admin') { ?>
                <div class="pure-u-14-24">
                    <?=$this->Form->input('IdCategorie',array(
                            'label' => 'Regroupements',
                            'class' => 'pure-form',
                            'options' => $lstCatPratique,
                            'empty' => array(0 => '-- Pour cette équipe seulement --'),
                            'onchange' => "this.form.submit()" 
                        )); ?>
                </div>
            <? } ?>

        <? } elseif($this->request->data['Evenement']['TypeEvenement'] < 3) {
            $labelEquipe = 'Équipe';
            if($this->request->data['Evenement']['TypeEvenement'] == 1) {
                $labelEquipe .= ' receveuse';
            } ?>
            <div class="pure-u-1"> 
                <label><?=$labelEquipe;?>: <b><?=$equipe;?></b></label>
            </div><br/>
        <?php } ?>

        <? if($this->request->data['Evenement']['TypeEvenement'] == 1) { ?>      
            <div class="pure-u-1">
                <?=$this->Form->input('IdPartie',array(
                    'label' => 'Partie à replacer',
                    'class' => 'pure-form',
                    'options' => $lstParties,
                    'empty' => '-- Sélectionner une partie --',
                    'onchange' => "this.form.submit()"
                )); ?>
            </div> 

            <? if($this->Session->read('User.role') == 'entraineur') { ?>
                <div class="pure-u-1">
                    <br/>* Lorsque vous faites une demande de changement d'horaire pour une partie de la LBAVR, 
                    il est important que l'heure de début de la réservation soit la même que la partie.
                    <br/><br/>* Lorsque vous aurez reçu votre confirmation, vous devrez faire la demande sur le site de la LBAVR.
                </div>
            <? } ?>
        <? } elseif($this->request->data['Evenement']['TypeEvenement'] == 2) { ?>
            <? if($this->Session->read('User.role') == 'entraineur') { ?>
                <div class="pure-u-1"> 
                    <br/>* Notez que les replacements de parties auront la priorité sur les réservations de pratiques.
                <div>
            <? }
        }
    }
     
    if(!isset($partie) && $this->request->data['Evenement']['TypeEvenement'] == 3) { ?>
        <div class="pure-u-1">
            <?=$this->Form->input('DescEvenement',array('label' => 'Courte description *', 'size' => 30)); ?>
            <span style="font-size: 12px; margin-left: 0px;">* Cette description sera affichée sur le calendrier.</span><br/><br/>
        </div>
    <? }

    if($this->request->data['Evenement']['idEvenement'] > 0) { ?>
            <div class="pure-u-1">
                <?=$this->Form->input('Confirmation',array(
                    'label' => '<b>Confirmer la demande</b>',
                    'class' => 'pure-form',
                    'options' => $lstConfirm
                )); ?>
            </div>
    <? } ?>

    <div class="pure-u-1"> 
        <?=$this->Form->input('Commentaire',
                array('label' => 'Commentaires :', 'cols' => '85', 'rows' => '5')); ?><br/>
    </div>
        
    <div class="pure-u-1">
	<button type="submit" class="pure-button pure-button-primary" name="Envoyer" value="Submit">Envoyer la demande</button>
	<? if($this->request->data['Evenement']['idEvenement'] == 0) {
            echo $this->Html->link('Réinitialiser',array('action' => 'reservation'), array('class' => 'pure-button'));
        } else {
            echo $this->Html->link('Annuler',array('controller' => 'pages', 'action' => 'home'), array('class' => 'pure-button'));
        } ?>
    </div>
    <?=$this->Form->end(); ?><br/><br/>
      
