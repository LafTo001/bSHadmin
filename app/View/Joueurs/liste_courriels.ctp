<h1>Liste des courriels de tous les parents</h1>

<?=$this->Form->create('Joueur', array('class' => 'pure-form pure-form-stacked')); ?>
    <table style="margin-left: 40px; width: 200px;">
        <tr>
            <td><?=$this->Form->checkbox('rallyecap', array(
                                'value' => "1",
                                'hidden' => false,
                                'onchange' => "this.form.submit()"
                ));?></td>
            <td>Rallye Cap</td>
        </tr>
        <tr>
            <td><?=$this->Form->checkbox('atome', array(
                                'value' => "2",
                                'hidden' => false,
                                'onchange' => "this.form.submit()"
                ));?></td>
            <td>Atome</td>
        </tr>
        <tr>
            <td><?=$this->Form->checkbox('moustique', array(
                                'value' => "3",
                                'hidden' => false,
                                'onchange' => "this.form.submit()"
                ));?></td>
            <td>Moustique</td>
        </tr>
        <tr>
            <td><?=$this->Form->checkbox('peewee', array(
                                'value' => "4",
                                'hidden' => false,
                                'onchange' => "this.form.submit()"
                ));?></td>
            <td>Pee-wee</td>
        </tr>
        <tr>
            <td><?=$this->Form->checkbox('bantam', array(
                                'value' => "5",
                                'hidden' => false,
                                'onchange' => "this.form.submit()"
                ));?></td>
            <td>Bantam</td>
        </tr>
        <tr>
            <td><?=$this->Form->checkbox('midget', array(
                                'value' => "6",
                                'hidden' => false,
                                'onchange' => "this.form.submit()"
                ));?></td>
            <td>Midget</td>
        </tr>
    </table>

<?=$this->Form->end(); ?>

<p style="font-size: 12px;"><?=$liste; ?></p>
<br/>