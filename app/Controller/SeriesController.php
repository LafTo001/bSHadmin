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
class SeriesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        public $minutes15 = array('00','15','30','45');

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
    
    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page");
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
    
    public function validerAccesTerrain($idTerrain) {
        if($idTerrain != $this->Session->read('Terrain.id')) {
            $this->Session->setFlash("Vous n'avez pas accès à ce terrain");
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }

    public function index() {
        $this->validerRole('terrain');
        
        $this->loadModel('VueSerie');
        $series = $this->VueSerie->find('all',array('conditions' => array(
                            'IdTerrain' => $this->Session->read('Terrain.id'),
                            'Annee' => date('Y')
        )));
        $this->set('series',$series);
        
        if($this->Session->read('Terrain.Count') > 1) {
            $this->set('lstTerrains',$this->requestAction('/terrain/listerTerrains'));
        }
        
        $this->set('terrain',$this->requestAction('/terrain/getTerrain'));
    }

    /*public function ajouter() {
        $this->validerRole('terrain');
        
        $this->Session->write('Serie.id',null);
        $this->initialiser();
        
        $this->redirect(array('action' => 'formulaire'));
    }*/
    
    public function formulaire($id=null) {
        $this->validerRole('terrain');
        
        $this->loadModel('VueSerie');
        if(isset($this->request->data['Envoyer']) && $this->request->data['Envoyer'] == 'Submit') {
            $this->enregistrer();
        }
        
        if($id != null) {
            $serie = $this->VueSerie->findByIdserie($id);
            $this->set(compact('serie'));
        }
        
        if(!isset($this->request->data['Serie'])) {
            if($id != null) {
                $event = $this->VueSerie->findByIdserie($id);
                $this->request->data['Serie'] = $event['VueSerie'];
                $this->request->data['Serie']['HeureDebut'] = str_replace("h",":",$this->request->data['Serie']['HeureDebut']);
                $this->request->data['Serie']['HeureFin'] = str_replace("h",":",$this->request->data['Serie']['HeureFin']);
            } else {
                $this->request->data['Serie'] = $this->initialiser();
            }
        }
        
        $this->set('lstTerrains', $this->requestAction('terrain/listerTerrains'));
        $this->set('lstHeuresDebut',$this->listerHeuresDebut($id));
        $this->set('lstHeuresFin',$this->listerHeuresFin($id));

        $this->loadModel('VueDate');
        $date = $this->VueDate->findByDate($this->request->data['Serie']['DateDebut']);
        $this->set(compact('date'));
        
        $lstFrequence = array( $date['VueDate']['JourSemaine'] => 'Tous les '.$date['VueDate']['NomJour'].'s', 
                                '0' => 'Quotidien');
        $this->set(compact('lstFrequence'));
        
        $this->loadModel('VueEvenement');
        $events = $this->VueEvenement->find('all',array('conditions' => array(
                    'DateEvenement' => $date['VueDate']['date'],
                    'IdTerrain' => $this->request->data['Serie']['IdTerrain'],
                    'Confirmation' => '1'),
                'order' => 'DateEvenement'
        ));
        $this->set('events',$events);

        $this->Session->write('Terrain.id', $this->request->data['Serie']['IdTerrain']);
        $this->set('terrain',$this->requestAction('/terrain/getTerrain'));
    }
    
    public function enregistrer() {
        $this->validerRole('terrain');
        
        $this->loadModel('Serie');
        $dateDebut = $this->request->data['Serie']['DateDebut'];
        $dateFin = $this->request->data['Serie']['DateFin'];
        $this->request->data['Serie']['DebutSerie'] = $dateDebut.' '.$this->request->data['Serie']['HeureDebut'].':00';
        $this->request->data['Serie']['FinSerie'] = $dateFin.' '.$this->request->data['Serie']['HeureFin'].':00';
        
        $this->request->data['Serie']['Confirmation'] = 1;
        $this->request->data['Serie']['TypeEvenement'] = 3;
        $this->request->data['Serie']['Demandeur'] = $this->Auth->User('id');
        $this->request->data['Serie']['DateCreation'] = date('Y-m-d H:i:s');
        $this->request->data['Serie']['DerniereModif'] = date('Y-m-d H:i:s');
        
        $id = $this->request->data['Serie']['idSerie'];
        if($id != null) {
            $this->Serie->id = $id;
        }
        $this->Serie->save($this->request->data);
        $id = $this->Serie->id;
        
        $this->creerEvenements($id);
        
        $this->redirect(array('controller' => 'evenements', 'action' => 'jour', str_replace("/", "-",$dateDebut)));
    }
    
    public function supprimer($id,$confirm=0) {
        $this->validerRole('terrain');
        
        $this->loadModel('VueSerie');
        $serie = $this->VueSerie->findByIdserie($id);
        $this->validerAccesTerrain($serie['VueSerie']['IdTerrain']);
        
        if($confirm == 1) {
            $this->loadModel('Evenement');
            //effacer les événements de la série
            $this->Evenement->deleteAll(array('IdSerie' => $id), false);
            
            $this->loadModel('Serie');
            $this->Serie->id = $id;
            $this->Serie->delete();

            $this->redirect(array('action' => 'index'));
            
        } else {
            $this->set('serie',$serie);
            $this->set('id',$id);
        }
    }
    
    protected function creerEvenements($id) {
        $this->validerRole('terrain');
        
        $this->loadModel('VueSerie');
        $serie = $this->VueSerie->findByIdserie($id);

        //trouver toutes les dates selon le jour de la semaine
        $this->loadModel('Date');
        if($serie['VueSerie']['JourSemaine'] == 0) {
            $dates = $this->Date->find('all',array('fields' => 'date',
                            'conditions' => array('date BETWEEN ? AND ?' => array($serie['VueSerie']['DateDebut'], $serie['VueSerie']['DateFin'])
                )));
        } else {
            $dates = $this->Date->find('all',array('fields' => 'date',
                            'conditions' => array(
                                'JourSemaine' => $serie['VueSerie']['JourSemaine'],
                                'date BETWEEN ? AND ?' => array($serie['VueSerie']['DateDebut'], $serie['VueSerie']['DateFin'])
                )));
        }
        
        $this->loadModel('Evenement');
        //effacer les événements de la série
        $this->Evenement->deleteAll(array('IdSerie' => $id), false);
        
        foreach($dates as $date) {
            $this->Evenement->create();
            $event = array();
            $event['Evenement']['DebutEvenement'] = $date['Date']['date'].' '.str_replace("h",":",$serie['VueSerie']['HeureDebut']).':00';
            $event['Evenement']['FinEvenement'] = $date['Date']['date'].' '.str_replace("h",":",$serie['VueSerie']['HeureFin']).':00';
            $event['Evenement']['IdTerrain'] = $serie['VueSerie']['IdTerrain'];
            $event['Evenement']['TypeEvenement'] = $serie['VueSerie']['TypeEvenement'];
            $event['Evenement']['DescEvenement'] = $serie['VueSerie']['DescriptionSerie'];
            $event['Evenement']['Confirmation'] = 1;
            $event['Evenement']['IdSerie'] = $id;
            $event['Evenement']['Demandeur'] = $this->Auth->User('id');
            $event['Evenement']['DateCreation'] = date('Y-m-d H:i:s');
            $event['Evenement']['DerniereModif'] = date('Y-m-d H:i:s');
            //var_dump($event);
            $this->Evenement->save($event);
        }
    }

/***************************************
 * Création de listes
 **************************************/
    
    public function listerHeuresDebut() {
        $listeHeuresDebut = array();
        for ($h = 9; $h < 21; $h++) {
            foreach($this->minutes15 as $minute) {
                $listeHeuresDebut[$h.':'.$minute] = $h.'h'.$minute;
            }
        }
        return $listeHeuresDebut;
    }
    
    public function listerHeuresFin() {
        $listeHeuresFin = array();
        for ($h = 10; $h < 22; $h++) {
            foreach($this->minutes15 as $minute) {
                $listeHeuresFin[$h.':'.$minute] = $h.'h'.$minute;
            }
        }
        return $listeHeuresFin;
    }
    
    private function initialiser() {
        $params = array();
        $params['idSerie'] = 0; 
        $date = new DateTime(date('Y-m-d'));
        $params['DateDebut'] = $date->format('Y-m-d');
        $params['DateFin'] = $date->add(new DateInterval('P7D'))->format('Y-m-d');
        $params['HeureDebut'] = '9:00';
        $params['HeureFin'] = '10:00';
        
        $this->loadModel('Date');
        $date = $this->Date->findByDate($params['DateDebut']);
        $params['JourSemaine'] = $date['Date']['JourSemaine'];
        
        return $params;
    }

}
