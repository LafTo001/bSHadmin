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
class RallyecapController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        //public $helpers = array('PhpExcel'); 
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
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'calendrier', 'evenement'); 
    }

    public function index() {
        
        $this->loadModel('VueEvenement');
        $events = $this->VueEvenement->find('all', array(
                                    'conditions' => array(
                                        'DescEvenement LIKE' => 'Rallye Cap%',
                                        'datetime >' => date('Y-m-d 00:00:00'),
                                        'Confirmation' => '1'),
                                    'order' => array('datetime' => 'asc'),
                                    'limit' => 5
            ));
        
        $this->set(compact('events'));
        
        $this->layout = 'accueil';
    }
    
    public function evaluations($saison=null, $session=null, $nomEquipe=null) {
        
        if($this->request->is('post', 'put')) {
            
            if(isset($this->request->data['Joueur']['nomEquipe'])) {
                $this->redirect(array('action' => 'evaluations', $saison, $session, $this->request->data['Joueur']['nomEquipe']));
            }
            
            if(isset($this->request->data['Evaluation'])) {
                $this->loadModel('Evaluation');
                $eval = $this->Evaluation->findByIdjoueurAndSaisonAndSession($this->request->data['Evaluation']['idJoueur'],
                                                                             $saison, $session);

                $this->request->data['Evaluation']['saison'] = $saison;
                $this->request->data['Evaluation']['session'] = $session;
                $this->request->data['Evaluation']['idCategorie'] = 1;

                $this->Evaluation->id = null;
                if(!empty($eval)) {
                    $this->Evaluation->id = $eval['Evaluation']['id'];
                }
                $this->Evaluation->save($this->request->data);
            }
        }
        
        $this->loadModel('Equipe');
        $equipes = $this->Equipe->find('list', array('fields' => array('nomEquipe', 'nomEquipe'),
                                                     'conditions' => array(
                                                         'idCategorie' => 1,
                                                         'saison' => $saison,
                                                         'idAssociation' => 2),
                                                     'order' => 'classe'
            ));
        
        $this->loadModel('VueEvaluation');
        $joueurs = $this->VueEvaluation->find('all', array(
                                                'conditions' => array(
                                                    'saison' => $saison,
                                                    'nomEquipe' => $nomEquipe),
                                                'order' => array('nomFamille', 'prenom')
            ));
        
        $objectifs = array( 'frapper', 
                            'courrir',
                            'lancer',
                            'attraper',
                            'habiletes',
                            'casquette'
            );
        
        $this->loadModel('Casquette');
        $couleurs = $this->Casquette->find('list', array('fields' => array('id', 'couleur')));
        
        $this->set(compact('equipes', 'nomEquipe', 'joueurs', 'objectifs', 'couleurs'));
    }
    
    public function calendrier($noMois= null, $nomMois=null) {
        
        if($noMois == null) {
            $noMois = date('m');
        }
        if($noMois < 4) {
            $noMois = 4;
        }
        
        $this->set(compact('noMois'));
        
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
            $event = $this->VueEvenement->find('first', array(
                                                    'conditions' => array(
                                                        'DateEvenement' => $jour['D']['date'],
                                                        'DescEvenement LIKE' => 'Rallye Cap%',
                                                        'Confirmation' => '1'),
                                                    'order' => array('IdTerrain' => 'asc')
            ));
            $jours[$cle]['D']['Event'] = $event;
            $jours[$cle]['D']['Reserve'] = (!empty($event)) ? 1 : 0;
        }
        $this->set('jours',$jours);
        
        $this->layout = 'accueil';
    }
    
    public function evenement($id) {
        
        if($this->request->is('Post','put')) {
            $this->loadModel('Evenement');
            $this->Evenement->id = $id;
            $this->Evenement->save($this->request->data);
        }
        
        $this->loadModel('VueEvenement');
        $event = $this->VueEvenement->findByIdevenement($id);
        
        $this->request->data['Evenement'] = $event['VueEvenement'];
        
        $this->layout = 'accueil';
    }


}
