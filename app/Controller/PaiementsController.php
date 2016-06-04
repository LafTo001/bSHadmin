<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PaiementsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
    public $uses = array();
    
/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
    public function display() {
            $path = func_get_args();

            $count = count($path);
            if (!$count) {
                    return $this->redirect('/');
            }
            $page = $subpage = $title_for_layout = null;

            if (!empty($path[0])) {
                    $page = $path[0];
            }
            if (!empty($path[1])) {
                    $subpage = $path[1];
            }
            if (!empty($path[$count - 1])) {
                    $title_for_layout = Inflector::humanize($path[$count - 1]);
            }
            $this->set(compact('page', 'subpage', 'title_for_layout'));

            try {
                    $this->render(implode('/', $path));
            } catch (MissingViewException $e) {
                    if (Configure::read('debug')) {
                            throw $e;
                    }
                    throw new NotFoundException();
            }
    }
     
/**
 * Enregistrement des inscriptions dans le panier Paypal
 *
 * @return void
 *
 */
    
   public function panier() {
        
        $this->loadModel('Adulte');
        $parent = $this->Adulte->findById($this->Session->read('User.idParent'));
        
        $this->set('parent',$parent['Adulte']);
        
        $this->loadModel('Joueur');
        $joueurs = $this->Joueur->findJoueursParParent($this->Session->read('User.idParent'),1);
        $this->set(compact('joueurs'));
        
    }
    
    public function paypalReturn() {
        
        //tx=4SP71609PK545361N&st=Completed&amt=1%2e00&cc=CAD&cm=&item_number=
        
        if($_GET['st'] == "Completed" && $_GET['tx']) {
        
            // The custom hidden field (user id) sent along with the button is retrieved here. 
            //if($_GET['cm']) $user=$_GET['cm']; 
            // The unique transaction id. 
            $tx= $_GET['tx'];

            $identity = $this->getConfigValueByName('Identity Token Paypal');
            $urlConnect = $this->getConfigValueByName('Url Connexion Paypal');

            // Init curl
            $ch = curl_init(); 
            // Set request options 
            curl_setopt_array($ch, array ( CURLOPT_URL => $urlConnect,
              CURLOPT_POST => TRUE,
              CURLOPT_POSTFIELDS => http_build_query(array
                (
                  'cmd' => '_notify-synch',
                  'tx' => $tx,
                  'at' => $identity,
                )),
              CURLOPT_RETURNTRANSFER => TRUE,
              CURLOPT_HEADER => FALSE,
              // CURLOPT_SSL_VERIFYPEER => TRUE,
              // CURLOPT_CAINFO => 'cacert.pem',
            ));
            // Execute request and get response and status code
            $response = curl_exec($ch);
            //echo $response;
            $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Close connection
            curl_close($ch);
            if($status == 200 AND strpos($response, 'SUCCESS') === 0)
            {
                $response = explode("\n",$response);
                //var_dump($params);

                $params['Paiement'] = array();
                foreach($response as $field) {
                    $item = explode("=",$field);
                    if(isset($item[1])) {
                        $params['Paiement'][$item[0]] = $item[1];
                    } else {
                        $params['Paiement'][$item[0]] = null;
                    }
                }

                $params['Paiement']['payer_email'] = str_replace('%40','@',$params['Paiement']['payer_email']);
                $params['Paiement']['DatePaiement'] = date('Y-m-d H:i:s');
                $params['Paiement']['Montant'] = $params['Paiement']['mc_gross'];
                $params['Paiement']['TypePaiement'] = $params['Paiement']['payment_type'];
                $params['Paiement']['IdUser'] = $this->Auth->User('id');
                //var_dump($params);

                $rs = $this->Paiement->findByTxnId($tx);
                if(!empty($rs)) {
                    $this->Paiement->id = $rs['Paiement']['id'];
                }
                $this->Paiement->save($params);
                $idPaiement = $this->Paiement->id;
                
                $saison = $this->getConfigValueByName('Annee Inscription');
                $this->loadModel('Joueur');
                $this->loadModel('Inscription');

                for ($i = 1; $i <= $params['Paiement']['num_cart_items']; $i++) {
                    //chercher les enfants
                    $inscrits = $this->Joueur->findJoueursParParent($this->Session->read('User.idParent'),1);
                    foreach($inscrits as $cle => $inscrit) {
                        if($inscrit['VueJoueur']['IdCategorie'] == $params['Paiement']['item_number'.$i] &&
                                $inscrit['VueJoueur']['Paiement'] == 0) {
                            
                            $inscription = array();
                            $inscription['Inscription']['Paiement'] = $params['Paiement']['mc_gross_'.$i];
                            $inscription['Inscription']['modePaiement'] = 5;
                            $inscription['Inscription']['IdPaiement'] = $idPaiement;
                            $inscription['Inscription']['DerniereModif'] = date('Y-m-d H:i:s');
                            
                            $this->Inscription->id = $inscrit['VueJoueur']['idInscription'];
                            $this->Inscription->save($inscription);
                        }
                    }
                }
            }
            
        }
        
        $this->redirect(array('action' => 'confirmation'));
    }
    
    public function confirmation() {
        
    }
}

/*SUCCESS
mc_gross=30.00
protection_eligibility=Ineligible
item_number1=5
tax=0.00
item_number2=1
payer_id=Y99PLVWXZPSWU
ebay_txn_id1=
ebay_txn_id2=
payment_date=10%3A07%3A06+Sep+19%2C+2015+PDT
payment_status=Completed
charset=windows-1252
mc_shipping=0.00
mc_handling=0.00
first_name=test
mc_fee=1.17
custom=
payer_status=verified
business=tomlafleur25-facilitator%40gmail.com
num_cart_items=2
mc_handling1=0.00
mc_handling2=0.00
payer_email=tomlafleur25-buyer%40gmail.com
mc_shipping1=0.00
mc_shipping2=0.00
tax1=0.00
btn_id1=3214157
tax2=0.00
btn_id2=3214133
txn_id=9T230194YA9080511
payment_type=instant
last_name=buyer
item_name1=Bantam
receiver_email=tomlafleur25-facilitator%40gmail.com
item_name2=Novice
payment_fee=
quantity1=1
quantity2=1
receiver_id=R2K53REFQWGKE
txn_type=cart
mc_gross_1=25.00
mc_currency=CAD
mc_gross_2=5.00
residence_country=CA
transaction_subject=
payment_gross=
 */