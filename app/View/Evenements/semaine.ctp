<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Par mois',array('action' => 'calendrier',$noMois));?>
            <th width="25%"><?=$this->Html->link('Par semaine',array('action' => 'semaine',$noSemaine));?>
            <th width="25%"><?=$this->Html->link('Par jour',array('action' => 'jour',$premierJour));?>
            <th width="25%"><?=$this->Html->link('Réserver',array('action' => 'ajouter',$premierJour));?>
        </tr>
    </table>
</div>

<?=$this->Form->create('Terrain', array('url' => array('controller' => 'terrain', 'action' => 'changerTerrain')));
echo $this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI'])); ?>

<h1><?=$this->Html->image('http://www.baseballsthyacinthe.com/images/glyphicons/15.png', array('url' => array('action' => 'semaine',$noSemaine-1))); ?>
    <?=$this->Html->image('http://www.baseballsthyacinthe.com/images/glyphicons/16.png', array('url' => array('action' => 'semaine',$noSemaine+1))); ?>
    <?=' &nbsp; Disponibilité de terrain :';
    if(isset($lstTerrains)) {
        echo $this->Form->input('idTerrain',array(
                    'label' => '',
                    'div' => false,
                    'options' => $lstTerrains,
                    'selected' => $this->Session->read('Terrain.id'),
                    'onchange' => "this.form.submit()"
        ));
    } else {
        echo ' '.$terrain['NomTerrain'];
    }
    echo ' | Semaine du : '.$jourFormat;?></h1>

<table id="plage" cellpadding="0" cellspacing="0" width="97%">
    <tr>
        <th id="colhead">Heure</th>
    <?php
    //entête du tableau selon le role de l'usager
    foreach($jours as $cle => $jour) { ?>
        <th id="colhead">
            <?=$this->Html->link($jour['VueDate']['JourSemaineFormat'],array('action' => 'jour',$jour['VueDate']['date']));?>
        </th>
    <?php } ?>
      </tr>
      <td width="60px">
	<table id="heure" cellpadding="1" cellspacing="1" width="100%">

    <?php for ($h = 8; $h < 22; $h++) {
        $minutes30 = array('00','30');
        foreach($minutes30 as $minute) { ?>
            <tr><td><?php echo $h.'h'.$minute ?></td></tr>
    <?php }
    } //fin foreach JourHead ?>
	</table></td>

    <?php foreach($jours as $jour) { ?>
	<td width="150px">
          <table id="plage" cellpadding="1" cellspacing="1" width="100%">
  
        <?php foreach($jour['VueDate']['Events'] as $plage) { //var_dump($plage);?>
            <tr>
                
            <?php if(isset($plage['rowspan']) && $plage['rowspan'] > 0) { 
                foreach($plage['events'] as $event) { ?>
                    <td id="<? if($event['Confirmation'] == 1 || $event['Confirmation'] == 3) 
                                    echo 'reserve'; else echo 'non-confirm';?>"
                        rowspan="<?=$plage['rowspan'] ?>"><?=$event['Temps']; ?> &nbsp;
                    <? if($this->Session->read('User.role') == 'terrain') {
                        echo $this->Html->image('http://www.baseballsthyacinthe.com/images/glyphicons/glyphicons_030_pencil.png', 
                            array('url' => array('action' => 'editer', $event['idEvenement']),
                                'width' => '16px', 'height' => '16px'));
                    } ?>
                        <br/><?=$event['Description'] ?></br></td>
                    </td>
                <? } ?>     
                <td id="pair"></td>
                
            <? } else { ?>
              <td id="pair" colspan="<?=$plage['colspan'] ?>"></td>
            <? } ?> 
            </tr>
        <? } ?>
		  </table>
		</td>
<?php
	}
?>
	  </tr>
	</table><br/>

<?=$this->Html->link('Réserver une plage horaire',array('action' => 'ajouter'),array('class' => 'pure-button pure-button-primary')); ?>
<br/><br/><br/>

