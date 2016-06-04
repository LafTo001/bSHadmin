<? header ( "Content-type: application/vnd.ms-excel" );
header("Content-Disposition: attachment; filename=Liste des joueurs.xls"); ?>

<table border="1">
    <tr>
        <th bgcolor="#CCCCCC" width="200">Nom</th>
        <th bgcolor="#CCCCCC" width="100">Date naissance</th>
        <th bgcolor="#CCCCCC" width="200">Adresse</th>
        <th bgcolor="#CCCCCC" width="150">Municipalité</th>
        <th bgcolor="#CCCCCC" width="100">Tel. maison</th>
        <th bgcolor="#CCCCCC" width="100">Tel. mobile</th>
        <th bgcolor="#CCCCCC" width="250">Courriels</th>
        <th bgcolor="#CCCCCC" width="80">No CAL</th>
        <th bgcolor="#CCCCCC" width="80">Exp. CAL</th>
        <th bgcolor="#CCCCCC" width="90">Catégorie</th>
        <th bgcolor="#CCCCCC" width="90">Choix No.</th>
        <th bgcolor="#CCCCCC" width="80">Grandeur</th>
        <th bgcolor="#CCCCCC" width="80">Classe</th>
        <th bgcolor="#CCCCCC" width="100">Équipe</th>
        <th bgcolor="#CCCCCC" width="80">Numéro</th>
    </tr>

<? foreach($joueurs as $joueur) { ?>
    <tr>
        <td><?=$joueur['VueJoueur']['nomPrenom'];?></td>
        <td><?=$joueur['VueJoueur']['DateNaissance'];?></td>
        <td><?=$joueur['VueJoueur']['Adresse'];?></td>
        <td><?=$joueur['VueJoueur']['Ville'];?></td>
        <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $joueur['VueJoueur']['TelMaison']);?></td>
        <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $joueur['VueJoueur']['TelMobile']);?></td>
        <td><?=$joueur['VueJoueur']['Courriels'];?></td>
        <td><?=$joueur['VueJoueur']['NoCAL'];?></td>
        <td><?=$joueur['VueJoueur']['DateExpCAL'];?></td>
        <td><?=$joueur['VueJoueur']['NomCategorie'];?></td>
        <td><?=$joueur['VueJoueur']['ChoixNumero'];?></td>
        <td><?=$joueur['VueJoueur']['Chandail'];?></td>
        <td><?=$joueur['VueJoueur']['Classe'];?></td>
        <td><?=$joueur['VueJoueur']['NomEquipe'];?></td>
        <td><?=$joueur['VueJoueur']['Numero'];?></td>
    </tr>
<? } ?>
</table>