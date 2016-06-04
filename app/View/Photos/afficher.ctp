<? if($perso) { ?>
<div>
    <table id="menuhead" width="1024px" border="0" cellspacing="1">
        <tr>
            <th width="25%"><?=$this->Html->link('Photos',array('action' => 'index'));?></th>
            <th width="25%"><?=$this->Html->link('Albums',array('action' => 'albums'));?></th>
            <th width="25%"><?=$this->Html->link('Créer un album',array('action' => 'ajout_album'));?></th>
            <th width="25%"><?=$this->Html->link('Ajouter une photo',array('action' => 'ajouter', $idAlbum));?></th>
        </tr>
    </table>
</div>
<? } ?>

<h1></h1>

<table>
    <tr>
        <? if($autres['prev'] != null) { ?>
            <td width="100px" align="center"><?=$this->Html->image('glyphicons/15.png', 
                                    array('url' => array('action' => 'afficher',$autres['prev']['Photo']['id']),
                                            'height' => '25px'));?>
            </td>
        <? } else { ?>
            <td width="100px"></td>
        <? } ?>
    
        <td width="800px" align="center"><?=$this->Html->image('gallerie/'.$photo['Photo']['Filename']); ?></td>
        
        <? if($autres['next'] != null) { ?>
            <td width="100px" align="center"><?=$this->Html->image('glyphicons/16.png', 
                                    array('url' => array('action' => 'afficher',$autres['next']['Photo']['id']),
                                            'height' => '25px'));?>
            </td>
        <? } ?>
    </tr>

<? if($perso) { ?>
    <tr>
        <td></td>
        <td><?=$this->Form->create('Photo',array('class' => 'pure-form pure-form-stacked')); ?>
                <div class="pure-u-1-1">
                    <?=$this->Form->input('Description',array(
                        'label' => '',
                        'placeholder' => 'Description de la photo',
                        'size' => '106',
                        'value' => $photo['Photo']['Description']
                    )); ?>
                </div>
            
                <div class="pure-u-10-24">
                    <?=$this->Form->input('IdAlbum',array(
                        'label' => '',
                        'options' => $albums,
                        'empty' => array(0 => '-- Déplacer dans un autre album --'),
                        'selected' => $idAlbum
                    )); ?>
                </div>
            
                <div class="pure-u-8-24">
                    <?=$this->Form->submit('Enregistrer les changements',array('class' => 'pure-button pure-button-primary'));?>
                </div>
            
                <div class="pure-u-4-24">
                    <?=$this->Html->link('Supprimer la photo',array('action' => 'supprimer', $idPhoto),array('class' => 'pure-button pure-button-primary'));?>
                </div>
        </td>
    </tr>
<? } else { ?>
    <tr>
        <td></td>
        <td><br/><?=$this->Html->link("Retour à l'album",array('action' => 'album', $idAlbum));?>
    </tr>
 <? } ?>   
</table>
<br/><br/>
      
