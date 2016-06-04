<h1>Liste des arbitres</h1>

<table id="tabdonnee" width="97%">
    <thead id="colhead">
        <tr>
            <th><?=$this->Paginator->sort('NomPrenom', 'Nom');?>  </th>
            <th><?=$this->Paginator->sort('NomType', 'Type');?></th>
            <th><?=$this->Paginator->sort('Grade','Grade');?></th>
            <th>Tel. Maison</th>
            <th>Tel. Mobile</th>
            <th>Courriel</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>                       
        <?php 
        $count=0;
        foreach($arbitres as $arb):                
        $count ++;
        if($count % 2): echo '<tr>'; else: echo '<tr id="impair">';
        endif; ?>
            <td><?=$this->Html->link($arb['VueArbitre']['NomPrenom'], 
                    array('action' => 'fiche',$arb['VueArbitre']['id'],
                                        strtr($arb['VueArbitre']['NomComplet'],
                                            '@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ',
                                            'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy_')),
                    array('escape' => false) );?>
            </td>
            <td><?=$arb['VueArbitre']['NomType']; ?></td>
            <td style="text-align: center;"><?=$arb['VueArbitre']['Grade']; ?></td>
            <td style="text-align: center;"><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $arb['VueArbitre']['TelMaison']); ?></td>
            <td style="text-align: center;"><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $arb['VueArbitre']['TelMobile']); ?></td>
            <td style="text-align: center;"><?=$this->Text->autoLinkEmails($arb['VueArbitre']['Courriel1']); ?></td>
            <td style="text-align:center;"><?=$this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                        array('url' => array('action' => 'editer', $arb['VueArbitre']['id']),
                            'width' => '16px', 'height' => '16px',
                            'title' => 'Éditer')).' &nbsp; &nbsp; '.
                   $this->Html->image('glyphicons/glyphicons_207_remove_2.png', 
                        array('url' => array('action' => 'supprimer', $arb['VueArbitre']['id']),
                            'width' => '16px', 'height' => '16px',
                            'title' => 'Supprimer'));?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php unset($arbitres); ?>
    </tbody>
</table><br/>
<?=$this->Html->link("Nouvel arbitre",
                                array('action' => 'ajouter'),
                                array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Paginator->prev('<< ' . __('précédente', true), array(), null, array('class'=>'disabled'));?>
<?=$this->Paginator->numbers(array('class' => 'numbers','separator'=>''));?>
<?=$this->Paginator->next(__('suivante', true) . ' >>', array(), null, array('class' => 'disabled'));?>

<br/><br/>
<span><?=$this->Html->link("Envoyer l'horaire à tous", array('action' => 'envoyerHoraire')); ?></span>
<span><?=$this->Html->link('Envoyer un courriel à tous', 'mailto:?bcc='.$courriels); ?></span>