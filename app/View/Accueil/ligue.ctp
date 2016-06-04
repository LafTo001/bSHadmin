<h1>Ligue locale St-Hyacinthe</h1>

<?=$this->Form->create('Equipe', array('class' => 'pure-form pure-form-stacked pure-g')); ?>
    <div class="pure-u-7-24" style="margin-left:50px">
        <?=$this->Form->input('NomCategorie',array(
            'label' => 'Catégorie',
            'class' => 'pure-form',
            'options' => $lstCategories,
            'selected' => $categorie,
            'onchange' => "this.form.submit()"
        )); ?>
    </div>

    <div class="pure-u-15-24">  
        <?=$this->Form->input('NomEquipe', array(
            'label' => 'Équipe',
            'class' => 'pure-form',
            'options' => $lstEquipes,
            'empty' => '-- Choisir une équipe --',
            'selected' => (!empty($equipe)) ? $equipe['VueEquipe']['NomEquipe'] : '',
            'onchange' => "this.form.submit()"
        )); ?>
    </div>

<?= $this->Form->end(); ?><br/>

<h2>Classement de la division <?=$categorie; ?></h2>
<table id="tabdonnee" width="95%">
    <tr>
        <th>Équipe</th>
        <th>PJ</th>
        <th>V</th>
        <th>D</th>
        <th>N</th>
        <th>Pts</th>
        <th>PP</th>
        <th>PC</th>
        <th>+/-</th>
        <th>Diff.</th>
    </tr>
    
    <? foreach($class as $e) { ?>
        <tr>
            <td><?=$e['VueClassement']['NomEquipe']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['Parties']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['Victoires']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['Defaites']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['Nulles']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['Points']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['PtsPour']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['PtsContre']; ?></td>
            <td style="text-align: center;"><?=$e['VueClassement']['PtsPour'] - $e['VueClassement']['PtsContre']; ?></td>
            <td style="text-align: center;">
                <?=($e['VueClassement']['Difference'] == 0) ? '-'
                                                            : number_format($e['VueClassement']['Difference'],1); ?></td>
        </tr>
    <? } ?>
</table><br/>

<? if(empty($equipe)) { ?>
    <h2>Calendrier de la division <?=$categorie; ?></h2>
<? } else { ?>
    <h2>Calendrier des <?=$equipe['VueEquipe']['NomEquipe'].' '.$categorie; ?></h2>
<? } ?>
<table id="tabdonnee" width="95%">
    <tr>
        <th>#</th>
        <th>Jour</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Visiteur</th>
        <th>Pts</th>
        <th>Receveur</th>
        <th>Pts</th>
        <th>Terrain</th>
    </tr>
    
    <? foreach($parties as $partie) { ?>
        <tr>
            <td><?=$partie['VuePartie']['NoPartie'];?></td>
            <td><?=$partie['VuePartie']['JourSemaine'];?></td>
            <td><?=$partie['VuePartie']['Date'];?></td>
            <td><?=$partie['VuePartie']['Heure'];?></td>
            <td><?=str_replace("St-Hyacinthe", "", $partie['VuePartie']['NomEquipeVisiteur']);?></td>
            <td><?=($partie['VuePartie']['Statut'] == 1) ? 'Ann.' : $partie['VuePartie']['PointsVisiteur']; ?></td>
            <td><?=str_replace("St-Hyacinthe", "", $partie['VuePartie']['NomEquipeReceveur']);?></td>
            <td><?=($partie['VuePartie']['Statut'] == 1) ? 'Ann.' : $partie['VuePartie']['PointsReceveur']; ?></td>
            <td><?=$partie['VuePartie']['NomTerrain'];?></td>
        </tr>
    <? } ?>
</table><br/>
    
<? /*<h2>Pratiques à venir</h2>

<table id="tabdonnee" style="width:80%;">
    <tr>
        <th>Date</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Terrain</th>
    </tr>
    
    <? foreach($pratiques as $pratique) { ?>
        <tr>
            <td><?=$pratique['VueEvenement']['DateFormat'];?></td>
            <td><?=$pratique['VueEvenement']['DebutEvenement'];?></td>
            <td><?=$pratique['VueEvenement']['FinEvenement'];?></td>
            <td><?=$pratique[0]['NomTerrain'];?></td>
        </tr>
    <? } ?>
</table><br/> */ ?>
<br/>
