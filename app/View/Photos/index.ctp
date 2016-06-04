<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="34%"><?=$this->Html->link('Photos',array('action' => 'index'));?></th>
            <th width="33%"><?=$this->Html->link('Albums',array('action' => 'albums'));?></th>
            <th width="33%"><?=$this->Html->link('Ajouter une photo',array('action' => 'ajouter'));?></th>
        </tr>
    </table>
</div>

<h1>Mes photos</h1>

<table width="100%">
    <? $cmpt = 0; ?>
    <tr>
    <? foreach($gallerie as $photo) { ?>
        <td align="center"><?=$this->Html->image('gallerie/min_'.$photo['Photo']['Filename'], array(
                                   'url' => array('action' => 'afficher', $photo['Photo']['id']))); ?></td>
        <? $cmpt++;
        if($cmpt % 4 == 0) echo '</tr><tr>'; ?>
    <? } ?>
    </tr>
</table>
      
