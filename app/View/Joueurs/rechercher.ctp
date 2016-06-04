<!-- /joueurs/rechercher.ctp -->

<h1>Rechercher un joueur</h1>

<table id="tabdonnee" width="97%">
    <thead id="colhead">
        <tr>
            <th>Joueur</th>
            <th>No Membre</th>
            <th>Date de naissance</th>
            <th>Niveau</th>
            <th>Cat.</th>
            <th>Équipe</th>
        </tr>
    </thead>
    <tbody>                       
        <?php $count=0; ?>
        <?php foreach($joueurs as $joueur): ?>                
        <?php if(++$count % 2): echo '<tr>'; else: echo '<tr id="impair">' ?>
        <?php endif; ?>
            <td><?=$this->Html->link($joueur['VueJoueur']['nomPrenom'], array('action' => 'fiche', $joueur['VueJoueur']['idJoueur']));?></td>
            <td><?=$joueur['VueJoueur']['NoMembre'];?></td>
            <td><?=$joueur['VueJoueur']['DateNaissance'];?></td>
            <td><?=$joueur['VueJoueur']['NomCategorie'];?></td>
            <td><?=$joueur['VueJoueur']['Classe'];?></td>
            <td><?=$joueur['VueJoueur']['NomEquipe'];?></td>
        </td>
      </tr>
<? endforeach; ?>

        </tbody>
</table><br/>
<?php echo $this->Html->link("Retour à la liste des joueurs",
                                array('action' => 'index'), array('class' => 'pure-button pure-button-primary')); ?>
    