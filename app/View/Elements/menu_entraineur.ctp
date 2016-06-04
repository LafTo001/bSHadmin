<ul>
    <li><?=$this->Html->link("Horaire de l'entraineur", array('controller' => 'parties', 'action' => 'horaire')); ?></li>
    <li><?=$this->Html->link("Cahier d'équipe", array('controller' => 'equipes', 'action' => 'fiche')); ?></li>
    <li><?=$this->Html->link('Réserver un terrain', array('controller' => 'evenements', 'action' => 'calendrier')); ?></li>
    <li><?=$this->Html->link('Chercher un réserviste', array('controller' => 'equipes', 'action' => 'reserviste')); ?></li>
    <li><?=$this->Html->link('Gestion des parties', array('controller' => 'alignement', 'action' => 'gestion')); ?></li>
    <li><?=$this->Html->link('Communications', array('controller' => 'communiques', 'action' => 'index')); ?></li>
    <li><?=$this->Html->link('Tournois BQ', array('controller' => 'tournois', 'action' => 'index')); ?></li>
    <li><?=$this->Html->link('Tutoriel de la section', array('controller' => 'pages', 'action' => 'home')); ?></li> 
</ul>

<? if(isset($listeEquipes)) { ?>
    <div style="text-align:center; margin-left: -15px; margin-bottom:10px;">
        <?=$this->Form->create('Equipe', array('url' => array('controller' => 'equipes', 'action' => 'changerEquipe')));
        echo $this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI']));
        echo '<span style="font-size: 13px;">Sélection de l\'équipe :</span><br/>';
        echo '<span style="margin-left:0px;">'.$this->Form->input('idEquipe',array(
                        'label' => '',
                        'div' => false,
                        'options' => $listeEquipes,
                        'selected' => $this->Session->read('Equipe.id'),
                        'onchange' => "this.form.submit()"
            )).'</span>'; ?>
        <?=$this->Form->end();?>
    </div>   
<? } ?>

