<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Par mois',array('action' => 'calendrier',$noMois));?></th>
            <th width="25%"><?=$this->Html->link('Par semaine',array('action' => 'semaine',$noSemaine));?></th>
            <th width="25%"><?=$this->Html->link('Par jour',array('action' => 'jour',$premierJour));?></th>
            <th width="25%"><?=$this->Html->link('Réserver',array('action' => 'ajouter',$premierJour));?></th>
        </tr>
    </table>
</div>

<?=$this->Form->create('Terrain', array('url' => array('controller' => 'terrain', 'action' => 'changerTerrain')));
echo $this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI'])); ?>

<h1><?=($noMois > 1) ? $this->Html->image('glyphicons/15.png', array('url' => array('action' => 'calendrier',$noMois-1))).' ' : ' '; ?>
    <?=($noMois < 12) ? $this->Html->image('glyphicons/16.png', array('url' => array('action' => 'calendrier',$noMois+1))).' ' : ' '; ?>
    <?=' &nbsp; Disponibilité de terrain :';
    if(isset($lstTerrains)) {
        echo $this->Form->input('idTerrain',array(
                    'label' => '',
                    'div' => false,
                    'options' => $lstTerrains,
                    'selected' => $this->Session->read('Terrain.id'),
                    'onchange' => "this.form.submit()"
        ));
        echo $this->Form->end();
    } else {
        echo ' '.$terrain['NomTerrain'];
    }
    echo ' | Mois : '.ucfirst($nomMois).' '.date('Y');?></h1>

<table class="mois" border="0" cellpadding="1" cellspacing="1">
    <tr>
        <th id="colhead">Lundi</th>
        <th id="colhead">Mardi</th>
        <th id="colhead">Mercredi</th>
        <th id="colhead">Jeudi</th>
        <th id="colhead">Vendredi</th>
        <th id="colhead">Samedi</th>
        <th id="colhead">Dimanche</th>

<?php
$i = 0;
foreach($jours as $jour) {
    if($i++ % 7 == 0) echo '</tr><tr>';
    if($jour['D']['NoMois'] != $noMois) { ?>
        <td> </td>
    <? }
    elseif($jour['D']['Reserve'] == true) { ?>
	<td class="occupe">
            <?=$this->Html->link($jour['D']['JourMois'],array('action' => 'jour',$jour['D']['date']), array('class' => 'date')); ?><br/>
            <? foreach($jour['D']['Events'] as $event): 
                echo '<br/>';
                if($event['VueEvenement']['Confirmation'] == 0) echo '<i>';
                echo $event['VueEvenement']['DebutEvenement'].' - '.$event['VueEvenement']['FinEvenement'].' ';
                if($event['VueEvenement']['TypeEvenement'] == 3) {
                    echo $event['VueEvenement']['DescEvenement'];
                } else {
                    echo $event['VueEvenement']['NomType'];
                }
                if($event['VueEvenement']['Confirmation'] == 0) echo '</i>';
            endforeach; ?>
        </td>
    <? } else { ?>
	<td class="non-occupe"><span class="date"><?php echo $jour['D']['JourMois'] ?></span></td>
    <? }
} ?>
    </tr>
</table><br/>

<?=$this->Html->link('Réserver une plage horaire',array('action' => 'ajouter'),array('class' => 'pure-button pure-button-primary')); ?>
<br/><br/><br/>

