<!-- /equipes/index.ctp -->
<h1>Les équipes</h1>

<? if(empty($categories)) { ?>
    <p>Les équipes de la saison <?=date('Y'); ?> ne sont pas encore formées.</p>
<? } ?>

<?=$this->Form->create('Equipe'); ?>
<div class="pure-form pure-form-stacked" style="margin-left:30px;">
      
    <div class="pure-g">
        <div class="pure-u-1-5">
            <?=$this->Form->input('nomCategorie',array(
                'label' => 'Catégorie',
                'empty' => '--',
                'options' => $categories,
                'selected' => $nomCategorie,
                'onchange' => "this.form.submit()"
            )); ?>
        </div> 
    <? if($nomCategorie != '') { ?>
        <div class="pure-u-4-5">
            <?=$this->Form->input('classe',array(
                'label' => 'Classe',
                'empty' => '--',
                'options' => $classes,
                'selected' => $classe,
                'onchange' => "this.form.submit()"
            )); ?>
        </div> 
    <? } ?>
    </div><br/><br/>
    
<? if ($classe != '') { ?>
    
    <div class="pure-g">
    <? foreach($equipes as $equipe) { ?>
        <div class="pure-u-1-2">
            <?=$this->Html->image($equipe['VueEquipe']['NomEquipe'].'.jpg',array(
                                    'url' => array('controller' => 'parties', 'action' => 'horaire',$nomCategorie,$classe,$equipe['VueEquipe']['NomEquipe']),
                                    'height' => '120px', 
                                    'width' => '430px'
            ));?>
            <br/>
        
            <span><?=$this->Html->link('Horaire des matchs', array('controller' => 'parties', 'action' => 'horaire',$nomCategorie,$classe,$equipe['VueEquipe']['NomEquipe'])); ?></span><br/>
            <span><?=$this->Html->link("Communiqués de l'équipe", array('controller' => 'communiques', 'action' => 'index',$nomCategorie,$classe,$equipe['VueEquipe']['NomEquipe'])); ?></span><br/><br/>
        </div>
    <?php } ?>
      <br/>
<?php } ?>

</div>