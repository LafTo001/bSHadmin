<? if($this->Session->read('User.role') == 'entraineur') { ?>

<h1>Tutoriel de la section Entraineur</h1>

<div>
  <ul style="margin-left:15px;">
    <li><?=$this->Html->link('Cahier d\'�quipe', 'home?tutoriel=cahier'); ?></li>
    <li><?=$this->Html->link('R�server un terrain', 'home?tutoriel=reserver'); ?></li>
    <li><?=$this->Html->link('Chercher un r�serviste', 'home?tutoriel=chercher'); ?></li>
    <li><?=$this->Html->link('Gestion des parties', 'home?tutoriel=parties'); ?></li>
    <li><?=$this->Html->link('Communications', 'home?tutoriel=communication'); ?></li>
    <li><?=$this->Html->link('Tournois BQ', 'home?tutoriel=tournoi'); ?></li>
  </ul>
</div>

<div>
<? if (isset($_GET['tutoriel']) && $_GET['tutoriel'] == 'reserver') { ?>
    <p><b>R�server un terrain</b></p>
    
    <p>Pour r�server un terrain pour une pratique ou autre genre d'�venement, vous devez le faire � partir de cette section.<br/><br/>
          Vous pouvez consulter le calendrier des terrains pour voir les disponibilit�s. 
          Il se peut que le terrain ne soit disponible et que �a ne soit pas afficher.
          Afin de pr�parer une demande de r�servation de terrain, il suffit de cliquer sur R�server dans la barre de menu rouge.  
          Dans ce formulaire, vous devez entrer le terrain, la date, les heures de d�but et fin et le type d'�v�nement. 
          Vous recevrez par courriel votre confirmation de demande et plus tard l'acceptation ou le refus de votre demande.<br/><br/>

          Lors d'une demande pour une reprise de match, il est important que le nom de l'�quipe et le num�ro du match soit indiqu�.
          Ceci ne confirme pas le match pour la LBAVR, il faut quand m�me faire la demande � la ligue par la suite.
    </p>
    
<? } elseif (isset($_GET['tutoriel']) && $_GET['tutoriel'] == 'chercher') { ?>
    <p><b>Chercher un r�serviste</b></p>
    
    <p>� venir</p>
    
<? } else { ?>
    <p><b>Cahier d'�quipe</b></p>

    <p>C'est sur cette page que vous trouvrez la liste de vos joueurs.  En cliquant sur le nom du joueur, vous acc�dez � sa fiche qui comprend 
          les coordonn�es des parents et la fiche-sant� du joueur.</p>

    <p>Vous devrez ajouter vos entraineurs en cliquant sur "Ajouter un entraineur".  Si c'est un parent d'un joueur qui est dans la liste, vous 
          pourrez l'ajouter vous m�me.  N'oubliez pas d'inscrire son r�le (assistant ou g�rant).  Si l'entraineur n'est pas dans la liste, contactez-moi 
          et indiquez-moi ses coordonn�es.</p>

    <p>Le plus t�t possible, vous devrez s�lectionner les num�ros de chandail de vos joueurs en tenant compte des choix du joueur (2e colonne).  
          S'il vous manque des grandeurs de chandail, vous devez me contacter pour qu'on les ajoute � la fiche du joueur.  Vous devez aussi ajouter les num�ros 
          et grandeurs de chandails de vos entraineurs adjoints.  Lorsque vous aurez entr� tous les num�ros de chandail de votre �quipe, vous pourrez cliquer 
          sur le bouton "Confirmer les chandails".  De notre c�t�, nous verrons que vos chandails sont pr�ts pour la cr�ation.  En cas de changement de 
          derni�re minute, vous devrez contacter <a href="mailto:lesproductionsgraphicart@maskatel.net">Dominic Fryer</a>.</p>
    
<? } ?> 
</div>
<br/>
<p>Pour du support informatique, vous pouvez contacter le webmaster par <a href="mailto:tomlafleur25@gmail.com">courriel</a>.</p>
<? }?>