<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
    'Session',
    'Auth' => array(
        'loginRedirect' => array('controller' => 'pages', 'action' => 'home'),
        'logoutRedirect' => array('controller' => 'accueil', 'action' => 'index'),
        'authError' => '<p>Vous devez être connecté pour voir cette page.</p>',
        'loginError' => "<p>Nom d'usager ou mot de passe invalide, svp veuillez réessayer.</p>"
 
    ));
 
    // only allow the login controllers only
    public function beforeFilter() {
        $this->Auth->allow('login');
    }

    public function isAuthorized($user) {
        // Here is where we should verify the role and give access based on role

        return true;
    }
    
    protected function getConfigValueByName($nomConfig) {
        $this->loadModel('Config');
        $annee = $this->Config->findByNomconfig($nomConfig);
        return $annee['Config']['Valeur'];
    }
    
    function verifierLienParentJoueur($idJoueur) {
        if($this->Auth->User('Admin') == 1) {
            return true;
        }
        elseif($this->Session->read('User.idParent') == null) {
            return false;
        }
        $this->loadModel('Famille');
        $rs = $this->Famille->findByIdjoueurAndIdparent($idJoueur, $this->Session->read('User.idParent'));
        return !empty($rs);
    }
    
    protected function setMois($noMois=null) {
        if($noMois != null) {
            $this->Session->write('Date.MoisCalendrier',$noMois);
        } elseif($this->Session->read('Date.MoisCalendrier') > 0) {
            $noMois = $this->Session->read('Date.MoisCalendrier');
        } else {
            $noMois = date('m');
            $this->Session->write('Date.MoisCalendrier',$noMois);
        }
        return $noMois;
    }
    
    protected function listerEquipesEntraineur() {
        $this->loadModel('VueEntraineur');
        $rs = $this->VueEntraineur->find('list',array(
                                            'fields' => array('IdEquipe','NomCompletEquipe'),
                                            'conditions' => array(
                                                'Idusager' => $this->Auth->User('id'),
                                                'IdCategorie > ' => '1'
            )));   

        return $rs;
    }
    
}