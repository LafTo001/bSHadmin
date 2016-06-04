<!-- /tournois/parties.ctp -->
<? //var_dump($parties); ?>
<h1> <?=$tournoi['VueTournoi']['NomTournoi'];?> : Parties enregistées</h1>

<table id="tabdonnee" border="0" cellpadding="2" cellspacing="1" width="97%">
    <tr>
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
    
    <p><?=$this->Html->link('Ajouter une partie à ce tournoi',array('controller' => 'parties', 'action' => 'ajouter',$tournoi['VueTournoi']['idLigue']));?></p>
    <br/><br/>