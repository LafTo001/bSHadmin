<!-- /joueurs/index.ctp -->

<h1>Répertoire des joueurs</h1>

<div class="pure-g">
    <div class="pure-u-7-24">
    <?=$this->Form->create('Joueur', array('url' => array('action' => 'index'), array('div' => false))); ?>
        <?=$this->Form->input('IdCategorie',array(
            'label' => 'Catégorie: ',
            'options' => $listeCategories,
            'empty' => array('' => 'Toutes les catégories'),
            'onchange' => "this.form.submit()"
            ));
        ?>
    <?=$this->Form->end();?>
    </div>
        
    <div class="pure-u-4-24">
    <? if (isset($listeClasses)) {
        echo $this->Form->create('Joueur', array('url' => array('action' => 'index', $categorie), array('div' => false)));
            echo $this->Form->input('classe',array(
            'label' => 'Classe: ',
            'options' => $listeClasses,
            'selected' => $classe,
            'empty' => array('' => 'Toutes'),
            'onchange' => "this.form.submit()"
            ));
        echo $this->Form->end();
    } else { ?>
        <label>Classe(s): <b>Toutes</b></label>
    <? } ?>
    </div>		
        
    <div class="pure-u-5-24">
    <? if (isset($listeEquipes)) {
        echo $this->Form->create('Joueur', array('url' => array('action' => 'index', $categorie, $classe), array('div' => false)));
            echo $this->Form->input('nomEquipe',array(
            'label' => 'Équipe: ',
            'options' => $listeEquipes,
            'selected' => $equipe,
            'empty' => array('' => 'Toutes'),
            'onchange' => "this.form.submit()"
            ));
        echo $this->Form->end();
    } else { ?>
        <label>Équipes(s): <b>Toutes</b></label>
    <? } ?>
    </div>
    
    <div class="pure-u-6-24">
        <?=$this->Form->create('Joueur', array('url' => array('action' => 'index'), array('div' => false))); ?>
        <?=$this->Form->input('nomComplet',array(
            'label' => '',
            'size' => '28',
            'placeholder' => 'Rechercher un joueur',
            'autofocus' => true
            )); ?>
    </div>
    <div class="pure-u-1-24">
        <button type="submit" value="Submit">Go</button>
    </div>
     <?=$this->Form->end();?>
</div><br/>

<table id="tabdonnee" width="97%">
    <tr>
        <th><?=$this->Paginator->sort('nomPrenom', 'Joueur');?></th>
        <th>No Membre</th>
        <th><?=$this->Paginator->sort('DateNaissance', 'Date de naissance');?></th>
        <th>Niveau</th>
        <th style="width:80px">Cat.</th>
        <th style="width:160px">Équipe</th>
    </tr>
                     
    <? foreach($joueurs as $joueur) { ?>  
    
        <?=$this->Form->create('Inscription', array('div' => false)); ?>
        <?=$this->Form->input('id', array('type' => 'hidden', 'value' => $joueur['VueJoueur']['idInscription'], 'div' => false)); ?>
        <tr>
            <td><?=$this->Html->link($joueur['VueJoueur']['nomPrenom'], array('action' => 'fiche', $joueur['VueJoueur']['idJoueur']));?></td>
            <td><?=$joueur['VueJoueur']['NoMembre'];?></td>
            <td><?=$joueur['VueJoueur']['DateNaissance'];?></td>
            <td><?=$joueur['VueJoueur']['NomCategorie'];?></td>
            <? if(!empty($joueur['Select']['Classe'])) { ?>
                <td style="text-align: center;">
                    <?=$this->Form->input('Classe',array(
                            'label' => false,
                            'style' => 'width: 55px;',
                            'div' => false,
                            'options' => $joueur['Select']['Classe'],
                            'selected' => $joueur['VueJoueur']['Classe'],
                            'empty' => '',
                            'onchange' => "this.form.submit()"
                        )); ?>
                </td>
            <? } else { ?>
                <td><?=$joueur['VueJoueur']['Classe']; ?></td>
            <? } ?>
                
            <? if(!empty($joueur['Select']['Equipe'])) { ?>
                <td style="text-align: center;">
                    <?=$this->Form->input('idEquipe',array(
                            'label' => false,
                            'style' => 'width: 130px;',
                            'div' => false,
                            'options' => $joueur['Select']['Equipe'],
                            'selected' => $joueur['VueJoueur']['idEquipe'],
                            'empty' => array('0' => ''),
                            'onchange' => "this.form.submit()"
                        )); ?>
                </td>
            <? } else { ?>
                <td><?=$joueur['VueJoueur']['NomEquipe']; ?></td>
            <? } ?>
        </tr>
        <?=$this->Form->end();?>
    <? } ?>

        </tbody>
</table><br/>
<?php echo $this->Html->link("Ajouter une inscription",
                                array('controller' => 'parents', 'action' => 'rechercher'),
                                array('class' => 'pure-button pure-button-primary')); ?>
<?php echo $this->Paginator->prev('<< ' . __('précédente', true), array(), null, array('class'=>'disabled'));?>
<?php echo $this->Paginator->numbers(array('class' => 'numbers','separator'=>''));?>
<?php echo $this->Paginator->next(__('suivante', true) . ' >>', array(), null, array('class' => 'disabled'));?>
<br/><br/>
    
<? if(isset($courriels)) { ?>
    <span><?=$this->Html->link('Envoyer un courriel à cette liste', 'mailto:?bcc='.$courriels); ?></span>
<? } else { ?>
    <span><?=$this->Html->link('Envoyer un courriel à cette liste', array('action' => 'listeCourriels')); ?></span>
<? } ?>

<br/><br/>
    