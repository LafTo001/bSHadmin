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
        <th>Receveur</th>
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
            <td><? if($partie['VuePartie']['Active'] == 0) echo '<s>'; echo $partie['VuePartie']['NomEquipeReceveur'];?></td>
        <? } else { ?>
            <td><?=$this->Form->input('IdArbitreMarbre',array(
                        'label' => '',
                        'div' => 'dropDown',
                        'options' => $partie['Select']['Marbre'],
                        'selected' => '/bshadmin/parties/assignerArbitrePartie/'.$partie['VuePartie']['IdArbitreMarbre'].'/marbre/'.$partie['VuePartie']['idPartie'],
                        'empty' => array('/bshadmin/parties/assignerArbitrePartie/0/marbre/'.$partie['VuePartie']['idPartie'] => ''),
                        'onchange' => 'location = this.options[this.selectedIndex].value;'
                    )); ?></td>
                
                <td><?=$this->Form->input('IdArbitreBut',array(
                        'label' => '',
                        'div' => 'dropDown',
                        'options' => $partie['Select']['But'],
                        'selected' => '/bshadmin/parties/assignerArbitrePartie/'.$partie['VuePartie']['IdArbitreBut'].'/but/'.$partie['VuePartie']['idPartie'],
                        'empty' => array('/bshadmin/parties/assignerArbitrePartie/0/but/'.$partie['VuePartie']['idPartie'] => ''),
                        'onchange' => 'location = this.options[this.selectedIndex].value;'
                    )); ?></td>
                
                <td><?=$this->Form->input('IdMarqueur',array(
                        'label' => '',
                        'div' => 'dropDown',
                        'options' => $partie['Select']['Marqueur'],
                        'selected' => '/bshadmin/parties/assignerArbitrePartie/'.$partie['VuePartie']['IdMarqueur'].'/marqueur/'.$partie['VuePartie']['idPartie'],
                        'empty' => array('/bshadmin/parties/assignerArbitrePartie/0/marqueur/'.$partie['VuePartie']['idPartie'] => ''),
                        'onchange' => 'location = this.options[this.selectedIndex].value;'
                    )); ?></td>
                <td><?=$this->Form->create('Partie',array('action' => 'marqueurEnFormation/'.$partie['VuePartie']['idPartie'].'/'.$date));
                    echo $this->Form->checkbox('Formation', array(
                                                'value' => "1",
                                                'onchange'=> 'this.form.submit()',
                                                'hidden' => false,
                                                'checked' => $partie['VuePartie']['Formation']));
                    echo $this->Form->end();?></td>
            <? }
        } 
    } else { ?>
            <tr><td colspan="10">Aucune partie cédulée pour cette journée</td></tr>
    <? }
?>
</table><br/>

<? if($this->Session->read('User.role') == 'admin') {
    echo $this->Html->link("Ajouter une partie",
                            array('action' => 'ajouter'),
                            array('class' => 'pure-button pure-button-primary')); 
} 
?>

