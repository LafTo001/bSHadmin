<? if($this->Session->read('User.role') == 'entraineur') { ?>

<h1>Tutoriel de la section Entraineur</h1>

<div>
  <ul style="margin-left:15px;">
    <li><?=$this->Html->link('Cahier d\'équipe', 'home?tutoriel=cahier'); ?></li>
    <li><?=$this->Html->link('Réserver un terrain', 'home?tutoriel=reserver'); ?></li>
    <li><?=$this->Html->link('Chercher un réserviste', 'home?tutoriel=chercher'); ?></li>
    <li><?=$this->Html->link('Gestion des parties', 'home?tutoriel=parties'); ?></li>
    <li><?=$this->Html->link('Communications', 'home?tutoriel=communication'); ?></li>
    <li><?=$this->Html->link('Tournois BQ', 'home?tutoriel=tournoi'); ?></li>
  </ul>
</div>

<div>
<? if (isset($_GET['tutoriel']) && $_GET['tutoriel'] == 'reserver') { ?>
    <p><b>Réserver un terrain</b></p>
    
    <p>Pour réserver un terrain pour une pratique ou autre genre d'évenement, vous devez le faire à partir de cette section.<br/><br/>
          Vous pouvez consulter le calendrier des terrains pour voir les disponibilités. 
          Il se peut que le terrain ne soit disponible et que ça ne soit pas afficher.
          Afin de préparer une demande de réservation de terrain, il suffit de cliquer sur Réserver dans la barre de menu rouge.  
          Dans ce formulaire, vous devez entrer le terrain, la date, les heures de début et fin et le type d'événement. 
          Vous recevrez par courriel votre confirmation de demande et plus tard l'acceptation ou le refus de votre demande.<br/><br/>

          Lors d'une demande pour une reprise de match, il est important que le nom de l'équipe et le numéro du match soit indiqué.
          Ceci ne confirme pas le match pour la LBAVR, il faut quand même faire la demande à la ligue par la suite.
    </p>
    
<? } elseif (isset($_GET['tutoriel']) && $_GET['tutoriel'] == 'chercher') { ?>
    <p><b>Chercher un réserviste</b></p>
    
    <p>À venir</p>
    
<? } else { ?>
    <p><b>Cahier d'équipe</b></p>

    <p>C'est sur cette page que vous trouvrez la liste de vos joueurs.  En cliquant sur le nom du joueur, vous accédez à sa fiche qui comprend 
          les coordonnées des parents et la fiche-santé du joueur.</p>

    <p>Vous devrez ajouter vos entraineurs en cliquant sur "Ajouter un entraineur".  Si c'est un parent d'un joueur qui est dans la liste, vous 
          pourrez l'ajouter vous même.  N'oubliez pas d'inscrire son rôle (assistant ou gérant).  Si l'entraineur n'est pas dans la liste, contactez-moi 
          et indiquez-moi ses coordonnées.</p>

    <p>Le plus tôt possible, vous devrez sélectionner les numéros de chandail de vos joueurs en tenant compte des choix du joueur (2e colonne).  
          S'il vous manque des grandeurs de chandail, vous devez me contacter pour qu'on les ajoute à la fiche du joueur.  Vous devez aussi ajouter les numéros 
          et grandeurs de chandails de vos entraineurs adjoints.  Lorsque vous aurez entré tous les numéros de chandail de votre équipe, vous pourrez cliquer 
          sur le bouton "Confirmer les chandails".  De notre côté, nous verrons que vos chandails sont prêts pour la création.  En cas de changement de 
          dernière minute, vous devrez contacter <a href="mailto:lesproductionsgraphicart@maskatel.net">Dominic Fryer</a>.</p>
    
<? } ?> 
</div>
<br/>
<p>Pour du support informatique, vous pouvez contacter le webmaster par <a href="mailto:tomlafleur25@gmail.com">courriel</a>.</p>
<? }?>