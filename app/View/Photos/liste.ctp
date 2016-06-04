<h1>Les albums photos de Baseball St-Hyacinthe</h1>

<table>
<? foreach($albums as $album) { ?>
    <tr>
        <td align="center" width="250px"><?=$this->Html->image('gallerie/min_'.$album['VueAlbum']['Filename'], array(
            'url' => array('action' => 'album', $album['VueAlbum']['IdAlbum']))); ?><br/><br/></td>
        <td><b><?=$album['VueAlbum']['NomAlbum'];?></b><br/><br/>
                <?=$album['VueAlbum']['Description'];?><br/>
                Publié par : <?=$album['VueAlbum']['NomUsager'];?><br/><br/>
        </td>
    </tr>
<? } ?>
</table>

      
