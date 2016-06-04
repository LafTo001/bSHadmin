<!-- /equipes/fiche.ctp -->

<h1>Fiche des <?=$equipe['VueEquipe']['NomComplet'];?></h1>

<? if($equipe['VueEquipe']['ConfirmationChandail'] == 0) {
    if($this->Session->read('User.role') == 'entraineur') {
        echo $this->Html->link("Confirmer les chandails",array('action' => 'confirmerChandails'),array('class' => 'pure-button pure-button-primary'));
    } else { ?>
        <span>Le choix des numéros n'a pas été confirmé</span>
    <? } ?><br/><br/>
<? } ?>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Nom</th>
        <? if($this->Session->read('User.role') == 'admin' || $equipe['VueEquipe']['ConfirmationChandail'] == 1) { ?>
            <th># Membre</th>
        <? } else { ?>
            <th>Choix numéro</th>
        <? } ?>
        <th>#</th>
        <th>Chandail</th>
        <th>DDN</th>
        <th>Adresse</th>
        <th>Tel maison</th>
        <th>Tél mobile</th>
    </tr>

<?php if(isset($joueurs)) {
    foreach($joueurs as $joueur) { ?> 
    
        <?=$this->Form->create('Inscription', array('div' => false)); ?>
        <?=$this->Form->input('id', array('type' => 'hidden', 'value' => $joueur['VueJoueur']['idInscription'], 'div' => false)); ?>
        <tr>
            <td><?=$this->Html->link($joueur['VueJoueur']['nomPrenom'],array('controller' => 'joueurs','action' => 'fiche',$joueur['VueJoueur']['idJoueur']));?></td>
            <? if($equipe['VueEquipe']['ConfirmationChandail'] == 1) { ?>
                <td><?=$joueur['VueJoueur']['NoMembre'];?></td>
                <td><?=$joueur['VueJoueur']['Numero'];?></td>
            <? } else { ?>
                <td><?=$joueur['VueJoueur']['ChoixNumero'];?></td>
                <td><?=$this->Form->input('Numero',array(
                    'label' => '',
                    'options' => $numeros,
                    'empty' => array('' => ''),
                    'selected' => $joueur['VueJoueur']['Numero'],
                    'onchange' => "this.form.submit()"
                ));?></td>
            <? } ?>
            <td><?=$joueur['VueJoueur']['Chandail'];?></td> 
            <td><?=$joueur['VueJoueur']['DateNaissance'];?></td>
            <td><?=$joueur['VueJoueur']['Adresse'].', '.$joueur['VueJoueur']['Ville'];?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $joueur['VueJoueur']['TelMaison']);?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $joueur['VueJoueur']['TelMobile']);?></td>
        </tr>
        <?=$this->Form->end();?>
    <? } 
}?>
</table><br/>
	
<h2>Les entraineurs</h2>

<table id="tabdonnee" width="97%">
  <tr>
    <th>Nom</th>
    <th>Titre</th>
    <th># Membre</th>
    <th>#</th>
    <th>Chandail</th>
    <th>Adresse</th>
    <th>Tel maison</th>
    <th>Tél mobile</th>
  </tr>

<?php if(isset($entraineurs)) {
    $cmpt = 0;
    foreach($entraineurs as $entraineur) { ?>
        
        <?=$this->Form->create('Entraineur', array('div' => false)); ?>
        <?=$this->Form->input('id', array('type' => 'hidden', 'value' => $entraineur['VueEntraineur']['id'], 'div' => false)); ?>
        <tr>
            <td><?=$this->Html->link($entraineur['VueEntraineur']['NomPrenom'], 
                    array('controller' => 'parents', 'action' => 'edit', $entraineur['VueEntraineur']['IdParent'])); ?></td>
            <td><?=$entraineur['VueEntraineur']['NomTitre'] ?></td>
            <td><?=$entraineur['VueEntraineur']['NoMembre'] ?></td>
            <? if($equipe['VueEquipe']['ConfirmationChandail'] == 1) { ?>
                <td><?=$entraineur['VueEntraineur']['Numero'];?></td>
                <td><?=$entraineur['VueEntraineur']['Chandail'];?></td>
            <? } else { ?>
                <td><?=$this->Form->input('Numero',array(
                    'label' => '',
                    'options' => $numeros,
                    'empty' => array('' => ''),
                    'selected' => $entraineur['VueEntraineur']['Numero'],
                    'onchange' => "this.form.submit()"
                ));?></td>
                <td><?=$this->Form->input('Chandail',array(
                    'label' => '',
                    'options' => $taillesChandail,
                    'empty' => array('' => ''),
                    'selected' => $entraineur['VueEntraineur']['Chandail'],
                    'onchange' => "this.form.submit()"
                ));?></td>
            <? } ?>
            <td><?=$entraineur['VueEntraineur']['Adresse'].', '.$entraineur['VueEntraineur']['Ville'];?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $entraineur['VueEntraineur']['TelMaison']);?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $entraineur['VueEntraineur']['TelMobile']);?></td>
        </tr>
        <?=$this->Form->end();?>
    <? }
} ?>
</table><br/>

<? if($equipe['VueEquipe']['ConfirmationChandail'] == 0) { 
    echo $this->Html->link('Ajouter un entraineur',array('action' => 'ajoutEntraineur',$equipe['VueEquipe']['idEquipe']),array('class' => 'pure-button pure-button-primary'));
} 

//echo '/files/cahiers/'.date('Y').'/'.$equipe['VueEquipe']['NomCahier'];
if(file_exists('files/cahiers/'.date('Y').'/'.$equipe['VueEquipe']['NomCahier'])) { ?>
    <p><?=$this->Html->link("Imprimer le cahier d'équipe officiel", '/files/cahiers/'.date('Y').'/'.$equipe['VueEquipe']['NomCahier']); ?></p>
<? } ?>
<br/><br/>

