<h1>Liste des s�ries d'�v�nement</h1>

<table id="tabdonnee" width="97%">
    <thead id="colhead">
        <tr>
            <th>Terrain</th>
            <th>Description</th>
            <th>Jour semaine</th>
            <th>Heure d�but</th>
            <th>Heure fin</th>
            <th>Date d�but</th>
            <th>Date fin</th>
            <th>Actions</th>
        </tr>
    </thead>
    
<? if(!empty($series)) {
    $count = 0;
    foreach($series as $serie): 
        if(++$count % 2): echo '<tr>'; else: echo '<tr id="impair">';
        endif; ?>
            <td><?=$serie['VueSerie']['NomTerrain'];?></td>
            <td><?=$serie['VueSerie']['DescriptionSerie'];?></td>
            <td><?=$serie['VueSerie']['nomJour'];?></td>
            <td><?=$serie['VueSerie']['HeureDebut'];?></td>
            <td><?=$serie['VueSerie']['HeureFin'];?></td>
            <td><?=$serie['VueSerie']['DateDebut'];?></td>
            <td><?=$serie['VueSerie']['DateFin'];?></td>
            <td><?=$this->Html->link('�diter',array('action' => 'formulaire', $serie['VueSerie']['idSerie'])).' | '.
                   $this->Html->link('Supprimer',array('action' => 'supprimer', $serie['VueSerie']['idSerie']));?></td>
        </tr>
    <? endforeach;
 } else { ?>
    <tr><td colspan="7">Aucune s�rie d'�v�n�ment enregistr�e</td></tr>
<? } ?>
</table><br/><br/>

<?php echo $this->Html->link("Ajouter une s�rie", array('action' => 'formulaire'),
                                array('class' => 'pure-button pure-button-primary')); ?>