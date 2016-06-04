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
class TerrainController extends AppController {

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

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('liste');
    }

    public function index() {
        $terrain = $this->Terrain->findById($this->Session->read('Terrain.id'));
        $this->set('terrain',$terrain);

        if($this->Session->read('Terrain.Count') > 1) {
            $this->set('lstTerrainsMenu',$this->listerTerrains());
            $this->set('source','');
        }
    }

    public function liste() {
        $terrains = $this->Terrain->findAllByIdassociation(2);
        $this->set(compact('terrains'));

        $this->layout = 'accueil';
    }

    public function editer() {
        if($this->request->is(array('post','put'))) {
            $this->Terrain->id = $this->Session->read('Terrain.id');
            $this->Terrain->save($this->request->data);

            $this->redirect('/terrain/');
        } else {
            $terrain = $this->Terrain->findById($this->Session->read('Terrain.id'));
            $this->request->data = $terrain;      
        }
    }

    public function mesures() {

    }

    public function changerTerrain() {
        if($this->request->is('post','put')) {
            $this->Session->write('Terrain.id', $this->request->data['Terrain']['idTerrain']);
            $this->redirect(str_replace("/".basename(dirname(APP)), "", $this->request->data['Terrain']['url']));
        } else {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
    
    public function getTerrain() {
        if($this->Session->read('Terrain.id') == null) {
            $this->Session->write('Terrain.id', 102);
        }
        $terrain = $this->Terrain->findById($this->Session->read('Terrain.id'));
        return $terrain['Terrain'];
    }
    
    public function setTerrain($idTerrain) {
        $this->Session->write('Terrain.id', $idTerrain);
    }

    public function listerTerrains() {

        if($this->Session->read('User.role') == 'terrain') {
            $liste = $this->Terrain->find('list',array(
                                            'fields' => array('id', 'NomTerrain'),
                                            'conditions' => array('IdUsager' => $this->Auth->User('id')), 
                                            'order' => array('Terrain.NomTerrain' => 'asc')
            ));
        } else {
            $liste = $this->Terrain->find('list',array(
                                            'fields' => array('id', 'NomTerrain'),
                                            'conditions' => array('IdAssociation' => 2),
                                            'order' => array('Terrain.NomTerrain' => 'asc')
            ));
        }

        return $liste;
    }

    public function listerTerrainsUsager() {
        $rs = $this->Terrain->findAllByIdusager($this->Auth->User('id'));        

        $liste = array();
        foreach($rs as $t):
            $liste['changerTerrain/'.$t['Terrain']['id'].'/'] = $t['Terrain']['NomTerrain'];
        endforeach;

        return $liste;
    }
}
