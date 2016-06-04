<div class="users form">
<h1>Liste des communiqués</h1>
<table id="tabdonnee" width="97%">
    <thead id="colhead">
        <tr>
            <th><?php echo $this->Paginator->sort('Titre', 'Titre');?>  </th>
            <th><?php echo $this->Paginator->sort('Datecreation', 'Date création');?></th>
            <th><?php echo $this->Paginator->sort('Auteur','Auteur');?></th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>                       
        <?php $count=0; ?>
        <?php foreach($communiques as $comm): ?>                
        <?php $count ++;?>
        <?php if($count % 2): echo '<tr>'; else: echo '<tr id="impair">' ?>
        <?php endif; ?>
            <td><?php echo $this->Html->link($comm['VueCommunique']['Titre'], array('action'=>'editer', $comm['VueCommunique']['id']),array('escape' => false) );?></td>
            <td style="text-align: center;"><?=$comm['VueCommunique']['DateCreation']; ?></td>
            <td style="text-align: center;"><?=$comm['VueCommunique']['Auteur']; ?></td>
            <td ><?=$this->Html->link("Supprimer", array('action'=>'supprimer', $comm['VueCommunique']['id'])); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php unset($user); ?>
    </tbody>
</table><br/>
<?php echo $this->Html->link("Nouveau communiqué",
                                array('action' => 'ajouter'),
                                array('class' => 'pure-button pure-button-primary')); ?>
<?php echo $this->Paginator->prev('<< ' . __('précédente', true), array(), null, array('class'=>'disabled'));?>
<?php echo $this->Paginator->numbers(array(   'class' => 'numbers'     ));?>
<?php echo $this->Paginator->next(__('suivante', true) . ' >>', array(), null, array('class' => 'disabled'));?>
</div>