<h1>Parties du <?=$dateFormat?></h1>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Ligue</th>
        <th>Categorie</th>
        <th>#</th>
        <th>Terrain</th>
        <th>Heure</th>
    <? if($this->Session->read('User.role') == 'admin') { ?>
        <th>Visiteur</th>
        <th>Pts</th>
        <th>Receveur</th>
        <th>Pts</th>
        <th></th>
    <? } else { ?>
        <th>Arbitre Marbre</th>
        <th>Arbitre But</th>
        <th>Marqueur</th>
        <th>Form.</th>
    <? } ?>
    </tr>
    
<? $cmpt =0;
    if(!empty($parties)) {
        foreach($parties as $partie) {
            if(++$cmpt%2 == 1) echo '<tr id="impair">'; else echo '<tr>'; ?>
            <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomLigue'];?></td>
            <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomCategorie'].' '.$partie['VuePartie']['Classe'];?></td>
            <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NoPartie'];?></td>
            <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo str_replace(' (St-Hyacinthe)','',$partie['VuePartie']['NomTerrain']);?></td>
            <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['Heure'];?></td>
            <? if($this->Session->read('User.role') == 'admin') { ?>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomEquipeVisiteur'];?></td>
                <td><?=$partie['VuePartie']['PointsVisiteur'];?></td>
                <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomEquipeReceveur'];?></td>
                <td><?=$partie['VuePartie']['PointsReceveur'];?></td>
                <td><?=$this->Html->image('glyphicons/glyphicons_150_edit.png', 
                        array('url' => array('action' => 'resultats', $partie['VuePartie']['idPartie']),
                              'title' => 'Enregistrer le résultat de la partie')); ?></td>
            <? } else { ?>
                <?=$this->Form->create('Partie', array('div' => false)); ?>
                <?=$this->Form->input('id', array('type' => 'hidden', 'value' => $partie['VuePartie']['idPartie'], 'div' => false)); ?>
                    <td><?=$this->Form->input('IdArbitreMarbre',array(
                            'label' => '',
                            'div' => false,
                            'options' => $partie['Select']['Arbitre'],
                            'selected' => $partie['VuePartie']['IdArbitreMarbre'],
                            'empty' => array(0 => ''),
                            'onchange' => "this.form.submit()"
                        )); ?></td>

                    <td><?=$this->Form->input('IdArbitreBut',array(
                            'label' => '',
                            'div' => false,
                            'options' => $partie['Select']['Arbitre'],
                            'selected' => $partie['VuePartie']['IdArbitreBut'],
                            'empty' => array(0 => ''),
                            'onchange' => "this.form.submit()"
                        )); ?></td>

                    <td><?=$this->Form->input('IdMarqueur',array(
                            'label' => '',
                            'div' => false,
                            'options' => $partie['Select']['Marqueur'],
                            'selected' => $partie['VuePartie']['IdMarqueur'],
                            'empty' => array(0 => ''),
                            'onchange' => "this.form.submit()"
                        )); ?></td>
                    <td><?=$this->Form->checkbox('Formation', array(
                                                    'value' => "1",
                                                    'hidden' => false,
                                                    'checked' => $partie['VuePartie']['Formation'],
                                                    'onchange'=> 'this.form.submit()'
                        )); ?></td>
                <?=$this->Form->end();
            } //fin foreach
        }
    } else { ?>
            <tr><td colspan="9">Aucune partie cédulée pour cette journée</td></tr>
    <? }
?>
</table><br/>

<? if($this->Session->read('User.role') == 'admin') {
    echo $this->Html->link("Ajouter une partie",
                            array('action' => 'ajouter'),
                            array('class' => 'pure-button pure-button-primary')); 
} 
?>

