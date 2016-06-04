<h1>Calendrier de la saison Rallye Cap</h1>

<table class="mois" border="0" cellpadding="1" cellspacing="1">
    <tr style="height:50px;">
        <th><?=($noMois > 4) ? $this->Html->image('glyphicons/15.png', array('url' => array('action' => 'calendrier',$noMois-1))).' ' : ' '; ?></th>
        <th colspan="5"><?=ucfirst($nomMois).' '.date('Y');?></th>
        <th><?=($noMois < 8) ? $this->Html->image('glyphicons/16.png', array('url' => array('action' => 'calendrier',$noMois+1))).' ' : ' '; ?></th>
    </tr>
    
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
	<td class="occupe" title="<?=substr($jour['D']['Event']['VueEvenement']['Commentaire'],0,100); ?>">
            <?=$this->Html->link($jour['D']['JourMois'],
                array('action' => 'evenement',$jour['D']['Event']['VueEvenement']['idEvenement']), array('class' => 'date')); ?><br/>
            <?=$this->Html->link($jour['D']['Event']['VueEvenement']['DebutEvenement'].' - '.$jour['D']['Event']['VueEvenement']['FinEvenement'],
                array('action' => 'evenement',$jour['D']['Event']['VueEvenement']['idEvenement']), array('class' => 'lienEvenement')); ?><br/>
            <?=$this->Html->link($jour['D']['Event']['VueEvenement']['NomTerrain'],
                array('action' => 'evenement',$jour['D']['Event']['VueEvenement']['idEvenement']), array('class' => 'lienEvenement')); ?><br/>
            <?=$this->Html->link(str_replace("Rallye Cap : ", "", $jour['D']['Event']['VueEvenement']['DescEvenement']),
                array('action' => 'evenement',$jour['D']['Event']['VueEvenement']['idEvenement']), array('class' => 'lienEvenement')); ?>    
        </td>
    <? } else { ?>
	<td class="non-occupe"><span class="date"><?php echo $jour['D']['JourMois'] ?></span></td>
    <? }
} ?>
    </tr>
</table><br/>

