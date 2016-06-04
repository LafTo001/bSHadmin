<h1>Trouver un réserviste</h1>
	
<div class="pure-form pure-form-stacked pure-g">
<? if(!empty($listeParties)) { ?>
    <div class="pure-u-3-5">
        <?=$this->Form->input('idPartie',array(
                        'label' => '',
                        'class' => 'pure-form',
                        'options' => $listeParties,
                        'selected' => 'changerPartie/'.$this->Session->read('Partie.id'),
                        'empty' => 'Sélectionner la partie',
                        'onchange' => 'location = this.options[this.selectedIndex].value;'
        ));?>
    </div> 
<? } ?>
	  
      <div class="pure-u-2-5">
	<?=$this->Form->input('idEquipe',array(
                        'label' => '',
                        'class' => 'pure-form',
                        'options' => $listeEquipesReserviste,
                        'empty' => 'Sélectionner l\'équipe',
                        'selected' => 'changerEquipeReserviste/'.$this->Session->read('Reserviste.idEquipe'),
                        'onchange' => 'location = this.options[this.selectedIndex].value;'
        ));?>
    </div>
</div>

<? if($this->Session->read('Reserviste.idEquipe') > 0 && $this->Session->read('Partie.id') > 0) {
        if(empty($partiesJour)) { ?>
            <p>L'équipe ne joue pas durant la journée de ce match</p>
        <? } else {
		foreach($partiesJour as $partie) { ?>
                    <p><b>L'équipe joue à <?=$partie['VuePartie']['Heure'] ?> sur le terrain <?=$partie['VuePartie']['NomTerrain'] ?></b></p>
                <? }
       } ?>
        <br>
        <table id="tabdonnee" width="97%">
            <tr>
                <th id="colhead"></th>
                <th id="colhead">Nom</th>
                <th id="colhead">Date de naissance</th>
                <th id="colhead">Ville</th>
                <th id="colhead">Email</th>
                <th id="colhead">Maison</th>
                <th id="colhead">Mobile</th>
            </tr>

    <? if(!empty($joueurs)) {
        $cmpt = 0;
        foreach($joueurs as $joueur) {
            if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>';
                if($this->Session->read('Partie.id') > 0) { ?>
                    <td><center><?=$this->Html->image('glyphicons/glyphicons_190_circle_plus.png',
                                array('url' => array('action' => 'ajouterReservisteAlignement',
                                                     $joueur['VueJoueur']['idJoueur'],
                                                     $this->Session->read('Partie.id')),
                                'width' => '16px', 'height' => '16px')); ?>
                    </center></td>
                <? } ?>
                <td><?=$this->Html->link($joueur['VueJoueur']['nomComplet'],
                            array('controller' => 'joueurs', 'action' => 'ficheReserviste', 
                                    $joueur['VueJoueur']['idJoueur'],
                                    strtr($joueur['VueJoueur']['nomComplet'],
                                        '@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ',
                                        'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy_')));?>
                </td>
                <td><?=$joueur['VueJoueur']['DateNaissance'] ?></td>
                <td><?=$joueur['VueJoueur']['Ville'] ?></td>
                <td><a href="mailto:<?php echo $joueur['VueJoueur']['Courriel1'] ?>"><?php echo $joueur['VueJoueur']['Courriel1'] ?></a></td>
                <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $joueur['VueJoueur']['TelMaison']);?></td>
                <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $joueur['VueJoueur']['TelMobile']);?></td>
            </tr>
        <? } 
    } ?>
    </table><br/>
<? } ?>
