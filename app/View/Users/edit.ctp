<!-- app/View/Users/edit.ctp -->
<h1>Éditer un usager</h1>

<?=$this->Form->create('User', array('class' => 'pure-form pure-form-stacked')); ?>

    <?=$this->Form->input('Role', array('type' => 'hidden', 'div' => false)); ?>
    <div class="pure-g">    

        <div class="pure-u-1">      
            <?=$this->Form->input('username', array('label' => "Identifiant", 'size' => '30')); ?>
        </div>

        <div class="pure-u-1">      
            <?=$this->Form->input('NomComplet', array('label' => "Nom de l'usager", 'size' => '30')); ?><br/>
        </div>  
        
        <div class="pure-u-1">
            <table style="width:250px;">
              <tr>
                <td>Adminstrateur :</td>
                <td><?=$this->Form->input('Admin', array('label' => false, 'type' => 'checkbox', 'value' => 1, 'hiddenField' => 'false')); ?></td>
              </tr>
              <tr>
                <td>Entraineur :</td>
                <td><?=$this->Form->input('Entraineur', array('label' => false, 'type' => 'checkbox', 'value' => 1, 'hiddenField' => 'false')); ?></td>
              </tr>
              <tr>
                <td>Resp. Terrain :</td>
                <td><?=$this->Form->input('Terrain', array('label' => false, 'type' => 'checkbox', 'value' => 1, 'hiddenField' => 'false')); ?></td>
              </tr>
              <tr>
                <td>Resp. Arbitres :</td>
                <td><?=$this->Form->input('Arbitre', array('label' => false, 'type' => 'checkbox', 'value' => 1, 'hiddenField' => 'false')); ?></td>
              </tr>
              <tr>
                <td>Parent :</td>
                <td><?=$this->Form->input('Parent', array('label' => false, 'type' => 'checkbox', 'value' => 1, 'hiddenField' => 'false')); ?></td>
              </tr>
            </table>
        </div>
        
    </div><br/> 

    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'index'), array('class' => 'pure-button'));?>
    <?=$this->Form->end(); ?>