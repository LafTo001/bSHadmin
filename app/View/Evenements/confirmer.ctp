<h1>Événements à confirmer</h1>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Terrain</th>
        <th>Date</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Équipe</th>
        <th>Demandeur</th>
        <th>Raison</th>
        <th>Action</th>
    </tr>
    
    <? $cmpt =0;
    if($this->Session->read('Terrain.nbConfirm') > 0) {
        foreach($listeDemandes as $demande) {
            if(++$cmpt%2 == 1) echo '<tr id="impair">'; else echo '<tr>'; ?>
                <td><?=$demande['VueEvenement']['NomTerrain'];?></td>
                <td><?=$demande['VueEvenement']['DateEvenement'];?></td>
                <td><?=$demande['VueEvenement']['DebutEvenement'];?></td>
                <td><?=$demande['VueEvenement']['FinEvenement'];?></td>
                <td><?=$demande['VueEvenement']['NomCompletEquipe'];?></td>
                <td><?=$demande['VueEvenement']['NomDemandeur'];?></td>
                <td><?=$demande['VueEvenement']['NomType'];?></td>
                <td><?=$this->Html->link('Accepter / Refuser',array('action' => 'reservation',$demande['VueEvenement']['idEvenement']));?></td>
            </tr>
        <? } 
    } else { ?>
            <tr><td colspan="8">Aucune demande de confirmation en attente</td></tr>
    <? }
?>
</table>