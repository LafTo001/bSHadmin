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
        <th>Cat�gorie</th>
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
<p>1 ch�que : ________ &nbsp; &nbsp; &nbsp; 2 ch�ques : ________ &nbsp; &nbsp; &nbsp; Argent comptant : ________ &nbsp; &nbsp; &nbsp;</p>
<p>- Un paiment le 24 mars 2016 et/ou 2e paiement le 28 avril 2016. <br/>
   - Le(s) ch�que(s) doit (doivent) �tre fait � l'ordre de : Baseball St-Hyacinthe</p>				
<br/><br/>

<p><b>IMPORTANT : Veuillez signer l'autorisation de participation</b><br/><br/>				
Baseball Saint-Hyacinthe ne peut s'engager � accepter tous les inscriptions �tant donn� que le nombre d'entra�neurs b�n�voles est parfois insuffisant, ou pour respecter le ratio de joueurs par �quipe, ou par mesure de s�curit� pour le joueur.  J'autorise mon enfant � participer et je suis conscient des risques inh�rents � la pratique du baseball et c'est en connaissance de cause que j'accepte de ne pas rendre Baseball Saint-Hyacinthe, et/ou ses entra�neurs responsables en cas d'accident.  J'accepte que mon enfant soit class� en fonction de ses aptitudes de jeu.	<br/>				
<br/><br/>
Signature (Parent ou tuteur) : _____________________________________________________________________<br/>					
</p>
<br/><br/>


<p><b>Nous avons besoin de parents b�n�voles</b><br/>
    L'organisation de Baseball Saint-Hyacinthe repose sur le b�n�volat.  Nous sommes toujours � la recherche de b�n�voles comme entraineurs, g�rantes, b�n�voles pour les �v�nements sp�ciaux ou membre du conseil d'administration.</p>

<table id="tabdonnee" width="97%">
    <tr>
        <th width="400px">Nom du parent</th>
        <th width="300px">R�le</th>
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