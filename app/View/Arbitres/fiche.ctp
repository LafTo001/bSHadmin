<!-- /arbitres/fiche.ctp -->

<h1><?=$arbitre['NomComplet'].' &nbsp; '.
        $this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                array('url' => array('action' => 'editer', $arbitre['id']),
                    'width' => '16px', 'height' => '16px'));?></h1>
<h2><?=$arbitre['NomType']; if($arbitre['Type'] == 1) echo ' Grade '.$arbitre['Grade'];?></h2>
<span>Tel Maison : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $arbitre['TelMaison']);?></span><br/>
<span>Tel Mobile : <?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $arbitre['TelMobile']);?></span><br/>
<span>Tel Travail : <?=$arbitre['TelTravail'];?></span><br/><br/>
<span>Courriel : <?=$this->Text->autoLinkEmails($arbitre['Courriel1']); 
    if($arbitre['Courriel2']) echo ', '.$this->Text->autoLinkEmails($arbitre['Courriel2']);?></span></br></br>
                    
<h2>Liste des matchs de la saison</h2>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Ligue</th>
        <th>Categorie</th>
        <th>Terrain</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Arbitre Marbre</th>
        <th>Arbitre But</th>
        <th>Marqueur</th>
    </tr>
    
<? $cmpt =0;
    if(!empty($parties)) {
        foreach($parties as $partie) {
            if(++$cmpt%2 == 1) echo '<tr id="impair">'; else echo '<tr>'; ?>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomLigue'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomCategorie'].' '.$partie['VuePartie']['Classe'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomTerrain'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $this->Html->link($partie['VuePartie']['Date'],array('controller' => 'parties', 'action' => 'jour',$partie['VuePartie']['Date']));?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['Heure'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomArbitreMarbre'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomArbitreBut'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomMarqueur'];?></td>
            </tr>   
            <? }  
    } else { ?>
            <tr><td colspan="8">Aucune partie cédulée pour cet arbitre</td></tr>
    <? }
?>
</table><br/>

<?=$this->Html->link("Envoyer l'horaire",
                        array('action' => 'envoyerHoraire',$idArbitre),
                        array('class' => 'pure-button pure-button-primary')); ?>
<br/><br/>