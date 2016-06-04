<h1>Ajouter un parent à <?=$joueur['VueJoueur']['nomComplet'];?></h1>

<div class="pure-form pure-form-stacked">
    <div class="pure-g">
        <? foreach($parents as $parent) { ?>
            <div class="pure-u-1-3"> 
                <p><b><?=$parent['VueParent']['nomComplet'];?></b></p>
                <p><?=$parent['VueParent']['AdresseComplete'];?><br/>
                    <?=$parent['VueParent']['Courriel1'];?>
                </p>
            </div><br/>
            <div class="pure-u-2-3"> 
                <br/><?=$this->Html->link('Ajouter ce parent', 
                                array('controller' => 'parents', 'action' => 'lierParentJoueur',$parent['VueParent']['idParent'],$joueur['VueJoueur']['idJoueur']),
                                array('class' => 'pure-button pure-button-primary')
                );?>
            </div>
        <? } ?>
    </div><br/>
    <?=$this->Html->link('Ajouter un autre parent', 
                            array('controller' => 'parents', 'action' => 'rechercher',$joueur['VueJoueur']['idJoueur']),
                            array('class' => 'pure-button pure-button-primary')
        );?>
    <?=$this->Html->link('Annuler', 
                            array('controller' => 'parent', 'action' => 'fiche',$joueur['VueJoueur']['idJoueur']),
                            array('class' => 'pure-button')
        );?>
    <br/><br/>
    
    </div>

