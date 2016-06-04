<!-- app/View/Tournois/index.ctp -->
<h1>Les tournois provinciaux Baseball Québec</h1>

<p><a href="http://grandeviree.baseballquebec.com/fr/index.html" target="_blank">Page officielle des tournois provinciaux</a></p>

<p><b>Liste des tournois déjà enregistrés</b></p>

<div class="pure-form pure-form-stacked">
    <div class="pure-u-1-2">
        <?=$this->Form->input('IdCategorie',array(
            'label' => '',
            'div' => 'pure-g',
            'options' => $listeCategories,
            'selected' => '/baseball/tournois/changerCategorie/'.$this->Session->read('Tournoi.Categorie').'/',
            'empty' => array('/baseball/tournois/changerCategorie/0/' => 'Toutes les catégories'),
            'onchange' => 'location = this.options[this.selectedIndex].value;'
        ));?><br/>
    </div>
    
    <div class="pure-u-2-5">
        <br/><b><?=$this->Html->link('Ajouter un tournoi auquel vous allez participer',array('action' => 'ajouter'));?></b>
    </div>
</div>



<table id="tabdonnee" width="97%">
  <tr>
    <th id="colhead">Tournoi</th>
    <th id="colhead">Niveau</th>
    <th id="colhead"></th>
  </tr>

<? $cmpt = 0;
foreach ($tournois as $tournoi) {
    if(++$cmpt %2 == 1) echo '<tr id="impair">'; else echo '<tr>';
?>
        <td><?=$tournoi['VueTournoi']['NomTournoi'];?></td>
        <td><?=$tournoi['VueTournoi']['NomCategorie'];?></td>
        <td><?=$this->Html->link('Voir les parties',array('action' => 'parties',$tournoi['VueTournoi']['idLigue'],
                                                                strtr($tournoi['VueTournoi']['NomTournoi'],
                                                                    '@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ',
                                                                    'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy_')
        ));?></td>
    </tr>
<? } ?>

</table>

<p><? //$this->Html->link('Ajouter un tournoi auquel vous allez participer',array('action' => 'ajouter'));?></p>
<br/><br/>