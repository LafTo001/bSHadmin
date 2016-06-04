<!-- /parents/paiement.ctp -->

<h1>Effectuer le paiement</h1>

<span><b><?=$parent['Prenom'].' '.$parent['NomFamille'];?></b></span><br/>
<span><?=$parent['Adresse'];?></span><br/>
<span><?=$parent['Ville'];?></span><br/>
<span><?=preg_replace("/([A-Z0-9]{3})([A-Z0-9]{3})/", "$1 $2", $parent['CodePostal']);?></span><br/>

<!-- PayPal Logo -->
<table border="0" cellpadding="15" cellspacing="0" align="left">
    <tr><td align="center"><a href="https://www.paypal.com/ca/webapps/mpp/paypal-popup" title="Fonctionnement de PayPal" onclick="javascript:window.open('https://www.paypal.com/ca/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/fr_CA/mktg/logo-image/AM_mc_vs_dc_ae.jpg" border="0" alt="Marques d'acceptation PayPal"></a></td></tr>
</table>
<!-- Logo PayPal -->
 
<table id="tabdonnee" width="97%">
  <tr>
    <th>Joueur</th>
    <th>Date de naissance</th>
    <th>Catégorie</th>
    <th>Inscription</th>
    <th>Paiement</th>
  </tr>
<? $count = 0; ?>
<? foreach($joueurs as $joueur) { ?>
    <? if(++$count % 2) echo '<tr id="pair" style="height:50px;">'; else echo '<tr id="impair" style="height:50px;">'; ?>
        <td style="vertical-align: middle; text-align:center;"><?=$joueur['VueJoueur']['nomPrenom'];?></td>
        <td style="vertical-align: middle; text-align:center;"><?=$joueur['VueJoueur']['DateNaissance'];?></td>
        <td style="vertical-align: middle; text-align:center;"><?=$joueur['VueJoueur']['NomCategorie'];?></td>
        <td style="vertical-align: middle; text-align:center;"><?=$joueur['VueJoueur']['MontantPreInscription'].' $';?></td>
        <td style="vertical-align: middle; text-align:center;">
        <? if($joueur['VueJoueur']['Paiement'] == 0) { ?>

            <form target="paypal" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="<?=$joueur['VueJoueur']['PaypalPreInscription'];?>">
            <input type="hidden" name="custom" value="<?=$joueur['VueJoueur']['nomComplet'];?>">
            <input type="image" src="https://www.paypalobjects.com/fr_CA/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
            <img alt="" border="0" src="https://www.paypalobjects.com/fr_CA/i/scr/pixel.gif" width="1" height="1">
            </form>
        <? } else {
        echo 'Payé - merci'; }?>
        </td>
    </tr>
<? } ?>
</table>
<br/><br/>

<div style="margin-left:15px;">
    <form target="paypal" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" >
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHBwYJKoZIhvcNAQcEoIIG+DCCBvQCAQExggE6MIIBNgIBADCBnjCBmDELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExETAPBgNVBAcTCFNhbiBKb3NlMRUwEwYDVQQKEwxQYXlQYWwsIEluYy4xFjAUBgNVBAsUDXNhbmRib3hfY2VydHMxFDASBgNVBAMUC3NhbmRib3hfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMA0GCSqGSIb3DQEBAQUABIGAG72QjJ6SoUGwoFqVBsqV3/lsoj0tF3Ed46CR6GrAHAYEYqM3PHpluGqsB6Mh/DMKvl89AEhdTw4hzx3xQWAS4GEnYFDxVyqomCt+OfPRXAdhmAszH5nWqlw/O0nWi4s8wqJ7EGPWRKgC9rXbhgQeb55KpCzE8AJlYOIb1pqdzl8xCzAJBgUrDgMCGgUAMFMGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQI5Y+MZjH6SleAML6S1RnGdnb5uHM34NFszapYXSl0yAuLJn+oX/ybzFIBdnXyzgskHRxsHLL3UCB9xaCCA6UwggOhMIIDCqADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGYMQswCQYDVQQGEwJVUzETMBEGA1UECBMKQ2FsaWZvcm5pYTERMA8GA1UEBxMIU2FuIEpvc2UxFTATBgNVBAoTDFBheVBhbCwgSW5jLjEWMBQGA1UECxQNc2FuZGJveF9jZXJ0czEUMBIGA1UEAxQLc2FuZGJveF9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwNDE5MDcwMjU0WhcNMzUwNDE5MDcwMjU0WjCBmDELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExETAPBgNVBAcTCFNhbiBKb3NlMRUwEwYDVQQKEwxQYXlQYWwsIEluYy4xFjAUBgNVBAsUDXNhbmRib3hfY2VydHMxFDASBgNVBAMUC3NhbmRib3hfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3luO//Q3So3dOIEv7X4v8SOk7WN6o9okLV8OL5wLq3q1NtDnk53imhPzGNLM0flLjyId1mHQLsSp8TUw8JzZygmoJKkOrGY6s771BeyMdYCfHqxvp+gcemw+btaBDJSYOw3BNZPc4ZHf3wRGYHPNygvmjB/fMFKlE/Q2VNaic8wIDAQABo4H4MIH1MB0GA1UdDgQWBBSDLiLZqyqILWunkyzzUPHyd9Wp0jCBxQYDVR0jBIG9MIG6gBSDLiLZqyqILWunkyzzUPHyd9Wp0qGBnqSBmzCBmDELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExETAPBgNVBAcTCFNhbiBKb3NlMRUwEwYDVQQKEwxQYXlQYWwsIEluYy4xFjAUBgNVBAsUDXNhbmRib3hfY2VydHMxFDASBgNVBAMUC3NhbmRib3hfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAVzbzwNgZf4Zfb5Y/93B1fB+Jx/6uUb7RX0YE8llgpklDTr1b9lGRS5YVD46l3bKE+md4Z7ObDdpTbbYIat0qE6sElFFymg7cWMceZdaSqBtCoNZ0btL7+XyfVB8M+n6OlQs6tycYRRjjUiaNklPKVslDVvk8EGMaI/Q+krjxx0UxggGkMIIBoAIBATCBnjCBmDELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExETAPBgNVBAcTCFNhbiBKb3NlMRUwEwYDVQQKEwxQYXlQYWwsIEluYy4xFjAUBgNVBAsUDXNhbmRib3hfY2VydHMxFDASBgNVBAMUC3NhbmRib3hfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xNTA5MTgwMTAzNDNaMCMGCSqGSIb3DQEJBDEWBBRhUJAoaILlxALXwAC9bXfCRUmsRzANBgkqhkiG9w0BAQEFAASBgHRsXFs7Yy6wTC4iuVt+j/uiOSZo/WeEqZDSE9V+F/s5nkeSrNmX3tg/cuvIim2IlW5KEITov7g12zebj+savtC9wv5WQMEQWLRoJbC7gyMACaAYTSVpgZ2E+p3uEQTTTYmYJKTVmgIOf9kB2Lx53JmPH9IW5X/IWDC0etiPgn45-----END PKCS7-----
    ">
    <input type="image" src="https://www.sandbox.paypal.com/fr_CA/i/btn/btn_viewcart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
    <img alt="" border="0" src="https://www.sandbox.paypal.com/fr_CA/i/scr/pixel.gif" width="1" height="1">
    </form>
</div>

