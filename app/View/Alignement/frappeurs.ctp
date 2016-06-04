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

<div class="pure-g pure-u-3-5">
  <table id="tabdonnee" border="0" cellpadding="2" cellspacing="1" width="100%">
    <tr>
      <th id="colhead">Ordre</th>
      <th id="colhead">Joueur</th>
      <th id="colhead">Options</th>
      <th id="colhead">Position</th>
    </tr>
    
    <? foreach($alignements as $cle => $joueur) { ?>
        <?=$this->Form->create('Alignement', array('div' => false)); ?>
        <?=$this->Form->input('Ordre', array('type' => 'hidden', 'value' => $cle, 'div' => false)); ?>
        <tr>
            <td><?=$cle;?></td>
            <td>
                <?=$this->Form->input('IdJoueur',array(
                            'label' => false,
                            'div' => false,
                            'options' => $joueur['Select']['Joueur'],
                            'selected' => $joueur['VueAlignement']['IdJoueur'],
                            'empty' => array(0 => ''),
                            'onchange' => "this.form.submit()"
                    )); ?>
            </td>
            <td style="text-align: center;">

            <? if($joueur['VueAlignement']['IdJoueur'] > 0) {
                if($cle > 1) {
                    echo $this->Html->image('glyphicons/glyphicons_213_up_arrow.png',
                        array('url' => array('action' => 'monterFrappeur',$joueur['VueAlignement']['idAlignement'],$cle),
                                    'title' => 'Monter le frappeur', 'class' => 'iconLineup', 
                                    'style' => 'height: 20px; width: 26px;')); 

                }
                if($cle > 1 && $cle < $count) {
                    echo ' &nbsp; '; 
                }
                if($cle < $count) {
                    echo $this->Html->image('glyphicons/glyphicons_212_down_arrow.png', 
                        array('url' => array('action' => 'descendreFrappeur',$joueur['VueAlignement']['idAlignement'],$cle),
                                    'title' => 'Descendre le frappeur', 'class' => 'iconLineup', 
                                    'style' => 'height: 20px; width: 26px;'));
                }
            } ?>
            </td>

            <td style="text-align: center;">
            <? if($joueur['VueAlignement']['IdJoueur'] > 0) {
                echo $this->Form->input('Position',array(
                            'label' => false,
                            'div' => false,
                            'options' => $joueur['Select']['Position'],
                            'selected' => $joueur['VueAlignement']['Manche1'],
                            'empty' => array(0 => ''),
                            'onchange' => "this.form.submit()"
                    ));
            } ?>
            </td>
        </tr>
        <?=$this->Form->end();?>
    <? } ?>
</table>
</div>
<div class="pure-g pure-u-1-4" style="vertical-align:top">
  <h3>Liste des joueurs disponibles</h3>       
<?php if(isset($listeDispo)) {
    foreach($listeDispo as $joueur) { ?>
        <span><?=$joueur['VueAlignement']['NomCompletJoueur'];?></span><br/>
    <? }
} ?>
</div><br/><br/>

<?=$this->Html->link("Imprimer la feuille d'alignement",
    array('action' => 'feuilleAlignement',$this->Session->read('Partie.id'),$this->Session->read('Equipe.id')),
    array('class' => 'pure-button pure-button-primary'));?>
<br/><br/>

  </table>
</div>