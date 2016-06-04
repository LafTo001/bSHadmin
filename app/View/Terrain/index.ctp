<h1><?=$terrain['Terrain']['NomTerrain'].' &nbsp; '.
        $this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                array('url' => array('action' => 'editer'),
                    'width' => '16px', 'height' => '16px')); ?></h1>

<p><?=$terrain['Terrain']['Organisme'];?><br/>
    <?=$terrain['Terrain']['Adresse'].', '.$terrain['Terrain']['Ville'];?><br/>
    <?=$terrain['Terrain']['Telephone'];?><br/><br/>
    
    Coordonateur : <?=$terrain['Terrain']['Responsable'].', '.$terrain['Terrain']['Courriel'];?><br/>
    <? if($terrain['Terrain']['Adjoint'] != null) { ?>
        Adjoint : <?=$terrain['Terrain']['Adjoint'].', '.$terrain['Terrain']['CourrielAdjoint'];?><br/>
    <? } ?>
    <br/>

</p>