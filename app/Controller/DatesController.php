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
class DatesController extends AppController {

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
        /*$path = func_get_args();

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
        }*/
    }
    
    public function sideCalendar() {
        
        if($this->Session->read('Date.MoisCalendrier') == null) {
            //numéro du mois
            $this->Session->write('Date.MoisCalendrier', date('n'));
        }
        $this->set('mois',$this->Session->read('Date.MoisCalendrier'));

        $this->loadModel('Month');
        $nomMois = $this->Month->findById($this->Session->read('Date.MoisCalendrier'));
        //var_dump($nomMois);
        $this->set('nomMois',$nomMois['Month']['NomMois']);
        
        $this->loadModel('Date');
        $jours = $this->Date->getJoursMois($this->Session->read('Date.MoisCalendrier'));

        if($this->Session->read('User.role') == 'terrain') {
            $this->loadModel('VueEvenement');
            foreach($jours as $cle => $jour) :
                $count = $this->VueEvenement->find('count',array('conditions' => array (
                                    'idTerrain' => $this->Session->read('Terrain.id'),
                                    'DateEvenement' => $jour['D']['date'],
                                    'Confirmation' => array(1,3)
                )));
                $jours[$cle]['Reserve'] = $count;
            endforeach;
        }
        
        elseif($this->Session->read('User.role') == 'arbitre' || $this->Session->read('User.role') == 'admin') {
            $this->loadModel('VuePartie');
            foreach($jours as $cle => $jour) :
                $count = $this->VuePartie->find('count',array('conditions' => array (
                                    'Locale' => 1,
                                    'Date' => $jour['D']['date']
                )));
                $jours[$cle]['Reserve'] = $count;
            endforeach;
        }
        
        elseif($this->Session->read('User.role') == 'entraineur') {
            $this->loadModel('VueEvenement');
            $this->loadModel('VuePartie');
            foreach($jours as $cle => $jour) :
                $count = $this->VueEvenement->find('count',array('conditions' => array (
                                    'idEquipe' => $this->Session->read('Equipe.id'),
                                    'DateEvenement' => $jour['D']['date'],
                                    'Confirmation' => array(1,3)
                )));
                $count += $this->VuePartie->find('count',array('conditions' => 
                                                        array('OR' => array('IdEquipeVisiteur' => $this->Session->read('Equipe.id'),
                                                                            'IdEquipeReceveur' => $this->Session->read('Equipe.id')),
                                                        'Date' => $jour['D']['date']
                )));
                $jours[$cle]['Reserve'] = $count;
            endforeach;
        }
        $this->set('jours',$jours);
        
        $this->layout = 'side_calendar';  
    }
    
    public function creerDates($annee=null)
    {
        if($annee == null) {
            $annee = date('Y');
        }
        
        //effacer les dates de l'année s'il y a lieu
        $this->Date->delete(array('conditions' => array('Annee' => $annee)));
        
        //trouver dernière date de la saison précédente
        $rs = $this->Date->find('first', 
                                array('conditions' => array('Annee' => $annee-1), 
                                      'order' => array('date' => 'desc')
            ));

        //initiation
        $noMois = 0; 
        $jourSemaine = $rs['Date']['JourSemaine'];
        $noSemaine = $rs['Date']['NoSemaine'];
        $idDate = $rs['Date']['id'];

        //$noSemaine = 1;
        //$jourSemaine = 2;

        if($annee % 4 == 0) { //bisextile
            $jourParMois = array(0,31,29,31,30,31,30,31,31,30,31,30,31);
        } else {
            $jourParMois = array(0,31,28,31,30,31,30,31,31,30,31,30,31);
        }

        for ($mois = 1; $mois < 13; $mois++) {
            $noMois++;

            for ($jour = 1; $jour <= $jourParMois[$mois]; $jour++)
            {
                $idDate++;
                $jourSemaine++;
                if($jourSemaine == 8) 
                {
                    $noSemaine++;
                    $jourSemaine = 1;
                }
                
                $this->Date->create();
                $date['Date']['id'] = $idDate;
                $date['Date']['date'] = $annee.'-'.$mois.'-'.$jour;
                $date['Date']['Annee'] = $annee;
                $date['Date']['NoMois'] = $mois;
                $date['Date']['JourMois'] = $jour;
                $date['Date']['JourSemaine'] = $jourSemaine;
                $date['Date']['NoSemaine'] = $noSemaine;
                
                $this->Date->id = $idDate;
                $this->Date->save($date);
            }
        }	
        
        $this->redirect(array('controller' => 'pages', 'action' => 'home'));
    }
        
    public function changerMois($noMois) {
        
        $this->Session->write('Date.MoisCalendrier', $noMois);
        $this->redirect(array('action' => 'sideCalendar'));
    }
    
    
}
