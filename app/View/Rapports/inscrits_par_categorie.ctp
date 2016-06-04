<!-- /rapports/index.ctp -->

<? if($this->Session->read('User.nomComplet') == 'Tommy Lafleur') { ?>
    <div>
        <table id="menuhead" width="1024px" border="0" cellspacing="1">
            <tr>
                <th width="25%"><?=$this->Html->link('Rapports',array('controller' => 'rapports', 'action' => 'index'));?>
                <th width="25%"><?=$this->Html->link('Maintenance',array('controller' => 'rapports', 'action' => 'maintenance'));?>
                <th width="25%"><?=$this->Html->link('Configuration du site',array('controller' => 'rapports', 'action' => 'index'));?>
                <th width="25%"><?=$this->Html->link('Autres',array('controller' => 'rapports', 'action' => 'index'));?>
            </tr>
        </table>
    </div>
<? } ?>

<h1>Liste des rapports</h1>

<span><?=$this->Html->link('Liste de joueurs (Excel)', array('action' => 'listeJoueurs'));?></span>
<span><?=$this->Html->link("Inscrits par catégorie", array('action' => 'inscritsParCategorie'));?></span>
<span><?=$this->Html->link("Gestion des paiements", array('action' => 'paiements'));?></span>
<span><?=$this->Html->link("Carte accès loisirs", array('action' => 'carteAccesLoisirs'));?></span>

<h3>Nombre d'inscriptions par catégorie</h3>
<table id="tabdonnee" width="50%">
    <tr>
        <th>Categorie</th>
        <th>Classe</th>
        <th>Nombre d'inscription</th>
    </tr>
    
<?  $cmpt = 0;
    foreach($liste as $cat) {
        if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>';
            if($cat[0]['Ordre'] > 1) { ?>
                <td><b><?=$cat[0]['Categorie'];?></b></td>
                <td></td>
                <td style="text-align:center;"><b><?=$cat['T']['Count'];?></b></td>
            <? } else { ?>
                <td><?=$cat[0]['Categorie'];?></td>
                <td><?=$cat['T']['Classe'];?></td>
                <td style="text-align:center;"><?=$cat['T']['Count'];?></td>
            <? } ?>
        </tr>
    <? } ?>
</table>
<br/>

