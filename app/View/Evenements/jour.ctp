<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Par mois',array('action' => 'calendrier', $jour['NoMois']));?>
            <th width="25%"><?=$this->Html->link('Par semaine',array('action' => 'semaine', $jour['NoSemaine']));?>
            <th width="25%"><?=$this->Html->link('Par jour',array('action' => 'jour',$jour['DateNumeric']));?>
            <th width="25%"><?=$this->Html->link('Réserver',array('action' => 'ajouter',$jour['DateNumeric']));?>
        </tr>
    </table>
</div>

<?=$this->Form->create('Terrain', array('url' => array('controller' => 'terrain', 'action' => 'changerTerrain')));
echo $this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI'])); ?>

<h1><?=$this->Html->image('glyphicons/15.png', array('url' => array('action' => 'jour',$jour['Veille']))); ?>
    <?=$this->Html->image('glyphicons/16.png', array('url' => array('action' => 'jour',$jour['Lendemain']))); ?>
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
    echo ' | '.$jour['DateFormat'];?>
</h1>

<table id="plage" width="88%">
    <tr>
        <th id="colhead">Heure</th>
        <th id="colhead" colspan="2">Événement</th>
    </tr>

<? foreach($jour['Events'] as $cle => $plage) { ?>
      <tr>
    <? if($cle % 2 == 0) { ?>
        <td id="pair" rowspan="2" style="width:100px; height:24px;"><?=$plage['heure']; ?></td>
    <? } ?>

    <? if(isset($plage['rowspan']) && $plage['rowspan'] > 0) { 
        foreach($plage['events'] as $event) { ?>
            <td id="<? if($event['Confirmation'] == 1 || $event['Confirmation'] == 3) echo 'reserve'; else echo 'non-confirm';?>" 
                rowspan="<?php echo $plage['rowspan'] ?>"><?=$event['Temps']; ?> &nbsp;
            <? if($this->Session->read('User.role') == 'terrain') {
                echo $this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                    array('url' => array('action' => 'reservation', $event['idEvenement']),
                        'title' => "Modifier l'événement",
                        'width' => '16px', 'height' => '16px')); 
            } elseif($event['EstDemandeur']) { 
                echo $this->Html->image('glyphicons/glyphicons_207_remove_2.png', 
                    array('url' => array('action' => 'supprimer', $event['idEvenement']),
                        'title' => "Annuler l'événement",
                        'width' => '16px', 'height' => '16px')); 
            } ?>
              <br/><?=$event['Description'] ?></br></td>

            <? /* if($plage['colAdd'] == 1) { ?>
            <td id="pair"><td>
            <? } */
        }
    } else { ?>
        <td id="pair" colspan="<?=$plage['colspan'] ?>"></td>
    <?php } ?>
      </tr>

<?php } ?>
    </table></br>

<?=$this->Html->link('Réserver une plage horaire',array('action' => 'ajouter',$jour['DateNumeric']),array('class' => 'pure-button pure-button-primary')); ?>
<br/><br/><br/>

