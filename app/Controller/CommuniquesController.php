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
class CommuniquesController extends AppController {

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
        
        public function index() {
            $idEquipe = ($this->Session->read('User.role') == 'entraineur') ? $idEquipe = $this->Session->read('Equipe.id') : 0;
            $this->loadModel('VueCommunique');
            $this->paginate = array(
                'limit' => 15,
                'order' => array('DateCreation' => 'desc'),
                'conditions' => array('IdEquipe' => $idEquipe,
                                      'DateSuppression' => null));
            $communiques = $this->paginate('VueCommunique');
            $this->set(compact('communiques'));
            
            if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
                $this->set('listeEquipes',$this->listerEquipesEntraineur('index'));
                $this->set('source','');
            }
        }
        
        public function ajouter() {
            if($this->request->is('post')) {
                $this->request->data['Communique']['IdUsager'] = $this->Auth->User('id');
                $this->request->data['Communique']['DateCreation'] = date('Y-m-d H:i:s');
                $this->request->data['Communique']['ModifParUsager'] = $this->Auth->User('id');
                $this->request->data['Communique']['DerniereModif'] = date('Y-m-d H:i:s');
                $this->request->data['Communique']['Active'] = 1;
                $this->request->data['Communique']['IdEquipe'] = 
                    ($this->Session->read('User.role') == 'entraineur') ? $idEquipe = $this->Session->read('Equipe.id') : 0;
                //var_dump($this->request->data);
                if($this->Communique->save($this->request->data)) {
                    $this->Session->setFlash(__("Le nouveau communiqué a été créé avec succès"));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__("Le nouveau communiqué n'a pas été créé"));
                }
            }
        }
        
        public function editer($id) {
            if($this->request->is('post')) {
                $this->request->data['DerniereModif'] = date('Y-m-d H:i:s');
                $this->request->data['ModifParUsager'] = $this->Auth->User('id');
                $this->Communique->id = $id;
                $this->Communique->save($this->request->data);
                $this->redirect(array('action' => 'index'));
            } else {
                $comm = $this->Communique->findById($id);
                $this->request->data = $comm;
                $this->set('id',$id);
            }
        }
        
        public function supprimer($id,$confirm=0) {
            $rs = $this->Communique->findById($id);
            
            if($rs['Communique']['IdEquipe'] > 0 && 
                $rs['Communique']['IdEquipe'] != $this->Session->read('Equipe.id')) {
                $this->Session->setFlash(__("Vous n'avez pas accès à cette page"));
                $this->redirect(array('controller' => 'pages', 'action' => 'home'));
            }
            
            if($confirm == 1) {
                $this->Communique->id = $id;
                $this->Communique->saveField('DateSuppression',date('Y-m-d H:i:s'));
                $this->Session->setFlash(__("Le communiqué a été supprimé de la liste"));
                $this->redirect(array('action' => 'index'));
            }
            $this->set('id',$id);
            $rs = $this->Communique->findById($id);
            $this->set('titre',$rs['Communique']['Titre']);
        }
        
        public function changerEquipe($idEquipe) {
            $this->Session->write('Equipe.id', $idEquipe);
            $this->redirect('/communiques/');
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
}
