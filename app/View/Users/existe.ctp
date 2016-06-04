<!-- /users/existe.ctp -->

<h1>Attention !</h1>

<? if($type == 1) { ?>
    <p>Vous avez d�j� un compte utilisateur avec l'adresse <?=$this->Session->read('NouveauUser.courriel');?></p>
    <p><?=$this->Html->link('Cliquer ici pour obtenir un nouveau mot de passe',array('controller' => 'users', 'action' => 'perduMotDePasse'));?></p>
    
<? } elseif($type == 2) { ?>
    <p>L'adresse <?=$this->Session->read('User.courriel');?> est d�j� utilis� par un autre parent</p>
    <p><?=$this->Html->link('Retour au formulaire',array('controller' => 'parents', 'action' => 'inscriptionEnLigne'));?></p>
    
<? } elseif($type == 3) { ?>
    <p>Vous �tes d�j� enregistr� comme parent, d�sirez-vous activer votre compte utilisateur?</p>
    <div style="margin-left:10px;">
        <?=$this->Html->link('Oui',array('controller' => 'users', 'action' => 'creerUsager'), array('class' => "pure-button pure-button-primary"));?>
        <?=$this->Html->link('Non',array('controller' => 'accueil', 'action' => 'index'), array('class' => "pure-button"));?>
    </div>
<? } ?>

