<!-- /parents/fiche.ctp -->

<script type="text/javascript">
function imprimer_page(){
  window.print();
}
</script>

<br/>
<h1>Baseball St-Hyacinthe : Formulaire de paiement</h1>

<aside>
    <form>
      <input id="impression" name="impression" type="button" onclick="imprimer_page()" value="Imprimer ce formulaire" />
    </form>
</aside>

<h2>Parent : <?=$parent['Prenom'].' '.$parent['NomFamille'];?></h2>
<span>Adresse : <?=$parent['Adresse'].', '.$parent['Ville'].', '.preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $parent['CodePostal']);?></span></br><br/>
<span>Tel Maison : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent['TelMaison']);?></span><br/>
<span>Tel Mobile : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent['TelMobile']);?></span><br/>
<span>Tel Travail : <?=$parent['TelTravail'];?></span><br/><br/>
<span>Courriel : <?=$this->Text->autoLinkEmails($parent['Courriel1']); 
                    if($parent['Courriel2']) echo ', '.$this->Text->autoLinkEmails($parent['Courriel2']);
                    if($parent['Courriel3']) echo ', '.$this->Text->autoLinkEmails($parent['Courriel3']);?></span></br></br>

<h2>Liste des inscrits pour la saison <?=$annee; ?></h2>
<table id="tabdonnee" width="97%">
    <tr style="text-align: left;">
        <th>Les enfants</th>
        <th>No. membre BQ</th>
        <th>Date de naissance</th>
        <th>Catégorie</th>
        <th>Solde</th>
    </tr>
    
    <? $total = 0; 
    foreach($joueurs as $joueur) { ?>
        <tr>
            <td><?=$joueur['VueJoueur']['nomComplet'];?></td>
            <td><?=$joueur['VueJoueur']['NoMembre'];?></td>
            <td><?=$joueur['VueJoueur']['DateNaissance'];?></td>
            <td><?=$joueur['VueJoueur']['NomCategorie'];?></td>
            <td style="text-align: right; margin-right: 5px;"><?=$joueur['VueJoueur']['MontantInscription'].'.00 $';?></td>
        </tr>
        <? $total += $joueur['VueJoueur']['MontantInscription'];
    } ?>
    <tr>
        <th colspan="3"></th>
        <th>Total</th>
        <th style="text-align: right; margin-right: 5px;"><?=$total.'.00 $'; ?></th>
    </tr>
    </table>
<br>

<p><b>Mode de paiement : </b></p>
<p>1 chèque : ________ &nbsp; &nbsp; &nbsp; 2 chèques : ________ &nbsp; &nbsp; &nbsp; Argent comptant : ________ &nbsp; &nbsp; &nbsp;</p>
<p>- Un paiment le 24 mars 2016 et/ou 2e paiement le 28 avril 2016. <br/>
   - Le(s) chèque(s) doit (doivent) être fait à l'ordre de : Baseball St-Hyacinthe</p>				
<br/><br/>

<p><b>IMPORTANT : Veuillez signer l'autorisation de participation</b><br/><br/>				
Baseball Saint-Hyacinthe ne peut s'engager à accepter tous les inscriptions étant donné que le nombre d'entraîneurs bénévoles est parfois insuffisant, ou pour respecter le ratio de joueurs par équipe, ou par mesure de sécurité pour le joueur.  J'autorise mon enfant à participer et je suis conscient des risques inhérents à la pratique du baseball et c'est en connaissance de cause que j'accepte de ne pas rendre Baseball Saint-Hyacinthe, et/ou ses entraîneurs responsables en cas d'accident.  J'accepte que mon enfant soit classé en fonction de ses aptitudes de jeu.	<br/>				
<br/><br/>
Signature (Parent ou tuteur) : _____________________________________________________________________<br/>					
</p>
<br/><br/>


<p><b>Nous avons besoin de parents bénévoles</b><br/>
    L'organisation de Baseball Saint-Hyacinthe repose sur le bénévolat.  Nous sommes toujours à la recherche de bénévoles comme entraineurs, gérantes, bénévoles pour les événements spéciaux ou membre du conseil d'administration.</p>

<table id="tabdonnee" width="97%">
    <tr>
        <th width="400px">Nom du parent</th>
        <th width="300px">Rôle</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

<br/><br/>

<center><h1>Retourner ce formulaire et le paiement au 5065 Gouin St-Hyacinthe, Qc  J2S 1E3</h1></center>

<br/><br/>