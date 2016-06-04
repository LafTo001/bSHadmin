<!-- /parties/horaire.ctp -->
<? if($nomCategorie != '') { ?>
    <div id="headerEquipe" style="background: url(/bshadmin/img/<?=$nomEquipe?>_head.jpg);">
        <br/>
        <ul style="text-align: right; margin-right:25px;">   
            <li><?=$this->Html->link('Horaire des matchs', array('controller' => 'parties', 'action' => 'horaire',$nomCategorie,$classe,$nomEquipe)); ?></li>
            <li><? //=$this->Html->link("Communiqués de l'équipe", array('controller' => 'communiques', 'action' => 'index',$nomCategorie,$classe,$nomEquipe)); ?></li>
        </ul>
    </div>
<? } ?>
    
<h1>Horaire des <?=$equipe['VueEquipe']['NomComplet'];?></h1>

<table id="tabdonnee" border="0" cellpadding="2" cellspacing="1" width="95%">
    <tr>
        <th id="colhead">Ligue</th>
        <th id="colhead">#</th>
        <th id="colhead">Jour</th>
        <th id="colhead">Date</th>
        <th id="colhead">Heure</th>
        <th id="colhead">Visiteur</th>
        <th id="colhead">Pts</th>
        <th id="colhead">Receveur</th>
        <th id="colhead">Pts</th>
        <th id="colhead">Terrain</th>
    </tr>
<?php
$cmpt = 0;
foreach($parties as $partie)
{
    if(++$cmpt%2 == 1) echo '<tr id="impair">'; else echo '<tr>'; ?>

        <td><?=$partie['VuePartie']['NomLigue'];?></td>
        <td><?=$partie['VuePartie']['NoPartie'];?></td>
        <td><?=$partie['VuePartie']['JourSemaine'];?></td>
        <td><?=$partie['VuePartie']['Date'];?></td>
        <td><?=$partie['VuePartie']['Heure'];?></td>
        <td><?=$partie['VuePartie']['NomEquipeVisiteur'];?></td>
        <td><?=$partie['VuePartie']['PointsVisiteur'];?></td>
        <td><?=$partie['VuePartie']['NomEquipeReceveur'];?></td>
        <td><?=$partie['VuePartie']['PointsReceveur'];?></td>
        <td><?=$partie['VuePartie']['NomTerrain'];?></td>
    </tr>
<?php } ?>

    </table></br>