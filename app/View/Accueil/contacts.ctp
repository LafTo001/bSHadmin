<h1>Baseball Saint-Hyacinthe</h1>
<p>Centre communautaire Douville <br/>
    5065 Gouin <br/>
    St-Hyacinthe, Qc <br/>
    J2S 1E3</p>

<p>Pour questions et inscriptions : (450) 773-7811</p>

<table style="margin-left: 25px; width: 50%">
    <tr>
        <td>Webmaster :</td>
        <td>Tommy Lafleur</td>
        <td><?=$this->Html->link('Contacter', 'mailto:tomlafleur25@gmail.com'); ?></td>
    </tr>
    <tr>
        <td>Responsable des arbitres :</td>
        <td>Luc Sansoucy</td>
        <td>(450) 278-5277</td>
    </tr>
</table>

<h1>Nos comités spécialisés</h1>
<table id="menuhead" width="100%" cellspacing="1" cellpadding="1">
    <tr>
        <th><?=$this->Html->link('Conseil',array('action' => 'contacts', 'conseil'));?></th>
        <th><?=$this->Html->link('Entraineurs',array('action' => 'contacts', 'entraineurs'));?></th>
        <th><?=$this->Html->link('Équipement',array('action' => 'contacts', 'equipement'));?></th>
        <th><?=$this->Html->link("Événements spéciaux",array('action' => 'contacts', 'evenements'));?></th>
        <th><?=$this->Html->link("Financement",array('action' => 'contacts', 'financement'));?></th>
        <th><?=$this->Html->link("Gouverneurs",array('action' => 'contacts', 'gouverneurs'));?></th>
        <th><?=$this->Html->link("Infrastructure",array('action' => 'contacts', 'infrastructure'));?></th>
        <th><?=$this->Html->link("Rallye cap",array('action' => 'contacts', 'rallyecap'));?></th>
        <th><?=$this->Html->link("Sélections",array('action' => 'contacts', 'selections'));?></th>
    </tr>
</table>

<? if($comite == 'conseil') { ?>
    <h2>Conseil d'administration</h2>
    <table style="margin-left: 25px; width: 40%">
        <tr>
            <td>Président :</td>
            <td>Patrice Dion</td>
            <td><?=$this->Html->link('Contacter', 'mailto:canucksreds@hotmail.com'); ?></td>
        </tr>
        <tr>
            <td>Vice-président :</td>
            <td>Dominic Fryer</td>
            <td><?=$this->Html->link('Contacter', 'mailto:fryerfamily@mac.com'); ?></td>
        </tr>
        <tr>
            <td>Trésorier : </td>
            <td>André Beauregard</td>
            <td><?=$this->Html->link('Contacter', 'mailto:loisirsdouville@maskatel.net'); ?></td>
        </tr>
        <tr>
            <td>Secrétaire : </td>
            <td>Guylaine Dion</td>
            <td><?=$this->Html->link('Contacter', 'mailto:guylaine.dion1972@gmail.com'); ?></td>
        </tr>
    </table>
<? } ?>

<? if($comite == 'entraineurs') { ?>
    <h2>Comité des entraineurs</h2>
    <table style="margin-left: 25px; width: 60%">
        <tr>
            <td>Directeur catégorie Atome :</td>
            <td>Alexandre Poudrette</td>
            <td><?=$this->Html->link('Contacter', 'mailto:alexandrepoudrette75@gmail.com'); ?></td>
        </tr>
        <tr>
            <td>Directeur catégorie Moustique :</td>
            <td>Dominic Fryer</td>
            <td><?=$this->Html->link('Contacter', 'mailto:fryerfamily@mac.com'); ?></td>
        </tr>
        <tr>
            <td>Directeur catégorie Pee-wee :</td>
            <td>Éric Pelletier</td>
            <td><?=$this->Html->link('Contacter', 'mailto:eric.pelletier@usinagemaska.com'); ?></td>
        </tr>
        <tr>
            <td>Directeur catégorie Bantam :</td>
            <td>Richard Graveline</td>
            <td><?=$this->Html->link('Contacter', 'mailto:richard.graveline@cgocable.ca'); ?></td>
        </tr>
    </table>
<? } ?>

<? if($comite == 'equipement') { ?>
    <h2>Comité de l'équipement</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Membres :</td>
        <td>André Beauregard</td>
        <td><?=$this->Html->link('Contacter', 'mailto:loisirsdouville@maskatel.net'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Dominic Fryer</td>
        <td><?=$this->Html->link('Contacter', 'mailto:fryerfamily@mac.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Alexandre Poudrette</td>
        <td><?=$this->Html->link('Contacter', 'mailto:alexandrepoudrette75@gmail.com'); ?></td>
    </tr>
</table>
<? } ?>

<? if($comite == 'evenements') { ?>
    <h2>Comité des événements spéciaux</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Membres :</td>
        <td>André Beauregard</td>
        <td><?=$this->Html->link('Contacter', 'mailto:loisirsdouville@maskatel.net'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Guylaine Dion</td>
        <td><?=$this->Html->link('Contacter', 'mailto:guylaine.dion1972@gmail.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Patrice Dion</td>
        <td><?=$this->Html->link('Contacter', 'mailto:canucksreds@hotmail.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Frédéric Didier</td>
        <td><?=$this->Html->link('Contacter', 'mailto:frederic.didier@cgocable.ca'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Jeremy Hélie</td>
        <td><?=$this->Html->link('Contacter', 'mailto:jeremybrolisport@outlook.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Steve Lachance</td>
        <td><?=$this->Html->link('Contacter', 'mailto:slachance6@sympatico.ca'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Tommy Lafleur</td>
        <td><?=$this->Html->link('Contacter', 'mailto:tomlafleur25@gmail.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Suzanne St-Pierre</td>
        <td><?=$this->Html->link('Contacter', 'mailto:suzannest_pierre@hotmail.com'); ?></td>
    </tr>
</table>
<? } ?>

<? if($comite == 'financement') { ?>
    <h2>Comité du financement</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Membres :</td>
        <td>Jeremy Hélie</td>
        <td><?=$this->Html->link('Contacter', 'mailto:jeremybrolisport@outlook.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Steve Lachance</td>
        <td><?=$this->Html->link('Contacter', 'mailto:slachance6@sympatico.ca'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Éric Pelletier</td>
        <td><?=$this->Html->link('Contacter', 'mailto:eric.pelletier@usinagemaska.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Suzanne St-Pierre</td>
        <td><?=$this->Html->link('Contacter', 'mailto:suzannest_pierre@hotmail.com'); ?></td>
    </tr>
</table>
<? } ?>
    
<? if($comite == 'gouverneurs') { ?>
    <h2>Nos gouverneurs</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Gouverneur LBAVR :</td>
        <td>Benoit Vigeant-Sansoucy</td>
        <td><?=$this->Html->link('Contacter', 'mailto:bins_@hotmail.com'); ?></td>
    </tr>
    <tr>
        <td>Gouverneur LBCAA</td>
        <td>Alexandre Poudrette</td>
        <td><?=$this->Html->link('Contacter', 'mailto:alexandrepoudrette75@gmail.com'); ?></td>
    </tr>
</table>
<? } ?>
    
<? if($comite == 'infrastructure') { ?>
    <h2>Comité des infrastructures</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Membres :</td>
        <td>Martin Desnoyers</td>
        <td><?=$this->Html->link('Contacter', 'mailto:martin.desnoyers@cgocable.ca'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Dominic Fryer</td>
        <td><?=$this->Html->link('Contacter', 'mailto:fryerfamily@mac.com'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Alexandre Poudrette</td>
        <td><?=$this->Html->link('Contacter', 'mailto:alexandrepoudrette75@gmail.com'); ?></td>
    </tr>
</table>
<? } ?>
    
<? if($comite == 'rallyecap') { ?>
    <h2>Comité de la catégorie Novice/Rallyecap</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Coordonnateur :</td>
        <td>Tommy Lafleur</td>
        <td><?=$this->Html->link('Contacter', 'mailto:tomlafleur25@gmail.com'); ?></td>
    </tr>
    <tr>
        <td>Responsable groupe 2009 : </td>
        <td>Normand Chouinard</td>
        <td><?=$this->Html->link('Contacter', 'mailto:normand.chouinard@gmail.com'); ?></td>
    </tr>
    <tr>
        <td>Responsable groupe 2010 : </td>
        <td>Olivier Poitras</td>
        <td><?=$this->Html->link('Contacter', 'mailto:opoitras19@gmail.com'); ?></td>
    </tr>
    <tr>
        <td>Responsable groupe 4-5 ans : </td>
        <td>Sylvain Houle</td>
        <td><?=$this->Html->link('Contacter', 'mailto:katrine.caisse@cgocable.ca'); ?></td>
    </tr>
</table>
<? } ?>
    
<? if($comite == 'selections') { ?>
    <h2>Comité des sélections et encadrement</h2>
    <table style="margin-left: 25px; width: 60%">
    <tr>
        <td>Membres :</td>
        <td>André Beauregard</td>
        <td><?=$this->Html->link('Contacter', 'mailto:loisirsdouville@maskatel.net'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td>Frédéric Didier</td>
        <td><?=$this->Html->link('Contacter', 'mailto:frederic.didier@cgocable.ca'); ?></td>
    </tr>
</table>
<? } ?>
    
    <br/>




