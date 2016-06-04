<!-- /parents/recu.ctp -->

<h1>Impression du reçu pour crédit d'impôt</h1>

<h2>Parent : <?=$parent['Prenom'].' '.$parent['NomFamille'];?></h2>
<span>Adresse : <?=$parent['Adresse'].', '.$parent['Ville'].', '.preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $parent['CodePostal']);?></span></br><br/>
<span>Tel Maison : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent['TelMaison']);?></span><br/>
<span>Tel Mobile : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent['TelMobile']);?></span><br/>
<span>Tel Travail : <?=$parent['TelTravail'];?></span><br/><br/>
<span>Courriel : <?=$this->Text->autoLinkEmails($parent['Courriel1']); 
                    if($parent['Courriel2']) echo ', '.$this->Text->autoLinkEmails($parent['Courriel2']);
                    if($parent['Courriel3']) echo ', '.$this->Text->autoLinkEmails($parent['Courriel3']);?></span></br></br>

<h2>Liste des enfants enregistrés</h2>
    
<table id="tabdonnee" width="97%">
  <tr>
    <th>Les enfants</th>
    <th>Date de naissance</th>
    <th>Catégorie <?=$saison;?></th>
    <th></th>
  </tr>
<? $count = 0; ?>
<? foreach($joueurs as $joueur) { ?>
    <? if(++$count % 2) echo '<tr>'; else echo '<tr id="impair">'; ?>
    <td><?=$joueur['VueImpot']['NomPrenom'];?></td>
    <td><?=$joueur['VueImpot']['DateNaissance'];?></td>
    <td><?=$joueur['VueImpot']['NomCategorie'];?></td>
    <td><?=$this->Html->link("Imprimer le reçu", 
        array('controller' => 'joueurs', 'action' => 'recuImpot', $joueur['VueImpot']['idInscription']), array('target' => '_blank'));?></td>
</tr>
<? } ?>
</table>