<!-- /equipes/liste.ctp -->

<h1>Liste des équipes <?=$annee; ?></h1>

<table id="tabdonnee" width="97%">
    <tr>
        <th id="colhead">Catégorie</th>
        <th id="colhead">Classe</th>
        <th id="colhead">Nom</th>
        <? if($this->Session->read('User.role') == 'admin') { ?>
            <th id="colhead">Ch.</th>
        <? } ?>
        <th id="colhead">Entraineur-chef</th>
        <th id="colhead">Courriel</th>
        <th id="colhead">Maison</th>
        <th id="colhead">Mobile</th>
        <? if($this->Session->read('User.role') == 'admin') { ?>
            <th id="colhead">Actions</th>
        <? } ?>
    </tr>
	  
<? if($listeSH) {
    $count = 0;
    foreach($listeSH as $equipe) {
        
        if($count++ % 2) echo '<tr>'; else echo '<tr id="impair">'; ?>

            <td><?=$equipe['VueEquipe']['nomCategorie']; ?></td>
            <td><?=$equipe['VueEquipe']['Classe']; ?></td>
            <? if($this->Session->read('User.role') == 'admin') { ?>
                <td><?=$this->Html->link($equipe['VueEquipe']['NomEquipe'],array('action' => 'fiche',$equipe['VueEquipe']['idEquipe'])); ?></td>
                <td style="text-align:center;"><? if($equipe['VueEquipe']['ConfirmationChandail'] == 1) {
                        echo $this->Html->image('glyphicons/glyphicons_283_t-shirt.png',array('width' => '16px', 'height' => '16px'));
                } ?>
                </td>
            <? } else { ?>
                <td><?=$equipe['VueEquipe']['NomEquipe']; ?></td>
            <? } ?>
            <td><?=$equipe['VueEquipe']['nomEntraineur']; ?></td>
            <td><?=$this->Html->link($equipe['VueEquipe']['CourrielEnt'],'mailto:'.$equipe['VueEquipe']['CourrielEnt']); ?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $equipe['VueEquipe']['TelMaisonEnt']); ?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $equipe['VueEquipe']['TelMobileEnt']); ?></td>
            <? if($this->Session->read('User.role') == 'admin') { ?>
                <td style="text-align:center;"><?=$this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                            array('url' => array('action' => 'editer', $equipe['VueEquipe']['idEquipe']),
                                'width' => '16px', 'height' => '16px')).' &nbsp; '.
                       $this->Html->image('glyphicons/glyphicons_207_remove_2.png', 
                            array('url' => array('action' => 'supprimer', $equipe['VueEquipe']['idEquipe']),
                                'width' => '16px', 'height' => '16px'));?>
                </td>
            <? } ?>
	</tr>
    
    <? }
} else { ?>
        <tr><td id="impair" colspan="8">Aucune équipe enregistrée</td></tr>
<? }?>
             
</table>

<h2>Richelieu-Yamaska</h2>

<table id="tabdonnee" width="97%">
    <tr>
        <th id="colhead">Catégorie</th>
        <th id="colhead">Classe</th>
        <th id="colhead">Nom</th>
        <? if($this->Session->read('User.role') == 'admin') { ?>
            <th id="colhead">Ch.</th>
        <? } ?>
        <th id="colhead">Entraineur-chef</th>
        <th id="colhead">Courriel</th>
        <th id="colhead">Maison</th>
        <th id="colhead">Mobile</th>
        <? if($this->Session->read('User.role') == 'admin') { ?>
            <th id="colhead">Actions</th>
        <? } ?>
    </tr>
	  
<? if($listeRY) {
    $count = 0;
    foreach($listeRY as $equipe) {
        
        if($count++ % 2) echo '<tr>'; else echo '<tr id="impair">'; ?>

            <td><?=$equipe['VueEquipe']['nomCategorie']; ?></td>
            <td><?=$equipe['VueEquipe']['Classe']; ?></td>
            <? if($this->Session->read('User.role') == 'admin') { ?>
                <td><?=$this->Html->link($equipe['VueEquipe']['NomEquipe'],array('action' => 'fiche',$equipe['VueEquipe']['idEquipe'])); ?></td>
                <td style="text-align:center;"><? if($equipe['VueEquipe']['ConfirmationChandail'] == 1) {
                        echo $this->Html->image('glyphicons/glyphicons_283_t-shirt.png',array('width' => '16px', 'height' => '16px'));
                } ?>
                </td>
            <? } else { ?>
                <td><?=$equipe['VueEquipe']['NomEquipe']; ?></td>
            <? } ?>
            <td><?=$equipe['VueEquipe']['nomEntraineur']; ?></td>
            <td><?=$this->Html->link($equipe['VueEquipe']['CourrielEnt'],'mailto:'.$equipe['VueEquipe']['CourrielEnt']); ?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $equipe['VueEquipe']['TelMaisonEnt']); ?></td>
            <td><?=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $equipe['VueEquipe']['TelMobileEnt']); ?></td>
            <? if($this->Session->read('User.role') == 'admin') { ?>
                <td style="text-align:center;"><?=$this->Html->image('glyphicons/glyphicons_030_pencil.png', 
                            array('url' => array('action' => 'editer', $equipe['VueEquipe']['idEquipe']),
                                'width' => '16px', 'height' => '16px')).' &nbsp; '.
                       $this->Html->image('glyphicons/glyphicons_207_remove_2.png', 
                            array('url' => array('action' => 'supprimer', $equipe['VueEquipe']['idEquipe']),
                                'width' => '16px', 'height' => '16px'));?>
                </td>
            <? } ?>
	</tr>
    
    <? }
} ?>
             
</table><br/>

<? if($this->Session->read('User.role') == 'admin') {
    echo $this->Html->link('Ajouter une équipe',array('action' => 'ajouter'), array('class' => 'pure-button pure-button-primary')).'<br/>';
    echo '<p><a href="mailto:'.$listeCourriels.'">Envoyer un courriel à tous les entraineurs de St-Hyacinthe</a></p>';
} ?>
<br/><br/>