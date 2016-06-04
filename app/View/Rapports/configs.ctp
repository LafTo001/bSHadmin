<!-- /rapports/configs.ctp -->

<? if($this->Session->read('User.nomComplet') == 'Tommy Lafleur') { ?>
    <div>
        <table id="menuhead" width="1024px" border="0" cellspacing="1">
            <tr>
                <th width="25%"><?=$this->Html->link('Rapports',array('controller' => 'rapports', 'action' => 'index'));?>
                <th width="25%"><?=$this->Html->link('Maintenance',array('controller' => 'rapports', 'action' => 'maintenance'));?>
                <th width="25%"><?=$this->Html->link('Configuration du site',array('controller' => 'rapports', 'action' => 'configs'));?>
                <th width="25%"><?=$this->Html->link('Autres',array('controller' => 'rapports', 'action' => 'index'));?>
            </tr>
        </table>
    </div>
<? } ?>

<h1>Listes des paramètres de configuration du site</h1>

<table id="tabdonnee" width="95%">
    <tr>
        <th>Nom de config</th>
        <th>Valeur</th>
    </tr>

<? foreach($configs as $config) { ?>
    
    <?=$this->Form->create('Config', array('div' => false)); ?>
    <?=$this->Form->input('id', array('type' => 'hidden', 'value' => $config['Config']['id'], 'div' => false)); ?>
    
    <tr>
        <td><?=$config['Config']['NomConfig']; ?></td>
        <td><?=$this->Form->input('Valeur',array(
                            'label' => false,
                            'style' => 'width: 300px;',
                            'div' => false,
                            'value' => $config['Config']['Valeur']
                )); ?>
            <button type="submit" value="Submit">Modifier</button>
        </td>
        
    </tr>
    <?=$this->Form->end(); ?>
<? } ?>
</table>