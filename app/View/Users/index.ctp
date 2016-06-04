<h1>Liste des usagers</h1>

<table id="tabdonnee" width="97%">
    <tr>
        <th><?=$this->Paginator->sort('username', 'Usager');?></th>
        <th><?=$this->Paginator->sort('NomComplet', 'Nom');?></th>
        <th><?=$this->Paginator->sort('DerniereConnexion','Dernière connexion');?></th>
        <th><?=$this->Paginator->sort('DerniereModif','Dernière modif');?></th>
        <th><?=$this->Paginator->sort('Admin','Admin');?></th>
        <th><?=$this->Paginator->sort('Entraineur','Ent.');?></th>
        <th><?=$this->Paginator->sort('Terrain','Terrain');?></th>
    </tr>
                     
    <? foreach($users as $user) { ?>     
        <tr>
            <td><?=$this->Html->link( $user['User']['username'], array('action'=>'edit', $user['User']['id']),array('escape' => false) );?></td>
            <td><?=$user['User']['NomComplet']; ?></td>
            <td><?=$user['User']['DerniereConnexion']; ?></td>
            <td><?=$user['User']['DerniereModif']; ?></td>
            <td style="text-align: center;"><?=($user['User']['Admin'] == 1) ? 'O' : 'N'; ?></td>
            <td style="text-align: center;"><?=($user['User']['Entraineur'] == 1) ? 'O' : 'N'; ?></td>
            <td style="text-align: center;"><?=($user['User']['Terrain'] == 1) ? 'O' : 'N'; ?></td>
        </tr>
    <? } ?>
</table>
<br/>

<div class="pure-g">
    <div class="pure-u-7-24">
    <?=$this->Form->create('User'); ?>
        <?=$this->Form->input('username',array(
            'label' => '',
            'size' => '32',
            'placeholder' => 'Rechercher un usager',
            'autofocus' => true,
            'style' => 'margin-left: -15px'
            )); ?>
    </div>
    <div class="pure-u-1-24">
        <button type="submit" value="Submit">Go</button>
    </div>
     <?=$this->Form->end();?>
</div>
<br/>

<?=$this->Html->link("Nouvel usager",
                                array('action' => 'add'),
                                array('class' => 'pure-button pure-button-primary')); ?>
<?=$this->Paginator->prev('<< ' . __('précédente', true), array(), null, array('class'=>'disabled'));?>
<?=$this->Paginator->numbers(array('class' => 'numbers', 'separator'=>''));?>
<?=$this->Paginator->next(__('suivante', true) . ' >>', array(), null, array('class' => 'disabled'));?>

<br/><br/>