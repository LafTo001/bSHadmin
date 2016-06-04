<!-- /parents/fiche.ctp -->
<? //<span>L'inscription des joueurs pour la prochaine saison est hors-service pour quelques heures, veuillez revenir un peu plus tard.</span> ?>

<h1>Informations du parent</h1>

<h2>Parent : <?=$parent['Prenom'].' '.$parent['NomFamille'];?></h2>
<span>Adresse : <?=$parent['Adresse'].', '.$parent['Ville'].', '.preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $parent['CodePostal']);?></span></br><br/>
<span>Tel Maison : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent['TelMaison']);?></span><br/>
<span>Tel Mobile : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent['TelMobile']);?></span><br/>
<span>Tel Travail : <?=$parent['TelTravail'];?></span><br/><br/>
<span>Courriel : <?=$this->Text->autoLinkEmails($parent['Courriel1']); 
                    if($parent['Courriel2']) echo ', '.$this->Text->autoLinkEmails($parent['Courriel2']);
                    if($parent['Courriel3']) echo ', '.$this->Text->autoLinkEmails($parent['Courriel3']);?></span></br></br>

<?=$this->Html->link('Modifier les coordonnées',
        array('action' => ($this->Session->read('User.role') == 'admin') ? 'edit/'.$parent['id'] : 'edit'), 
        array('class' => 'pure-button pure-button-primary'));
if($this->Session->read('User.role') == 'admin') { 
    echo $this->Html->link('Redémarrer la recherche',array('action' => 'rechercher'), array('class' => 'pure-button'));
} 

if($joueurs) { ?>
    <br/><br/>
    <h2>Liste des enfants enregistrés</h2>
    
    <? if($parent['id'] == $this->Session->read('User.IdParent')) { ?>
        <span style="font-size: 12px;">* Prenez le temps de réviser la fiche de joueur afin de vérifier qu'elle 
            est conforme pour la prochaine saison (Carte d'accès loisirs, allergies, maladies)</span>
    <? } ?>
    
    <table id="tabdonnee" width="97%">
      <tr>
        <th>Les enfants</th>
        <th>Date de naissance</th>
        <th>Catégorie <?=$annee;?></th>
        <th>Paiement</th>
        <th></th>
      </tr>
    <? $count = 0; ?>
    <? foreach($joueurs as $joueur) { ?>
        <? if(++$count % 2) echo '<tr>'; else echo '<tr id="impair">'; ?>
        <td><?=$this->Html->link($joueur['VueJoueur']['nomPrenom'],array('controller' => 'joueurs', 'action' => 'fiche', $joueur['VueJoueur']['idJoueur']), array('title' => 'Consulter la fiche du joueur'));?></td>
        <td><?=$joueur['VueJoueur']['DateNaissance'];?></td>
        <? if($joueur['VueJoueur']['Reinscrit'] == 1) { ?>
            <td><?=$joueur['VueJoueur']['NomCategorie'];?></td>
            <td><?=($joueur['VueJoueur']['Paiement'] == 0) ? 'Non reçu' : 'Reçu'; ?>
            <td><?=$this->Html->link("Modifier l'inscription",array('controller' => 'joueurs', 'action' => 'inscription', $joueur['VueJoueur']['idJoueur']));?></td>
        <? } else { ?>
            <td></td>
            <td></td>
            <td><?=$this->Html->link("Renouveller l'inscription",array('controller' => 'joueurs', 'action' => 'inscription', $joueur['VueJoueur']['idJoueur']));?></td>
        <? } ?>
    </tr>
    <? } ?>
    </table>
<? } ?>
<br/><br/>

<?=$this->Html->link('Ajouter un joueur',
        array('controller' => 'joueurs', 'action' => ($this->Session->read('User.role') == 'admin') ? 'ajout/'.$parent['id'] : 'ajout'), 
        array('class' => 'pure-button pure-button-primary'));

if(($parent['id'] == $this->Session->read('User.idParent') || $this->Session->read('User.role') == 'admin') && $inscrits > 0) { 
    echo $this->Html->link('Formulaire de paiement',array('action' => 'paiement', ($this->Session->read('User.role') == 'admin') ? $parent['id'] : null), array('class' => 'pure-button pure-button-primary', 'target' => '_blank'));
} 
?>
<br/><br/>

<? if($parent['id'] == $this->Session->read('User.idParent') && $joueurs) { ?>
    <p>**** Vous devez envoyer votre paiement aux loisirs Douville, 5065 rue Gouin, Saint-Hyacinthe, J2S 6W2. <br/>
        Le chèque doit être fait à l'ordre de Baseball St-Hyacinthe et il est très important de joindre le formulaire de paiement signé.
    </p>
<? } ?>

<br/>