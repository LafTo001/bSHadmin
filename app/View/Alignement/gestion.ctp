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

<? if(!empty($listeParties)) { ?>
    <?=$this->Form->create('Partie', array('url' => array('controller' => 'parties', 'action' => 'changerPartie'))); ?>
    <?=$this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI'])); ?>
    <div class="pure-u-1">
        <?=$this->Form->input('idPartie',array(
                        'label' => 'Sélection de la partie',
                        'div' => false,
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

<? if($existe == false) {
    echo $this->Html->link('Gérer cette partie', array('action' => 'ajouterAlignement'), array('class' => 'pure-button pure-button-primary'));
} else { ?>
    
    <h3>Joueurs présents</h3>

    <table id="tabdonnee" width="80%">
        <tr>
            <th width="100px">#</th>
            <th>Joueur</th>
            <th width="200px"></th>
        </tr>

    <? if(!empty($presents)) {
        $cmpt = 0;
        foreach($presents as $joueur) {
            if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>';?>
                <td><?=$joueur['VueAlignement']['Numero']; ?></td>
                <td><?=$joueur['VueAlignement']['NomCompletJoueur']; ?></td>
                <td><?=$this->Html->link("Retirer de l'alignement",
                    array('action' => 'changerStatut',$joueur['VueAlignement']['idAlignement'],1));?></td>
            </tr>
        <? }
    } else { ?>
            <tr id="impair"><td colspan="3">Aucun joueur</td></tr>
    <? } ?>
    </table><br/>
    
    <h3>Joueurs remplaçants</h3>

    <table id="tabdonnee" width="80%">
      <tr>
            <th width="100px">#</th>
            <th>Joueur</th>
            <th width="200px"></th>
      </tr>

    <? if(!empty($remplacants)) {
        $cmpt = 0;
        foreach($remplacants as $joueur) {
            if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>';?>
                <td><?=$joueur['VueAlignement']['Numero']; ?></td>
                <td><?=$joueur['VueAlignement']['NomCompletJoueur']; ?></td>
                <td><?=$this->Html->link("Retirer de l'alignement",
                    array('action' => 'changerStatut',$joueur['VueAlignement']['idAlignement'],1));?></td>
            </tr>
        <? }
    } else { ?>
            <tr id="impair"><td colspan="3">Aucun joueur</td></tr>
    <?php } ?>
    </table><br/>

    <h3>Joueurs absents</h3>

    <table id="tabdonnee" width="80%">
      <tr>
            <th width="100px">#</th>
            <th>Joueur</th>
            <th width="200px"></th>
      </tr>

    <? if(!empty($absents)) {
        $cmpt = 0;
        foreach($absents as $joueur) {
            if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>';?>
                <td><?=$joueur['VueAlignement']['Numero']; ?></td>
                <td><?=$joueur['VueAlignement']['NomCompletJoueur']; ?></td>
                <td><?=$this->Html->link("Remettre dans l'alignement",
                    array('action' => 'changerStatut',$joueur['VueAlignement']['idAlignement'],0));?></td>
            </tr>
        <? }
    } else { ?>
            <tr id="impair"><td colspan="3">Aucun joueur</td></tr>
    <?php } ?>
    </table><br/>

    <h3>Entraineurs</h3>

    <table id="tabdonnee" width="80%">
      <tr>
            <th width="100px">#</th>
            <th>Entraineur</th>
            <th width="200px"></th>
      </tr>

    <? if(!empty($entraineurs)) {
        $cmpt = 0;
        foreach($entraineurs as $ent) {
            if(++$cmpt % 2 == 1) echo '<tr id="impair">'; else echo '<tr>';
            if($ent['VueAlignement']['Statut'] == 98) { ?>
                <td><?=$ent['VueAlignement']['NumeroEnt']; ?></td>
                <td><?=$ent['VueAlignement']['NomCompletEnt']; ?></td>
                <td><?=$this->Html->link("Retirer de l'alignement",
                    array('action' => 'changerStatut',$ent['VueAlignement']['idAlignement'],99));?></td>
            <? } else { ?>
                <td><s><?=$ent['VueAlignement']['NumeroEnt']; ?></s></td>
                <td><s><?=$ent['VueAlignement']['NomCompletEnt']; ?></s></td>
                <td><?=$this->Html->link("Remettre dans l'alignement",
                    array('action' => 'changerStatut',$ent['VueAlignement']['idAlignement'],98));?></td>
            <? } ?>
            </tr>
        <? }
    } else { ?>
            <tr id="impair"><td colspan="3">Aucun joueur</td></tr>
    <?php } ?>
    </table><br/><br/>

<? } ?>