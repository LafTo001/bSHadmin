    <h1><?=$data['VueJoueur']['nomComplet'];?></h1>

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
      </div></div>
    </fieldset><br/>
    
    <fieldset>
      <legend>Les parents :</legend>
      <div class="pure-g">      
<? foreach($parents as $parent) : ?>
        <div class="pure-u-1-3">
            <h2>
            <? if($this->Session->read('User.role') == 'admin') {  
                echo $this->Html->link($parent[0]['NomComplet'],array('controller' => 'parents', 'action' => 'fiche', $parent['P']['id'])).' &nbsp; '.
                $this->Html->Image('glyphicons/glyphicons_030_pencil.png',
                      array('url' => array('controller' => 'parents', 'action' => 'edit', $parent['P']['id']),
                      'title' => 'Éditer',
                      'height' => '16px',
                      'width' => '16px'));
                  
                if($parent[0]['Ordre'] == 0) {
                    echo ' &nbsp; '.$this->Html->Image('glyphicons/glyphicons_207_remove_2.png',
                                        array('url' => array('controller' => 'parents', 'action' => 'supprimer', 
                                            $parent['F']['IdLienFamille'], $data['VueJoueur']['idJoueur']),
                                            'title' => 'Retirer',
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
    </fieldset><br/>
    <br/><br/>
    
    </div>

