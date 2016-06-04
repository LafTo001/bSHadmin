<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Photos',array('action' => 'index'));?></th>
            <th width="25%"><?=$this->Html->link('Albums',array('action' => 'albums'));?></th>
            <th width="25%"><?=$this->Html->link('Créer un album',array('action' => 'ajout_album'));?></th>
            <th width="25%"><?=$this->Html->link('Ajouter une photo',array('action' => 'ajouter'));?></th>
        </tr>
    </table>
</div>

<h1>Mes albums</h1>

<? if(empty($albums)) { ?>
    <p>Vous n'avez aucun album</p>
    <?=$this->Html->link('Ajouter un album',array('action' => 'ajout_album'),array('class' => 'pure-button pure-button-primary'));
} ?>

<table>
<? foreach($albums as $album) { ?>
    <tr>
        <td align="center" width="250px"><?=$this->Html->image('gallerie/min_'.$album['VueAlbum']['Filename'], array(
            'url' => array('action' => 'album', $album['VueAlbum']['IdAlbum']))); ?><br/><br/></td>
        <td><b><?=$album['VueAlbum']['NomAlbum'];?></b><br/><br/>
                <?=$album['VueAlbum']['Description'];?></td>
    </tr>
<? } ?>
</table>

      
