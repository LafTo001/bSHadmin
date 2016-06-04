<script>
    $(function() {
      $( "#EvenementDateEvenement" ).datepicker();
      $.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: 'Pr�c�dent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','F�vrier','Mars','Avril','Mai','Juin',
		'Juillet','Ao�t','Septembre','Octobre','Novembre','D�cembre'],
		monthNamesShort: ['Janv.','F�vr.','Mars','Avril','Mai','Juin',
		'Juil.','Ao�t','Sept.','Oct.','Nov.','D�c.'],
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
      <table id="menuhead" style="width: 324px; border-radius: 10px 10px 0 0 ;"><tr><td><center>Disponibilit� du terrain</center></td></tr></table>
      <p style="margin-left:0px;">Terrain : <b><?=$terrain['NomTerrain'] ?></b><br>Date : <b><?=$dateFormat; ?></b></p>
<?php
if(!empty($events)) {
    foreach($events as $ev) { ?>
	<p style="border: 2px <? if($ev['VueEvenement']['Confirmation'] % 2 == 1) echo 'solid'; else echo 'dashed'; ?>
            #808080; border-radius: 10px; padding: 10px; margin-left: 20px; margin-right:20px; box-shadow: 5px 5px 5px #555555; background: #EEEEEE;">
            <?=$ev['VueEvenement']['DebutEvenement'].' � '.$ev['VueEvenement']['FinEvenement'] ?><br/>
            <?  if($ev['VueEvenement']['TypeEvenement'] == 3) {
                    echo $ev['VueEvenement']['DescEvenement'];
                } else {
                    echo $ev['VueEvenement']['NomType'];
                } ?></p>
<?  }
} else { ?>
    <br><p style="margin-left: 00px;" >Aucun �v�nement c�dul�</p>
<? } ?>
</aside>

<h1>R�servation de terrain</h1>
<? if($this->request->data['Evenement']['idEvenement'] > 0) { ?>
    <p><b>Demande originale:</b><br/>
    <?=$event['VueEvenement']['DateEvenement'];?> de 
    <?=$event['VueEvenement']['DebutEvenement'];?> � 
    <?=$event['VueEvenement']['FinEvenement'];?> sur 
    <?=$event['VueEvenement']['NomTerrain'];?> 
    <? if($this->request->data['Evenement']['idEvenement'] > 0) {
        echo '&nbsp;'.$this->Html->link('Supprimer', array('action' => 'supprimer',$this->request->data['Evenement']['idEvenement']));
    } ?>
    <br/>
    <? if($event['VueEvenement']['IdSerie'] > 0) { ?>
    <br/>Fait partie d'une s�rie d'�v�nement : <b><?=$event['VueEvenement']['DescriptionSerie'];?></b><br/>
        <?=$this->Html->link('Modifier la s�rie au complet',
                        array('controller' => 'series', 'action' => 'editer',$event['VueEvenement']['IdSerie']));?><br/><br/>
        <span style="font-size:12px; margin-left:0px;">* Si vous modifiez seulement cet �v�nement, celui-ci sera exclu de la s�rie d'�v�nement.</span>
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
                'label' => "Date de l'�venement",
                'type' => 'text',
                'class' => 'pure-form',
                'onchange' => "this.form.submit()"
            )); ?>
    </div>
        
    <div class="pure-u-1-4">
        <?=$this->Form->input('DebutEvenement',array(
                'label' => 'Heure de d�but',
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
                        'label' => "Type d'�v�nement",
                        'class' => 'pure-form',
                        'options' => $lstTypes,
                        'onchange' => "this.form.submit()"
                    ));
            } else { 
                echo "<label>Type d'�v�nement</label><span><b>".$event['VueEvenement']['NomType'].'</b></span>';
                echo $this->Form->input('TypeEvenement',array('type' => 'hidden', 'div' => false));
            } ?>
        </div>

        <? if(!empty($lstEquipesReserve) && $this->request->data['Evenement']['TypeEvenement'] < 3) { 
            $labelEquipe = '�quipe';
            if($this->request->data['Evenement']['TypeEvenement'] == 1) {
                $labelEquipe .= ' receveuse';
            } ?>
            <div class="pure-u-10-24">
                <?=$this->Form->input('IdEquipe',array(
                        'label' => $labelEquipe,
                        'class' => 'pure-form',
                        'options' => $lstEquipesReserve,
                        'empty' => '-- Choisir une �quipe --',
                        'onchange' => "this.form.submit()"
                    )); ?>
            </div>
    
            <? if($this->request->data['Evenement']['TypeEvenement'] == 2 && $this->Session->read('User.role') == 'admin') { ?>
                <div class="pure-u-14-24">
                    <?=$this->Form->input('IdCategorie',array(
                            'label' => 'Regroupements',
                            'class' => 'pure-form',
                            'options' => $lstCatPratique,
                            'empty' => array(0 => '-- Pour cette �quipe seulement --'),
                            'onchange' => "this.form.submit()" 
                        )); ?>
                </div>
            <? } ?>

        <? } elseif($this->request->data['Evenement']['TypeEvenement'] < 3) {
            $labelEquipe = '�quipe';
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
                    'label' => 'Partie � replacer',
                    'class' => 'pure-form',
                    'options' => $lstParties,
                    'empty' => '-- S�lectionner une partie --',
                    'onchange' => "this.form.submit()"
                )); ?>
            </div> 

            <? if($this->Session->read('User.role') == 'entraineur') { ?>
                <div class="pure-u-1">
                    <br/>* Lorsque vous faites une demande de changement d'horaire pour une partie de la LBAVR, 
                    il est important que l'heure de d�but de la r�servation soit la m�me que la partie.
                    <br/><br/>* Lorsque vous aurez re�u votre confirmation, vous devrez faire la demande sur le site de la LBAVR.
                </div>
            <? } ?>
        <? } elseif($this->request->data['Evenement']['TypeEvenement'] == 2) { ?>
            <? if($this->Session->read('User.role') == 'entraineur') { ?>
                <div class="pure-u-1"> 
                    <br/>* Notez que les replacements de parties auront la priorit� sur les r�servations de pratiques.
                <div>
            <? }
        }
    }
     
    if(!isset($partie) && $this->request->data['Evenement']['TypeEvenement'] == 3) { ?>
        <div class="pure-u-1">
            <?=$this->Form->input('DescEvenement',array('label' => 'Courte description *', 'size' => 30)); ?>
            <span style="font-size: 12px; margin-left: 0px;">* Cette description sera affich�e sur le calendrier.</span><br/><br/>
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
            echo $this->Html->link('R�initialiser',array('action' => 'reservation'), array('class' => 'pure-button'));
        } else {
            echo $this->Html->link('Annuler',array('controller' => 'pages', 'action' => 'home'), array('class' => 'pure-button'));
        } ?>
    </div>
    <?=$this->Form->end(); ?><br/><br/>
      
