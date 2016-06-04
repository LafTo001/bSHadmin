<? if($perso) { ?>
<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Photos',array('action' => 'index'));?></th>
            <th width="25%"><?=$this->Html->link('Albums',array('action' => 'albums'));?></th>
            <th width="25%"><?=$this->Html->link('Créer un album',array('action' => 'ajout_album'));?></th>
            <th width="25%"><?=$this->Html->link('Ajouter une photo',array('action' => 'ajouter',$idAlbum));?></th>
        </tr>
    </table>
</div>
<? } ?>

<h1><?=$album['Album']['NomAlbum'];?></h1>
<p><?=$album['Album']['Description'];?></p>

<table width="100%">
    <? $cmpt = 0; ?>
    <tr>
    <? foreach($gallerie as $photo) { ?>
        <td align="center"><?=$this->Html->image('gallerie/min_'.$photo['Photo']['Filename'], array(
            'url' => array('action' => 'afficher', $photo['Photo']['id']))); ?><br/><br/></td>
        <? $cmpt++;
        if($cmpt % 4 == 0) echo '</tr><tr>'; ?>
    <? } ?>
    </tr>
    
</table>

<? if($perso) {
    echo $this->Html->link("Ajouter une photo",
                                array('action' => 'ajouter',$album['Album']['id']),
                                array('class' => 'pure-button pure-button-primary')); 

    echo $this->Paginator->prev('<< ' . __('précédente', true), array(), null, array('class'=>'disabled'));
    echo $this->Paginator->numbers(array('class' => 'numbers','separator'=>''));
    echo $this->Paginator->next(__('suivante', true) . ' >>', array(), null, array('class' => 'disabled'));
} else { ?>

    <div align="center">
        <?=$this->Paginator->prev('<< ' . __('précédente', true), array(), null, array('class'=>'disabled'));?>
        <?=$this->Paginator->numbers(array('class' => 'numbers','separator'=>''));?>
        <?=$this->Paginator->next(__('suivante', true) . ' >>', array(), null, array('class' => 'disabled'));?>
    </div>
<? } ?>
<br/><br/>
      
