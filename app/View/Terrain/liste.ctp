<h1>Les terrains de St-Hyacinthe</h1>
      
<? foreach($terrains as $terrain) { ?>
        <h2><?=$terrain['Terrain']['NomTerrain'] ?></h2>
        <span>Adresse: <?=$terrain['Terrain']['Adresse'].', '.$terrain['Terrain']['Ville']; ?></span><br/>
        <span><a href="<?=$terrain['Terrain']['Map'];?>" target="_blank">Google Map</a></span><br/><br/>
        <span>Responsable: <?=$terrain['Terrain']['Responsable']; ?></span><br/>
        <span>Téléphone: <?=$terrain['Terrain']['Telephone']; ?></span><br/> 
        <span>Courriel: <?=$terrain['Terrain']['Courriel']; ?></span><br/><br/>
<?php } ?>