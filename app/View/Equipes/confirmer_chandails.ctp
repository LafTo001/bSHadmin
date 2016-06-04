<h1>Confirmation des choix de numéro</h1>

<p><b>Voulez-vous vraiment envoyer la commande au responsable des chandais?</b><br/><br/>
    Cette liste deviendra aussi la liste officielle pour le cahier d'équipe de Baseball Québec</p>

<?=$this->Html->link('Oui',array('action' => 'confirmerChandails',1), array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Html->link('Non',array('action' => 'fiche'), array('class' => 'pure-button')); ?>
      
