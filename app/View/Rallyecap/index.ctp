<ul class="eventsRallyeCap">
    <li class="titre"><b>Événements à venir</b></li>
    <? foreach($events as $event) { ?>
        <li><?=$event['VueEvenement']['DateFormat'].' @ '.$event['VueEvenement']['DebutEvenement']; ?><br/>
            <?=$this->Html->image('arrow-30-24.png', 
                array('url' => array('action' => 'evenement', $event['VueEvenement']['idEvenement']), 'class' => 'icon')); ?>
            <?=$event['VueEvenement']['NomTerrain']; ?><br/>
            <?=str_replace("Rallye Cap : ", "", $event['VueEvenement']['DescEvenement']); ?>
        </li>
            
    <? } ?>
    <li><?=$this->Html->link('Calendrier complet', array('action' => 'calendrier')); ?>
        <?=$this->Html->image('arrow-30-24.png', 
                array('url' => array('action' => 'calendrier'), 'class' => 'icon')); ?></li>
</ul>

<h1>Programme Rallye Cap</h1>

<p><a href="http://www.saisirlemoment.com/fr/page/programme_dinitiation.html" target="_blank">Cliquez ici pour quelques informations sur le programme</a></p><br/>