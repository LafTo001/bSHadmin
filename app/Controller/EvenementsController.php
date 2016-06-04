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
App::uses('CakeEmail', 'Network/Email');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class EvenementsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        public $minutes15 = array('00','15','30','45');
        var $helpers = array('Html');
        //var $helpers = array('Html','Ajax','Javascript');

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

    public function index() {

    }
    
    public function ajouter($date=null) {
        $this->Session->write('Evenement.Date',$date);
        $this->redirect(array('action' => 'reservation'));
    }

    public function reservation($id=null) {
        
        $this->loadModel('VueEvenement');
        if(isset($this->request->data['Envoyer']) && $this->request->data['Envoyer'] == 'Submit') {
            $this->enregistrer();
        }
        
        if($id != null) {
            $event = $this->VueEvenement->findByIdevenement($id);
            $this->set(compact('event'));
        }
        
        if(!isset($this->request->data['Evenement'])) {
            if($id != null) {
                $event = $this->VueEvenement->findByIdevenement($id);
                $this->request->data['Evenement'] = $event['VueEvenement'];
                $this->request->data['Evenement']['DebutEvenement'] = str_replace("h",":",$this->request->data['Evenement']['DebutEvenement']);
                $this->request->data['Evenement']['FinEvenement'] = str_replace("h",":",$this->request->data['Evenement']['FinEvenement']);
            } else {
                $this->request->data['Evenement'] = $this->initialiserReservation();
            }
        }
        
        //var_dump($this->request->data);

        $this->request->data['Evenement']['id'] = $this->request->data['Evenement']['idEvenement'];
        
        $this->set('lstTerrains', $this->requestAction('terrain/listerTerrains'));
        $heuresDebut = $this->listerHeuresDebut($this->request->data);
        $this->set('lstHeuresDebut',$heuresDebut['liste']);
        $this->request->data['Evenement']['DebutEvenement'] = $heuresDebut['selected'];
        $this->set('lstHeuresFin',$this->listerHeuresFin($this->request->data));
        $this->set('lstTypes',$this->listerTypes());
        $this->set('lstConfirm',$this->listerConfirmation());

        $this->loadModel('VueDate');
        $date = $this->VueDate->findByDate($this->request->data['Evenement']['DateEvenement']);
        $this->set('dateFormat',$date['VueDate']['DateFormat']);

        //lister les événements à afficher à droite de la page
        $this->loadModel('VueEvenement');
        $events = $this->VueEvenement->find('all',array('conditions' => array(
                    'DateEvenement' => $this->request->data['Evenement']['DateEvenement'],
                    'IdTerrain' => $this->request->data['Evenement']['IdTerrain'],
                    'Confirmation !=' => '2'),
                'order' => 'Datetime'
        ));
        $this->set('events',$events);

        //liste des terrains et équipes
        if($this->Session->read('User.role') == 'terrain' || $this->Session->read('User.role') == 'admin') {
            $this->set('lstEquipesReserve',$this->listerEquipesReserve());
        } else {
            $this->set('listeTerrains',$this->requestAction('terrain/listerTerrains'));
            $this->loadModel('VueEquipe');
            $equipe = $this->VueEquipe->findByIdequipe($this->Session->read('Equipe.id'));
            if(!empty($equipe)) {
                $this->set('equipe',$equipe['VueEquipe']['NomComplet']);
            }
        }
        
        $lstCatPratique = array(2 => 'Tous les Atomes B', 3 => 'Tous les Moustiques B');
        $this->set(compact('lstCatPratique'));

        if($this->request->data['Evenement']['TypeEvenement'] == 1) {
            $this->set('lstParties',$this->listerParties());
        }

        $this->Session->write('Terrain.id', $this->request->data['Evenement']['IdTerrain']);
        $this->set('terrain',$this->requestAction('terrain/getTerrain'));

        if($this->request->data['Evenement']['idEvenement'] > 0 && $this->request->data['Evenement']['TypeEvenement'] == 1) {
            $this->loadModel('VuePartie');
            $partie = $this->VuePartie->findByIdpartie($event['VueEvenement']['IdPartie']);
            $this->set('partie',$partie);
        }

        if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->requestAction('equipes/listerEquipesEntraineur'));
        }
        
        //validations
        if($this->request->data['Evenement']['idEvenement'] == 1 && $this->request->data['Evenement']['IdPartie'] == null) {
            $this->Session->setFlash(__("Une partie à reprendre doit être sélectionnée."));
        }
        elseif($this->request->data['Evenement']['idEvenement'] == 2 && $this->request->data['Evenement']['IdEquipe'] == null) {
            $this->Session->setFlash(__("Une équipe doit être sélectionnée."));
        }     
    }
    
    public function enregistrer() {
        $date = $this->request->data['Evenement']['DateEvenement'];
        $this->request->data['Evenement']['DebutEvenement'] = $date.' '.$this->request->data['Evenement']['DebutEvenement'].':00';
        $this->request->data['Evenement']['FinEvenement'] = $date.' '.$this->request->data['Evenement']['FinEvenement'].':00';
        
        if($this->request->data['Evenement']['TypeEvenement'] == 1) {
            $event = $this->Evenement->findByIdpartie($this->request->data['Evenement']['IdPartie']);
            $this->request->data['Evenement']['id'] = $event['Evenement']['id'];
        }
        
        if($this->request->data['Evenement']['TypeEvenement'] == 2 &&
            $this->Session->read('User.role') == 'entraineur' &&
            !isset($this->request->data['Evenement']['IdEquipe'])) {
            $this->request->data['Evenement']['IdEquipe'] = $this->Session->read('Equipe.id');
        }
        
        $envoyerEmailReponse = false;
        if(isset($this->request->data['Evenement']['Confirmation']) && $this->request->data['Evenement']['Confirmation'] > 0) {
            $envoyerEmailReponse = true;
        }
        if(!isset($this->request->data['Evenement']['Confirmation'])) {
            $this->request->data['Evenement']['Confirmation'] = $this->Session->read('User.role') == 'terrain';
            $this->request->data['Evenement']['Demandeur'] = $this->Auth->User('id');
        }
        
        $this->request->data['Evenement']['IdSerie'] = 0;
        $this->request->data['Evenement']['DateCreation'] = date('Y-m-d H:i:s');
        $this->request->data['Evenement']['DerniereModif'] = date('Y-m-d H:i:s');
        $this->request->data['Evenement']['ModifParUsager'] = $this->Auth->User('id');
        
        //var_dump($this->request->data);
        
        if(strtotime($this->request->data['Evenement']['DebutEvenement']) < strtotime($this->request->data['Evenement']['FinEvenement'])) {
            $id = $this->request->data['Evenement']['idEvenement'];
            if($this->request->data['Evenement']['idEvenement'] > 0) {
                $this->Evenement->id = $id;
            }
            $this->Evenement->save($this->request->data);
            
            if($this->Session->read('User.role') != 'terrain' && $this->Auth->User('id') != 1258) { // A. Beauregard
                $this->emailConfirmationReservation($this->request->data['Evenement']);
            }
            unset($this->request->data);
        }
        else {
            $this->Session->setFlash(__("Erreur : L'événement n'a pas été créé/modifié"));
        }
        
        if($this->Session->read('User.role') == 'terrain') {
            $this->getNombreConfirmation();
        }
        
        if($envoyerEmailReponse == true) {
            $this->emailReponseDemandeur($id);
        }
        
        $this->redirect(array('action' => 'jour', str_replace("/","-",$date)));
    }
    
    public function supprimer($id,$confirm = 0) {
        $this->loadModel('VueEvenement');
        $event = $this->VueEvenement->findByIdevenement($id);
        
        if($confirm == 1) {
            if($this->Session->read('User.role') == 'terrain') {
                $this->emailReservationEffacee($event['VueEvenement'],'terrain');
                $this->Session->setFlash(__("Un courriel a été envoyé à l'entraineur de l'équipe"));
            } else {
                $this->emailReservationEffacee($event['VueEvenement'],'entraineur');
                $this->Session->setFlash(__("Un courriel a été envoyé au responsable du terrain"));
            }
            
            $event['Evenement'] = array('Confirmation' => 2,
                           'DerniereModif' => date('Y-m-d H:i:s'),
                           'ModifParUsager' => $this->Auth->User('id'));
            $this->Evenement->id = $id;
            $this->Evenement->save($event);
            
            $this->redirect(array('action' => 'calendrier'));
        } else {
            $this->set(compact('event', 'id'));
        }
    }

    public function calendrier($noMois=null) {
        
        if($noMois != null) {
            $this->Session->write('Date.MoisCalendrier',$noMois);
            $this->redirect(array('action' => 'calendrier'));
        }

        $this->set('terrain', $this->requestAction('/Terrain/getTerrain'));
        
        if($this->Session->read('User.role') == 'entraineur' || 
                $this->Session->read('User.role') == 'admin' ||
                $this->Session->read('Terrain.Count') > 1) {
            $this->set('lstTerrains',$this->requestAction('/terrain/listerTerrains'));
        }
        
        $noMois = $this->setMois();
        $this->set('noMois',$noMois);
        
        $this->loadModel('Month');
        $mois = $this->Month->findById($noMois);
        $this->set('nomMois',$mois['Month']['NomMois']);

        //trouver les jours du mois
        $this->loadModel('Date');
        $jours = $this->Date->getJoursMois($noMois);

        //ajouter les evenements de chaque jour
        $this->loadModel('VueEvenement');
        //var_dump($jours);
        foreach ($jours as $cle => $jour) {
            $events = $this->VueEvenement->find('all', array('conditions' => array(
                                            'DateEvenement' => $jour['D']['date'],
                                            'IdTerrain' => $this->Session->read('Terrain.id'),
                                            'Confirmation !=' => '2'),
                                        'order' => array('Datetime' => 'asc')
            ));
            $jours[$cle]['D']['Events'] = $events;
            $jours[$cle]['D']['Reserve'] = (!empty($events)) ? 1 : 0;
        }
        $this->set('jours',$jours);

        //éléments du menu calendrier
        $premierJour = date('Y').'-'.$noMois.'-1';
        $this->set('premierJour', $premierJour);
        $date = $this->Date->findByDate($premierJour);
        $this->set('noSemaine', $date['Date']['NoSemaine']);
        
        if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->requestAction('equipes/listerEquipesEntraineur'));
        }
    }

    public function semaine($no=null) {
        
        if($no != null) {
            $this->Session->write('Date.NoSemaine',$no);
            $this->redirect(array('action' => 'semaine'));
        }
        
        $no = $this->Session->read('Date.NoSemaine');
        
        $this->set('terrain', $this->requestAction('terrain/getTerrain'));

        if($this->Session->read('Terrain.Count') > 1 || $this->Session->read('User.role') == 'entraineur' ||
                $this->Session->read('User.role') == 'admin') {
           $this->set('lstTerrains',$this->requestAction('terrain/listerTerrains'));
        }
        
        $this->loadModel('VueDate');
        $jours = $this->VueDate->find('all',array('conditions' => array(
                                'NoSemaine' => $no, 
                                'Annee' => date('Y'))));
        
        //ajouter les evenements de chaque jour
        $this->loadModel('VueEvenement');
        foreach ($jours as $cle => $jour) {
            $jours[$cle]['VueDate']['Events'] = $this->getDetailsJour($jour['VueDate']['date']);
        }
        $this->set('jours',$jours);
        
        //éléments du menu calendrier
        $date = $this->VueDate->find('first',
            array('conditions' => array('NoSemaine' => $no, 'JourSemaine' => '1', 'Annee' => date('Y'))));
        //var_dump($date);
        $this->set('premierJour', $date['VueDate']['DateNumeric']);
        $this->set('jourFormat', $date['VueDate']['DateSemaineDu']);
        $this->set('noMois', $date['VueDate']['NoMois']);
        $this->set('noSemaine', $no);
        
        if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->requestAction('equipes/listerEquipesEntraineur'));
        }
    }

    public function jour($date=null) {
        
        if($date != null) {
            $this->Session->write('Date.Date',$date);
            $this->redirect(array('action' => 'jour'));
        }
        
        $date = $this->Session->read('Date.Date');
        $this->set('date',$date);
        
        $this->set('terrain', $this->requestAction('terrain/getTerrain'));

        if($this->Session->read('Terrain.Count') > 1 || $this->Session->read('User.role') == 'entraineur' ||
                $this->Session->read('User.role') == 'admin') {
            $this->set('lstTerrains',$this->requestAction('terrain/listerTerrains'));
        }
        
        $this->loadModel('VueDate');
        $jour = $this->VueDate->findByDate($date);
        
        $jour['VueDate']['Events'] = $this->getDetailsJour($date);
        $this->set('jour',$jour['VueDate']);
        
        if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->requestAction('equipes/listerEquipesEntraineur'));
        }
    }
    
    public function confirmer() {
        
        //trouver les événements à confirmer
        $this->loadModel('VueEvenement');
        $rs = $this->VueEvenement->find('all',array('conditions' => array(
                                    'IdUsagerTerrain' => $this->Auth->User('id'),
                                    'Confirmation' => 0),
                                'order' => array('Datetime' => 'asc')
        ));
        $this->set('listeDemandes',$rs);
    }
    
    public function getDetailsJour($date) {    
        $cmpt = 0;
        
        $rowspan = 0;
        $plage = array();
        for ($h = 8; $h < 22; $h++) :
            foreach($this->minutes15 as $minute) :
                $datetime = $date.' '.$h.':'.$minute.':00';
                $datetime2 = $date.' '.$this->ajouter15minutes($h,$minute);
                $idTerrain = $this->Session->read('Terrain.id');

                $this->loadModel('VueEvenement');
                $evenements = $this->VueEvenement->find('all',
                    array('conditions' => array('Datetime >=' => $datetime,
                                                'Datetime <' => $datetime2,
                                                'IdTerrain' => $idTerrain,
                                                'Confirmation !=' => '2')
                ));
                
                $plage[$cmpt]['heure'] = $h.':'.$minute;
                $plage[$cmpt]['events'] = array();

                if (!empty($evenements)) {
                    foreach($evenements as $evenement) :
                        $event = $evenement['VueEvenement'];
                    
                        $rowspan = $event['Duree'] / 15;
                        $plage[$cmpt]['colspan'] = 1;
                        $plage[$cmpt]['rowspan'] = $rowspan;
                        $event['Temps'] = $event['DebutEvenement'].' - '.$event['FinEvenement'];
                        if($event['TypeEvenement'] == 3) {
                            $event['Description'] = $event['DescEvenement'];
                        } elseif($event['IdEquipe'] > 0) {
                            $event['Description'] = $event['NomType'].'<br/>'.$event['NomCompletEquipe'];
                        } else {
                            $event['Description'] = $event['NomType'].' '.$event['NomCategorie'].' '.$event['Classe'];
                        }
                        $event['EstDemandeur'] = ($event['Demandeur'] == $this->Auth->User('id')) ? 1 : 0;
                        
                        array_push($plage[$cmpt]['events'],$event);
                
                    endforeach;
                }
                elseif(empty($evenements) && $rowspan <= 0) {
                    $plage[$cmpt]['couleur'] = 'pair';
                    $plage[$cmpt]['colspan'] = 2;
                }
                elseif(empty($evenements)) {
                    $plage[$cmpt]['couleur'] = 'pair';
                    $plage[$cmpt]['colspan'] = 1;
                }
                $rowspan--;
                $cmpt++;
            endforeach;
        endfor;
        
        return $plage;
    }
    
    protected function getNombreConfirmation() {
        $this->loadModel('VueEvenement');
        $nbConfirm = $this->VueEvenement->find('count',array('conditions' => array(
                                    'IdUsagerTerrain' => $this->Auth->User('id'),
                                    'Confirmation' => '0')
        ));
        $this->Session->write('Terrain.nbConfirm',$nbConfirm);
    }
    
    public function emailConfirmationReservation($event) {
        
        $terrain = $this->requestAction('terrain/getTerrain');
        $debut = explode(" ",$event['DebutEvenement']);
        $fin = explode(" ",$event['FinEvenement']);

        $texte = '<html><p>Bonjour,</p>';
        $texte.= '<p>Voici votre confirmation de demande de réservation</p>';
        $texte.= '<p>Terrain: '.$terrain['NomTerrain'].', '.$terrain['Adresse'].', '.$terrain['Ville'].'<br/>';
        $texte.= 'Date: '.$debut[0].'<br/>';
        $texte.= 'De: '.$debut[1].' à '.$fin[1].'<br/>';
        if(isset($event['DescEvenement'])) {
            $texte.= 'Raison: '.$event['DescEvenement'].'<br/>';
        }
        $texte.= 'Commentaire: '.$event['Commentaire'];
        $texte.= '</p>';
        
        $texte.= '<p>Vous recevrez, dans les prochaines heures, une réponse de la part des responsables du terrain.</p>';
        
        $texte.= '<p>Si vous avez eu des difficultés techniques, vous pouvez communiquer avec le webmaster<br/>';
        $texte.= 'Tommy Lafleur<br/><a href="mailto:tomlafleur25@gmail.com">tomlafleur25@gmail.com</a></p></html>';
        
        $Email = new CakeEmail();
        $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
            ->to($this->Session->read('User.courriel'))
            ->emailFormat('html')
            ->subject('Demande de réservation de terrain')
            ->send($texte);
        
        //envoie du courriel aux responsables du terrain
        $texte2 = '<html><p>Bonjour,</p>';
        $texte2.= '<p>Vous avez reçu une demande de réservation pour le terrain de baseball</p>';

        $texte2.= '<p><a href="http://www.baseballsthyacinthe.com/bshadmin/evenements/confirmer/" target="_blank">';
        $texte2.= 'Cliquez ici pour accéder à la page des confirmations</a></p>';        
        $texte2.= '<p>Merci et bonne journée<br/><br/>Baseball St-Hyacinthe</p></html>';
        
        $Email2 = new CakeEmail();
        if($terrain['Courriel'] != null && $terrain['Courriel'] != "") {
            $Email2->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to($terrain['Courriel'])
                ->emailFormat('html')
                ->subject('Demande de terrain à confirmer')
                ->send($texte2);
        }
        
        if($terrain['CourrielAdjoint'] != null && $terrain['CourrielAdjoint'] != "" &&
            $terrain['Courriel'] != $terrain['CourrielAdjoint']) {
            $Email2 = new CakeEmail();
            $Email2->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to($terrain['CourrielAdjoint'])
                ->emailFormat('html')
                ->subject('Demande de terrain à confirmer')
                ->send($texte2);
        }
    }
    
    public function emailReponseDemandeur($idEvent) {
        
        $this->loadModel('VueEvenement');
        $event = $this->VueEvenement->findByIdevenement($idEvent);

        $texte = '<html><p>Bonjour,</p>';
        if($event['VueEvenement']['Confirmation'] == 1) {
            $texte.= '<p><b>Votre demande de réservation a été acceptée</b></p>';
            if($event['VueEvenement']['TypeEvenement'] == 1) {
                $texte.= '<p>Vous pouvez maintenant faire la demande de changement à la ligue avec les informations suivantes:</p>';
            }
            $sujet = 'Votre demande de réservation a été acceptée';
            
            
        } else {
            $texte.= '<p>Votre demande de réservation a été refusée</p>';
            $sujet = 'Votre demande de réservation a été refusée';
        }
        $texte.= '<p>Terrain: '.$event['VueEvenement']['NomTerrain'].', '.$event['VueEvenement']['AdresseComplete'].'<br/>';
        $texte.= 'Date: '.$event['VueEvenement']['DateEvenement'].'<br/>';
        $texte.= 'De: '.$event['VueEvenement']['DebutEvenement'].' à '.$event['VueEvenement']['FinEvenement'].'<br/>';
        if(isset($event['VueEvenement']['DescEvenement'])) {
            $texte.= 'Raison: '.$event['VueEvenement']['DescEvenement'];
        }
        $texte.= '</p>';
        
        $texte.= '<p>Explication: '.$event['VueEvenement']['Commentaire'].'</p>';
        
        $texte.= '<p>'.$event['VueEvenement']['Organisme'].'<br/>';
        $texte.= '<a href="mailto:'.$event['VueEvenement']['CourrielTerrain'].'">'.$event['VueEvenement']['CourrielTerrain'].'</a></p></html>';
        
        $this->loadModel('User');
        $user = $this->User->findById($event['VueEvenement']['Demandeur']);
        
        if(!empty($user)) {
            $Email = new CakeEmail();
            $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to($user['User']['username'])
                ->emailFormat('html')
                ->subject($sujet)
                ->send($texte);
        }
    }
    
    public function emailReservationEffacee($event, $source) {
        
        $sujet = 'Une réservation de terrain a été supprimée';

        $texte = '<html><p>Bonjour,</p>';
        $texte.= '<p>La réservation de terrain suivante a été supprimé par un responsable du terrain</p>';
        $texte.= '<p>Terrain: '.$event['NomTerrain'].', '.$event['AdresseComplete'].'<br/>';
        $texte.= 'Date: '.$event['DateEvenement'].'<br/>';
        $texte.= 'De: '.$event['DebutEvenement'].' à '.$event['FinEvenement'].'<br/>';
        if($event['DescEvenement'] != null) {
            $texte.= 'Raison: '.$event['DescEvenement'];
        } else {
            $texte.= 'Raison: Partie';
        }
        $texte.= '<br/></p>';
        
        $texte.= "<p>Si vous avez des questions sur l'annulation de cet événement, vous pouvez communiquer avec le coordonateur des loisirs.</p>";
        
        $dest =($source == 'terrain') ? $event['CourrielDemandeur'] : $event['CourrielTerrain'];
        
        if($dest != null && $dest != "") {
            $Email = new CakeEmail();
            $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to($dest)
                ->emailFormat('html')
                ->subject($sujet)
                ->send($texte);
        }
    }
    
 /***************************************
 * Changements de paramètres
 **************************************/
    
    public function enregistrerParamEdition($id) {
        $this->Session->write('Evenement.id',$id);
        
        $this->loadModel('VueEvenement');
        $event = $this->VueEvenement->findByIdevenement($id);
        
        return $event['VueEvenement'];
    }
    
/***************************************
 * Création de listes
 **************************************/
    
    public function listerHeuresDebut($data) {
        
        $heuresDebut = array();
        $heuresDebut['selected'] = $data['Evenement']['DebutEvenement'];
        for ($h = 8; $h < 21; $h++) {
            foreach($this->minutes15 as $minute) {
                $datetime = $data['Evenement']['DateEvenement'].' '.$h.':'.$minute.':00';
                $reserve = $this->Evenement->find('count',array('conditions' => array(
                    'IdTerrain' => $data['Evenement']['IdTerrain'],
                    '? BETWEEN DebutEvenement AND FinEvenement' => $datetime,
                    'id != ' => $data['Evenement']['id'],
                    'Confirmation <=' => '1',
                    'FinEvenement != ' => $datetime
                )));

                if ($reserve == 0) { 
                    $heuresDebut['liste'][$h.':'.$minute] = $h.'h'.$minute;
                    if($heuresDebut['selected'] == null) {
                        $heuresDebut['selected'] = $h.':'.$minute;
                    }
                } elseif($heuresDebut['selected'] == $h.':'.$minute) {
                    $heuresDebut['selected'] = null;
                }
            }
        }
        return $heuresDebut;
    }
    
    public function listerHeuresFin($data) {
        
        $heureDebut = intval(str_replace(':','',$data['Evenement']['DebutEvenement']));
        
        $listeHeuresFin = array();
        for ($h = 8; $h < 22; $h++) {
            foreach($this->minutes15 as $minute) {
                if($heureDebut < intval($h.$minute)) {
                    $datetime = $data['Evenement']['DateEvenement'].' '.$h.':'.$minute.':00';
                    $reserve = $this->Evenement->find('count',array('conditions' => array(
                        'IdTerrain' => $data['Evenement']['IdTerrain'],
                        '? BETWEEN DebutEvenement AND FinEvenement' => $datetime,
                        'id != ' => $data['Evenement']['id'],
                        'Confirmation <=' => '1',
                        'DebutEvenement != ' => $datetime
                    )));

                    if ($reserve == 0) { 
                        $listeHeuresFin[$h.':'.$minute] = $h.'h'.$minute;
                    }
                    else {
                        return $listeHeuresFin;
                    }
                }
            }
        }
        return $listeHeuresFin;
    }
    
    public function listerTypes() {
        $liste = array(
            '1' => 'Partie enregistrée',
            '2' => 'Pratique',
            '3' => 'Autre',
        );
        return $liste;
    }
    
    public function listerEquipesReserve() {
        $this->loadModel('VueEquipe');
        $rs = $this->VueEquipe->find('list',array(
                            'fields' => array('idEquipe','NomComplet'),
                            'conditions' => array(
                                'Ville' => array('St-Hyacinthe','Richelieu-Yamaska'),
                                'IdCategorie >' => '1'),
                            'order' => 'idCategorie,classe,nomEquipe'
        ));
        
        return $rs;
    }
    
    public function listerParties() {
        $this->loadModel('VuePartie');
        $rs = $this->VuePartie->find('list',array(
                                        'fields' => array('idPartie', 'DropDownPartieDesc'),
                                        'conditions' => array(
                                            'date >=' => date('Y-m-d'),
                                            'IdEquipeReceveur' => $this->Session->read('Equipe.id')),
                                        'order' => array('date','heure')
        ));

        return $rs;
    }
    
    public function listerConfirmation() {
        $select = array();
        $select[0] = 'En attente de confirmation';
        $select[1] = 'Accepter la demande';
        $select[2] = 'Refuser la demande';
        
        return $select;
    }
    
    function ajouter15minutes($heure,$minutes) {
        if($minutes == '00') {
            return $heure.':15:00';
        } elseif($minutes == '15') {
            return $heure.':30:00';
        } elseif($minutes == '30') {
            return $heure.':45:00';
        } elseif($minutes == '45') {
            return ($heure+1).':00:00';
        }
    }
    
    private function initialiserReservation() {
        $params = array();
        $params['idEvenement'] = 0; //Douville
        $params['IdTerrain'] = $this->Session->read('Terrain.id') == null
                                        ? 102
                                        : $this->Session->read('Terrain.id'); //Douville
        $params['DateEvenement'] = $this->Session->read('Evenement.Date') == null 
                                        ? date('Y-m-d') 
                                        : date('Y-m-d', strtotime($this->Session->read('Evenement.Date')));
        $params['DebutEvenement'] = '9:00';
        $params['FinEvenement'] = '10:00';
        $params['TypeEvenement'] = 2;
        
        return $params;
    }
    

}
