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

<h1>Liste des rapports</h1>

<span><?=$this->Html->link('Liste de joueurs (Excel)', array('action' => 'listeJoueurs'));?></span>
<span><?=$this->Html->link("Inscrits par catégorie", array('action' => 'inscritsParCategorie'));?></span>
<span><?=$this->Html->link("Gestion des paiements", array('action' => 'paiements'));?></span>
<span><?=$this->Html->link("Carte accès loisirs", array('action' => 'carteAccesLoisirs'));?></span>

<h3>Gestion des paiements</h3>
<table id="tabdonnee" width="80%">
    <tr>
        <th>Joueur</th>
        <th>Catégorie</th>
        <th>Solde</th>
        <th>Action</th>
    </tr>
    
<?  $cmpt = 0;
    foreach($joueurs as $joueur) {
        if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>'; ?>
            <td><?=$joueur['VueJoueur']['nomPrenom'];?></td>
            <td><?=$joueur['VueJoueur']['NomCategorie'];?></td>
            <td><?=$joueur['VueJoueur']['MontantInscription'];?></td>
            <td><?=$this->Html->link('Enregister le paiement', 
                array('controller' => 'joueurs', 'action' => 'inscription', $joueur['VueJoueur']['idJoueur'])); ?>
        </tr>
    <? } ?>
</table>
<br/>

