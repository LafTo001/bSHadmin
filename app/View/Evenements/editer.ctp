<aside style="width: 324px; background: #EEEEEE; min-height:420px;">
      <table id="menuhead" style="width: 324px;"><tr><td><center>Disponibilité du terrain</center></td></tr></table>
      <p style="margin-left:0px;">Terrain : <b><?=$terrain['NomTerrain'] ?></b><br>Date : <b><?=$dateFormat ?></b></p>
<?php
if(!empty($listeEvents)) {
    foreach($listeEvents as $eventDesc) { ?>
	<p style="border: 2px solid #888888; padding:10px; margin-left: 20px; margin-right:20px;" ><?php echo $eventDesc ?></p>
<?  }
} else { ?>
    <br><p style="margin-left: 00px;" >Aucun événement cédulé</p>
<? } ?>
</aside>

<h1>Réservation de terrain</h1>     
        
<div class="pure-form pure-form-stacked pure-g">
        
        <div class="pure-u-1">
<? if(isset($listeTerrains)) { ?>
        <?=$this->Form->create('ddTerrain');
            echo $this->Form->input('idTerrain',array(
            'label' => 'Terrain',
            'class' => 'pure-form',
            'options' => $listeTerrains,
            'selected' => '/baseball/evenements/changerTerrain/'.$this->Session->read('Evenement.idTerrain'),
            'onchange' => 'location = this.options[this.selectedIndex].value;'
            ));
            echo $this->Form->end(null,array('type','hidden')); ?>
<? } else { ?>
          <label>Terrain</label>
          <span style="margin-left:0px; "><b><?=$terrain['NomTerrain'];?></b></span>
<? } ?>
        </div>

        <div class="pure-u-1-4">
            <?=$this->Form->create('ddMois');
            echo $this->Form->input('date',array(
            'label' => 'Mois',
            'class' => 'pure-form',
            'options' => $listeMois,
            'selected' => '/baseball/evenements/changerDate/'.$this->Session->read('Evenement.Date'),
            'onchange' => 'location = this.options[this.selectedIndex].value;'
            )); ?>
        </div>
        
        <div class="pure-u-3-4">
            <?=$this->Form->create('ddJour');
            echo $this->Form->input('date',array(
            'label' => 'Jour',
            'class' => 'pure-form',
            'options' => $listeJours,
            'selected' => '/baseball/evenements/changerDate/'.$this->Session->read('Evenement.Date'),
            'onchange' => 'location = this.options[this.selectedIndex].value;'
            )); ?>
        </div>
        
        <div class="pure-u-1-4">
            <?=$this->Form->create('ddDebut');
                echo $this->Form->input('debut',array(
                'label' => 'Heure de début',
                'class' => 'pure-form',
                'options' => $listeHeuresDebut,
                'selected' => '/baseball/evenements/changerHeureDebut/'.$this->Session->read('Evenement.HeureDebut'),
                'onchange' => 'location = this.options[this.selectedIndex].value;'
            ));
            echo $this->Form->end(null,array('type','hidden')); ?>
        </div>
          
        <div class="pure-u-3-4">
            <?=$this->Form->create('ddFin');
                echo $this->Form->input('fin',array(
                'label' => 'Heure de fin',
                'class' => 'pure-form',
                'options' => $listeHeuresFin,
                'selected' => '/baseball/evenements/changerHeureFin/'.$this->Session->read('Evenement.HeureFin'),
                'onchange' => 'location = this.options[this.selectedIndex].value;'
            ));
            echo $this->Form->end(null,array('type','hidden')); ?>
        </div>

	<div class="pure-u-1">  
          <?=$this->Form->create('ddType');
                echo $this->Form->input('TypeEvenement',array(
                'label' => 'Type d\'événement',
                'class' => 'pure-form',
                'options' => $listeTypes,
                'selected' => '/baseball/evenements/changerType/'.$this->Session->read('Evenement.Type'),
                'onchange' => 'location = this.options[this.selectedIndex].value;'
            ));
            echo $this->Form->end(null,array('type','hidden')); ?>
        </div>

<? if(isset($listeEquipes) && $this->Session->read('Evenement.Type') < 3) { ?>
        <div class="pure-u-1">
            <?=$this->Form->create('ddEquipe');
                echo $this->Form->input('idEquipe',array(
                'label' => 'Équipe',
                'class' => 'pure-form',
                'options' => $listeEquipes,
                'selected' => '/baseball/evenements/changerEquipe/'.$this->Session->read('Evenement.IdEquipe'),
                'onchange' => 'location = this.options[this.selectedIndex].value;'
            ));
            echo $this->Form->end(null,array('type','hidden')); ?>
        </div>

    <?php } /*elseif($type < 3) { ?>
        <div class="pure-u-1"> 
          <label>Équipe<?php if($type == 1) echo ' receveuse' ?> : <b><?php echo $equipe ?></b></label>
        </div><br/>
    <?php } */?>

<?= $this->Form->create('Evenement',array('action' => 'ajouter')); ?>

<? if($this->Session->read('Evenement.Type') == 1) { ?>
        <div class="pure-u-1">
            <?=$this->Form->input('NoPartie',array('label' => 'No de partie', 'size' => 5)); ?>
        </div>
<? } elseif($this->Session->read('Evenement.Type') == 3) { ?>
        <div class="pure-u-1">
            <?=$this->Form->input('DescEvenement',array('label' => 'Raison', 'size' => 50)); ?>
        </div>
<?php } ?>
	  
        <div class="pure-u-1"> 
            <?=$this->Form->input('Commentaire',
                    array('label' => 'Commentaires :', 'cols' => '80', 'rows' => '5')); ?>
        </div>
    </div><br/>        
        <div class="pure-u-1">
	  <button type="submit" class="pure-button pure-button-primary" value="Submit">Envoyer la demande</button>
	  <?=$this->Html->link('Réinitialiser',array('action' => 'clear'), array('class' => 'pure-button'));?>
        </div>
	<?= $this->Form->end(); ?>