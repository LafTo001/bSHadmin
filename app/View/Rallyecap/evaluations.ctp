<h1>Évaluations Rallye Cap</h1>

<?=$this->Form->create('Joueur');
            echo $this->Form->input('nomEquipe',array(
            'label' => 'Équipe: ',
            'options' => $equipes,
            'selected' => $nomEquipe,
            'onchange' => "this.form.submit()"
            ));
        echo $this->Form->end(); ?>
<br/>

<table id="tabdonnee" width="97%">
    <tr>
        <th>Nom Joueur</th>
        <? foreach($objectifs as $objectif) { ?>
            <th><?=ucfirst($objectif); ?></th>
        <? } ?>
    </tr>
    <? foreach($joueurs as $joueur) { ?>
        <?=$this->Form->create('Evaluation', array('div' => false)); ?>
        <?=$this->Form->input('idJoueur', array('type' => 'hidden', 'value' => $joueur['VueEvaluation']['idJoueur'], 'div' => false)); ?>
        <tr>
            <td><?=$joueur['VueEvaluation']['nomJoueur']; ?></td>
            <? foreach($objectifs as $objectif) { ?>
                <td style="text-align:center;"><?=$this->Form->input($objectif, array(
                            'label' => false,
                            'div' => false,
                            'options' => $couleurs,
                            'empty' => array(0 => ''),
                            'selected' => $joueur['VueEvaluation'][$objectif],
                            'onchange' => "this.form.submit()"
                        )); ?>
                </td>
            <? } ?>
        </tr>
        <?=$this->Form->end();?>
    <? } ?>
</table>