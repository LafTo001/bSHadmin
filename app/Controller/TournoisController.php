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
class TournoisController extends AppController {

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
        
        public $paginate = array(
            'limit' => 15,
            'order' => array('VueTournoi.NomTournoi' => 'asc')
        );
        
        public function index() {
            $this->loadModel('VueTournoi');
            $this->paginate = array(
                'limit' => 15,
                'order' => array('VueTournoi.NomTournoi' => 'asc')
            );
            
            if($this->Session->read('Tournoi.Categorie') != null && $this->Session->read('Tournoi.Categorie') != "0") {
                $this->paginate['conditions'] = array('IdCategorie' => intval($this->Session->read('Tournoi.Categorie')));
            }
            
            $tournois = $this->paginate('VueTournoi');
            $this->set(compact('tournois'));
            $this->set('listeCategories',$this->listerCategories());
            
            if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
                $this->set('listeEquipes',$this->listerEquipesEntraineur('index'));
                $this->set('source','');
            }
        }
        
        public function ajouter() {      
            if($this->request->is(array('post','put'))) {
                $this->loadModel('Ligue');
                $this->request->data['Ligue']['Tournoi'] = 1;
                $this->request->data['Ligue']['NomCourtLigue'] = 'Tournoi';
                $this->request->data['Ligue']['DateCreation'] = date('Y-m-d H:i:s');
                $this->Ligue->save($this->request->data);
                
                if($this->Session->read('User.role') == 'entraineur') {
                    $usager = $this->Session->read('User.nomComplet');
                    $this->emailAjoutTournoi($this->request->data,$usager);
                }
                $this->redirect(array('action' => 'index'));
            }
            //charger les éléments à afficher
            $this->loadModel('Categorie');
            $liste = $this->Categorie->find('list',array('fields' => array('id','NomCategorie'),
                                                         'conditions' => array('id >' => 1)
                     ));
            $this->set('categories',$liste);
        }
        
        public function parties($idLigue,$nom=null) {
            $this->loadModel('VueTournoi');
            $tournoi = $this->VueTournoi->findByIdligue($idLigue);
            $this->set('tournoi',$tournoi);
            
            $this->loadModel('VuePartie');
            $parties = $this->VuePartie->find('all',array('conditions' => array(
                                            'IdLigue' => $idLigue,
                                            'Annee' => date('Y')),
                                         'order' => array('Datetime' => 'asc')
            ));
            $this->set('parties',$parties);
        }
        
        public function changerCategorie($cat) {
            $this->Session->write('Tournoi.Categorie',$cat);
            $this->redirect(array('action' => 'index'));
        }
        
        public function changerEquipe($idEquipe) {
            $this->Session->write('Equipe.id', $idEquipe);
            $this->redirect('/tournois/');
        }
        
        public function listerCategories() {
            $this->loadModel('Categorie');
            $rs = $this->Categorie->find('all',array('fields' => array('id','NomCategorie'),
                                                    'conditions' => array('id >' => 1)
                  ));
        
            $liste = array();
            foreach($rs as $cat):
                $liste['/baseball/tournois/changerCategorie/'.$cat['Categorie']['id'].'/'] = $cat['Categorie']['NomCategorie'];
            endforeach;

            return $liste;
        }
        
        public function listerEquipesEntraineur($source) {
            $this->loadModel('VueEntraineur');
            $rs = $this->VueEntraineur->find('all',array('conditions' => array(
                                                    'Idusager' => $this->Auth->User('id'),
                                                    'IdCategorie > ' => '1'
            )));   

            $liste = array();
            foreach($rs as $equipe):
                $liste['changerEquipe/'.$equipe['VueEntraineur']['IdEquipe'].'/'] = $equipe['VueEntraineur']['NomCompletEquipe'];
            endforeach;

            return $liste;
        }
        
        public function emailAjoutTournoi($data,$usager) {

            $texte = '<p>Un nouveau tournoi a été ajouté</p>';
            $texte.= '<p>Usager: '.$usager.'<br/>';
            $texte.= 'Nom du tournoi: '.$data['Ligue']['NomLigue'].'</p></html>';

            $Email = new CakeEmail();
            $Email->from(array('no-reply@baseballsthyacinthe.com' => 'bSHadmin'))
                ->to('tomlafleur25@gmail.com')
                ->emailFormat('html')
                ->subject('Nouveau tournoi enregistré')
                ->send($texte);
        }
}
