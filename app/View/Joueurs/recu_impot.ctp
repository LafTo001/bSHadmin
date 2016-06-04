<!-- /joueurs/recu_impot.ctp -->

<script type="text/javascript">
function imprimer_page(){
  window.print();
}
</script>

<br/>
<div style="margin-left: 15px;">
    <?=$this->Html->image('logo.jpg', array('id' => 'logo')); ?>
    <span id="titre">&nbsp; Reçu pour crédit d'impôt fédéral</span>
    <br/><br/><br/>

    <aside>
        <form>
          <input id="impression" name="impression" type="button" onclick="imprimer_page()" value="Imprimer ce reçu" />
        </form>
    </aside>

    <table id="recu">
        <tr>
            <td style="width:200px;">Nom du participant :</td>
            <td><b><?=$recu['VueImpot']['NomJoueur']; ?></b></td>
        </tr
        <tr>
            <td>Date de naissance :</td>
            <td><?=$recu['VueImpot']['DateNaissance']; ?></td>
        </tr>
        <tr>
            <td>Adresse :</td>
            <td><?=$recu['VueImpot']['Adresse']; ?></td>
        </tr>
        <tr>
            <td>Ville :</td>
            <td><?=$recu['VueImpot']['Ville']; ?></td>
        </tr>
        <tr>
            <td>Code postal :</td>
            <td><?=preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $recu['VueImpot']['CodePostal']);?></td>
        </tr>
    </table>

    </br><br/>

    <table id="recu">
        <tr>
            <td style="width:350px;">Activité :</td>
            <td>Baseball saison <?=$recu['VueImpot']['Saison']; ?></td>
        </tr>
        <tr>
            <td>Montant de l'inscription :</td>
            <td><?=$recu['VueImpot']['Paiement']; ?>.00 $</td>
        </tr>
        <? if($recu['VueImpot']['PaiementEcole'] > 0) { ?>
            <tr>
                <td>Inscription à l'école de baseball :</td>
                <td><?=$recu['VueImpot']['PaiementEcole']; ?>.00 $</td>
            </tr>
            <tr>
                <td><b>Total :</b></td>
                <td><b><?=$recu['VueImpot']['Paiement']+$recu['VueImpot']['PaiementEcole']; ?>.00 $</b></td>
                
            </tr>
            <tr>
                <td></td>
                <td></td>
                
            </tr>
        <? } ?>
        <tr>
            <td>Numéro de reçu :</td>
            <td>#<?=$recu['VueImpot']['idInscription']; ?></td>
        </tr>
    </table>

    <br/><br/>

    <table id="recu">
        <tr>
            <td style="width:350px;">Nom du club :</td>
            <td>Baseball Saint-Hyacinthe</td>
        </tr>
        <tr>
            <td>Adresse :</td>
            <td>5065 Gouin</td>
        </tr>
        <tr>
            <td></td>
            <td>Saint-Hyacinthe, Qc, J2S 1E3</td>
        </tr>
        <tr>
            <td>Numéro d'entreprise du Québec :</td>
            <td>1148958599</td>
        </tr>
        <tr>
            <td>Numéro d'entreprise du Canada :</td>
            <td>84078 6875 RP001</td>
        </tr>
    </table>

    </br><br/>

    <table id="recu">
        <tr>
            <td style="width:350px;">Trésorier :</td>
            <td>André Beauregard</td>
        </tr>
        <tr>
            <td></td>
            <td><?=$this->Html->image('signature.png'); ?></td>
        </tr>  
    </table>
</div>
<br/><br/><br/><br/>
