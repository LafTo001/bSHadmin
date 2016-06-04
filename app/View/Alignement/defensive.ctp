<div>
    <table id="menuhead" width="1024px" cellspacing="1" cellpadding="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Gestion de parties',array('action' => 'gestion'));?></th>
            <th width="25%"><?=$this->Html->link('Alignement des joueurs',array('action' => 'frappeurs'));?></th>
            <th width="25%"><?=$this->Html->link('Positions défensives',array('action' => 'defensive'));?></th>
            <th width="25%"><?=$this->Html->link("Imprimer l'alignement",array('action' => 'feuilleAlignement',
                                                                        $this->Session->read('Partie.id'),
                                                                        $this->Session->read('Equipe.id')
            ));?></th>
        </tr>
    </table>
</div><br/>

<div class="pure-form pure-form-stacked pure-g">

<? if(!empty($listeParties)) {
    echo $this->Form->create('Partie', array('url' => array('controller' => 'parties', 'action' => 'changerPartie')));
    echo $this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI'])); ?>
    <div class="pure-u-1">
        <?=$this->Form->input('idPartie',array(
                        'label' => 'Sélection de la partie',
                        'class' => 'pure-form',
                        'options' => $listeParties,
                        'selected' => $this->Session->read('Partie.id'),
                        'onchange' => "this.form.submit()"
        ));?>
    </div>
    <?=$this->Form->end();?>
<? } ?>
    
</div>

<h1>Gestion des parties</h1>

<span>No. Partie: <?=$partie['P']['NoPartie'];?></span><br/>
<span>Division: <?=$partie['P']['NomCategorie'].' '.$partie['P']['Classe'];?></span><br/>
<span>Date: <?=$partie['P']['DateFormat'].' '.$partie['P']['Heure'];?></span><br/><br/>

<span>Nom de l'équipe: <?=$partie[0]['NomEquipe'];?></span><br/>
<span>Nom de l'opposant: <?=$partie[0]['NomOpposant'];?></span><br/><br/>

<h1>Positions défensives</h1>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Ordre</th>
        <th>Joueur</th>
<? for($m = 1; $m <= 6; $m++) { ?>
        <th>Manche #<?php echo $m ?></th>
<? } ?>
    </tr>

    <? foreach($alignements as $joueur) { ?>
        <tr>
            <td><?=$joueur['VueAlignement']['Ordre'];?></td>
            <td><?=$joueur['VueAlignement']['NomCompletJoueur'];?></td>
            <? for($m = 1; $m <= 6; $m++) { ?>
            <td>
                <?=$this->Form->create('Alignement', array('div' => false)); ?>
                <?=$this->Form->input('IdJoueur', array('type' => 'hidden', 'value' => $joueur['VueAlignement']['IdJoueur'], 'div' => false)); ?>
                <?=$this->Form->input('Manche', array('type' => 'hidden', 'value' => $m, 'div' => false)); ?>
                <?=$this->Form->input('Position', array(
                        'label' => false,
                        'options' => $joueur['Select'][$m],
                        'selected' => $joueur['VueAlignement']['Manche'.$m],
                        'empty' => array(0 => ''),
                        'onchange' => "this.form.submit()"
                    )); ?>
                <?=$this->Form->end();?>
            <? } //fin for ?>
        </tr>
    <? } //fin foreach ?>
</table><br/>

<?=$this->Html->link("Imprimer la feuille d'alignement",
    array('action' => 'feuilleAlignement',$this->Session->read('Partie.id'),$this->Session->read('Equipe.id')),
    array('class' => 'pure-button pure-button-primary'));?>
<br/><br/>