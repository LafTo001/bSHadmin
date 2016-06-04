<!-- /user/perduMotDePasse.ctp -->
<h1>Demande de nouveau mot de passe</h1>

<?=$this->Form->create('User',array('class' => 'pure-form pure-form-stacked')); ?>
    <fieldset>
        <div class="pure-g">
            <div class="pure-u-3-8">
                <?=$this->Form->input('Courriel',array(
                    'label' => 'Adresse courriel',
                    'type' => 'email',
                    'size' => '35',
                    'required' => true
                )); ?>  
            </div>
        </div>
    </fieldset>

<p>L'adresse doit être celle que vous utilisez pour vous connecter sur le site.</p>  
<p>Vous recevrez votre nouveau mot de passe à l'adresse électronique indiquée.</p><br/>

<div style="margin-left:10px;">
    <button type="submit" class="pure-button pure-button-primary">Enregistrer</button>
    <?=$this->Html->link('Annuler',array('action' => 'login'), array('class' => 'pure-button')); ?>
    <?=$this->Form->end(); ?>
</div>