    <h1><?=$data['VueJoueur']['nomComplet'];
    if($this->Session->read('User.role') == 'admin' || $parent == true) {
        echo ' &nbsp; '.$this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                array('url' => array('action' => 'edit', $data['VueJoueur']['idJoueur']),
                    'width' => '16px', 'height' => '16px', 'title' => 'Modifier les informations du joueur'));
    } ?>
    </h1>

    <div class="pure-form pure-form-stacked">
    
    <fieldset>
      <legend>Informations du joueur</legend>
      <div class="pure-g">
        <div class="pure-u-1">
          <span>Date de naissance : <?=$data['VueJoueur']['DateNaissance'];?></span><br/>
          <span>Sexe : <?=$data['VueJoueur']['NomSexe'];?></span><br/><br/>

          <span>Catégorie : <?=$data['VueJoueur']['NomCategorie'].' '.$data['VueJoueur']['Classe'];?></span></br>
          <span>Équipe : <?=$this->Html->link($data['VueJoueur']['NomEquipe'],array('controller' => 'equipes', 'action' => 'fiche',$data['VueJoueur']['idEquipe']));?></span></br>
          <span>Numéro : <?=$data['VueJoueur']['Numero'];?></span></br>
          <span>Choix de numéro : <?=$data['VueJoueur']['ChoixNumero'];?>
          <span>Chandail : <?=$data['VueJoueur']['Chandail'];?></span></br>
          
<? if($this->Session->read('User.role') == 'admin') { ?>
          <br>

          <span>Carte accès loisirs : <?=$data['VueJoueur']['NoCAL'];?></span><br/>
          <span>Exp. : <?=$data['VueJoueur']['DateExpCAL'];?></span>
          <? if($data['VueJoueur']['CALvalide'] == 0) { ?>
              <span style="color:red; font-weight:bold;">&nbsp; (Carte expirée)</span>
          <? } ?></br></br>

    <? if($data['VueJoueur']['Reinscrit'] == 1) {
        if($data['VueJoueur']['Paiement'] == 0) { ?>
              <span style="color:red; font-weight:bold;">Inscription non-payée</span></br>
        <? } else {?>
              <span>Inscription payée : <?=$data['VueJoueur']['Paiement'];?>$</span>
              <span>Mode de paiement : <?=$data['VueJoueur']['strModePaiement'];?></span></br>
        <? }
        if($data['VueJoueur']['PaiementEcole'] > 0) { ?>      
            <span>Inscription à l'école de baseball : <?=$data['VueJoueur']['PaiementEcole'];?>$</span><br/>
        <? }
    }

        echo '<br/>'.$this->Html->link($data['VueJoueur']['Reinscrit'] == 0 ? "Renouveller l'inscription" : "Modifier l'inscription", 
                        array('controller' => 'joueurs', 'action' => 'inscription',$data['VueJoueur']['idJoueur']),
                        array('class' => 'pure-button pure-button-primary')).'<br/>';
} ?>
      </div></div>
    </fieldset><br/>
    
    <fieldset>
      <legend>Fiche santé</legend>
      <div class="pure-g">
        <div class="pure-u-1">
          <span>Carte assurance maladie : <?=$data['VueJoueur']['CarteRAMQ'];?></span><br/>
          <span>Allergies : <?=$data['VueJoueur']['DescAllergies'];?></span><br/>
          <span>Épipen : <?=$data['VueJoueur']['AdminEpipen'];?></span><br/>
          <span>Maladies : <?=$data['VueJoueur']['DescMaladies'];?></span><br/>
          <span>Médicaments : <?=$data['VueJoueur']['DescMedicaments'];?></span><br/>
          <br/>
          <span>Autres informations : <?=$data['VueJoueur']['Informations'];?></span><br/>
        </div></div>
    </fieldset><br/>
    
    <fieldset>
      <legend>Les parents</legend>
      <div class="pure-g">      
<? foreach($parents as $parent) : ?>
        <div class="pure-u-1-3">
            <h2>
            <? if($this->Session->read('User.role') == 'admin' || $parent == true) {  
                echo $this->Html->link($parent[0]['NomComplet'],
                    array('controller' => 'parents', 'action' => 'fiche', $parent['P']['id'])).' &nbsp; '.
                $this->Html->Image('glyphicons/glyphicons_030_pencil.png',
                      array('url' => array('controller' => 'parents', 'action' => 'edit', $parent['P']['id'], $data['VueJoueur']['idJoueur']),
                      'title' => 'Modifier les informations du parent',
                      'height' => '16px', 
                      'width' => '16px'));
                  
                if($parent[0]['Ordre'] == 0) {
                    echo ' &nbsp; '.$this->Html->Image('glyphicons/glyphicons_207_remove_2.png',
                                        array('url' => array('controller' => 'parents', 'action' => 'supprimer', 
                                            $parent['F']['IdLienFamille'], $data['VueJoueur']['idJoueur']),
                                            'title' => 'Retirer le lien parental avec ce joueur',
                                            'height' => '16px',
                                            'width' => '16px'));
                } 
            } else {
                echo $parent[0]['NomComplet'];
            } ?>
            </h2>

            <span><b>Adresse :</b></span></br>
            <span><?=$parent['P']['Adresse'];?></span></br>
            <span><?=$parent['P']['Ville'];?></span></br>
            <span><?=preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $parent['P']['CodePostal']);?></span></br><br>

            <table style="margin-left:12px;">

              <tr>
                  <td>Tel maison:</td>
                  <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent[0]['TelMaison']);?></td>
              </tr>
              <tr>
                  <td>Tel mobile:</td>
                  <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $parent[0]['TelMobile']);?></td>
              </tr>
              <tr>
                  <td>Tel travail:</td>
                  <td><?=$parent['P']['TelTravail'];?></td>
              </tr>
			</table></br>
    
            <span><a href="mailto:<?=$parent['P']['Courriel1'];?>"><?=$parent['P']['Courriel1'];?></a></span></br>
            <span><a href="mailto:<?=$parent['P']['Courriel2'];?>"><?=$parent['P']['Courriel2'];?></a></span></br>
            
	</div>
<? endforeach; ?>
      </div>
    </fieldset>
    <? if(($this->Session->read('User.role') == 'admin' || $parent == true) && $nbParents < 2) {
        echo $this->Html->link('Ajouter un parent', 
                            array('action' => 'ajoutParent',$data['VueJoueur']['idJoueur']),
                            array('class' => 'pure-button pure-button-primary'));
        echo '<br/><br/>';
    } ?>

<? if($this->Session->read('User.role') == 'admin') { ?>

    <fieldset>
        <legend>Saisons précédentes</legend>
        <table id="tabdonnee" width="94%">
            <tr>
                <th>Saison</th>
                <th>Catégorie</th>
                <th>Équipe</th>
                <th>Action</th>
            </tr>

            <? foreach($saisons as $saison) { ?>
                <tr>
                    <td><?=$saison['VueImpot']['Saison']; ?></td>
                    <td><?=$saison['VueImpot']['NomCategorie'].' '.$saison['VueImpot']['Classe']; ?></td>
                    <td><?=$saison['VueImpot']['NomEquipe']; ?></td>
                    <td><?=$this->Html->link("Imprimer le reçu d'impôt", 
                        array('action' => 'recuImpot',$saison['VueImpot']['idInscription']), 
                        array('target' => '_blank')); ?>
                    </td>
                </tr>
            <? } ?>
        </table>
<? } ?>

</div>
<br/><br/>

