<!-- /rapports/index.ctp -->

<? if($this->Session->read('User.nomComplet') == 'Tommy Lafleur') { ?>
    <div>
        <table id="menuhead" width="1024px" border="0" cellspacing="1">
            <tr>
                <th width="25%"><?=$this->Html->link('Rapports',array('controller' => 'rapports', 'action' => 'index'));?>
                <th width="25%"><?=$this->Html->link('Maintenance',array('controller' => 'rapports', 'action' => 'maintenance'));?>
                <th width="25%"><?=$this->Html->link('Configuration du site',array('controller' => 'rapports', 'action' => 'configs'));?>
                <th width="25%"><?=$this->Html->link('Autres',array('controller' => 'rapports', 'action' => 'index'));?>
            </tr>
        </table>
    </div>
<? } ?>

<br/>
<?=$this->Html->link("Chargement complet des parties",
                                array('controller' => 'dataloads', 'action' => 'extrairePartiesLBAVR',1),
                                array('class' => 'pure-button pure-button-primary')); ?>

<h1>Liste des parties à retirer</h1>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Catégorie</th>
        <th>Classe</th>
        <th># Partie</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Visiteur</th>
        <th>Receveur</th>
        <th>Terrain</th>
        <th></th>
    </tr>
    
<? if(empty($parties)) { ?>
    <tr><td colspan="9">Aucune partie à supprimer</td></tr>
<? } else { 
    $cmpt =0;
    foreach($parties as $partie) {
        if(++$cmpt%2 == 1) echo '<tr id="impair">'; else echo '<tr>'; ?>
        <td><?=$partie['NomCategorie'];?></td>
        <td><?=$partie['Classe'];?></td>
        <td><?=$partie['NoPartie'];?></td>
        <td><?=$partie['Date'];?></td>
        <td><?=$partie['Heure'];?></td>
        <td><?=$partie['NomEquipeVisiteur'];?></td>
        <td><?=$partie['NomEquipeReceveur'];?></td>
        <td><?=$partie['NomTerrain'];?></td>
        <td><?=$this->Html->link("Supp",
                    array('controller' => 'dataloads', 'action' => 'supprimerPartie',$partie['idPartie']),
                    array('class' => 'pure-button pure-button')); ?>
        </td>
    <? } 
} ?>
    </tr>
</table>
