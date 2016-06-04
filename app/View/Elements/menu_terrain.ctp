<ul>
    <li><?=$this->Html->link('Confirmer une demande ('.$this->Session->read('Terrain.nbConfirm').')', '/evenements/confirmer/'); ?></li>
    <li><?=$this->Html->link('Calendrier du terrain', '/evenements/calendrier/'); ?></li>
    <li><?=$this->Html->link('Ajouter une réservation', '/evenements/ajouter/'); ?></li>
    <li><?=$this->Html->link('Enregistrement de série', '/series/'); ?></li>
    <li><?=$this->Html->link('Liste des entraineurs', '/equipes/liste/'); ?></li>
    <li><?=$this->Html->link('Gestion du terrain', '/terrain/'); ?></li>
    <li><?=$this->Html->link('Mesures de terrain', '/terrain/mesures'); ?></li>
</ul>
<? if(isset($lstTerrainsMenu)) { ?>
    <div style="text-align:center; margin-left: -15px; margin-bottom:10px;">
        <?=$this->Form->create('Terrain', array('url' => array('controller' => 'terrain', 'action' => 'changerTerrain')));
        echo $this->Form->input('url',array('type' => 'hidden', 'div' => false, 'value' => $_SERVER['REQUEST_URI']));
        echo '<span style="font-size: 13px;">Sélection du terrain :</span><br/>';
        echo '<span style="margin-left:0px;">'.$this->Form->input('idTerrain',array(
                        'label' => '',
                        'div' => false,
                        'options' => $lstTerrainsMenu,
                        'selected' => $this->Session->read('Terrain.id'),
                        'onchange' => "this.form.submit()"
            )).'</span>'; ?>
    </div>   
<? } ?>

