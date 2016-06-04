<!-- /usagers/login.ctp -->

<h1>Connexion à bSHadmin</h1>
<?=$this->Session->flash('auth');?>
<?=$this->Form->create('User',array('class' => 'pure-form pure-form-stacked')); ?>
    <fieldset>
        <div class="pure-g">
            <div class="pure-u-1-1">
                <?=$this->Form->input('username',array('label' => 'Courriel'));?>
            </div>
            <div class="pure-u-1-1">
                <?=$this->Form->input('password',array('label' => 'Password'));?>
            </div> 
            
        </div> 
    </fieldset>

<br/>
<div style="margin-left:15px;">
    <?=$this->Form->end(array('label' => 'Se connecter', 
                                  'class' => 'pure-button pure-button-primary'
                        )); ?>
</div>
<br/>
<p><?=$this->Html->link("J'ai perdu mon mot de passe", array('controller' => 'users', 'action' => 'perduMotDePasse')); ?></p>



