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
class UsersController extends AppController {

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
        $this->Auth->allow('login','perduMotDePasse','demande','existe','add','creerUsager'); 
    }
     
    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'default', array(), 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
 
    public function login() {
         
        //if already logged-in, redirect
        if($this->Session->check('Auth.User')){
            $this->redirect(array('action' => 'index'));      
        }
        
        // if we get the post information, try to authenticate
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->User->id = $this->Auth->User('id');
                $this->User->saveField('DerniereConnexion',date('Y-m-d H:i:s'));
                
                $this->setRole();
                $this->redirect(array('controller' => 'parties', 'action' => 'extrairePartiesLBAVR',1));
                //$this->redirect(array('controller' => 'parties', 'action' => 'extrairePartiesLBAVR',2));
                $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Session->setFlash("Nom d'usager ou mot de passe invalide", 'default', array(), 'bad');
            }
        }
        
        $this->layout = 'accueil';
    }
 
    public function logout() {
        
        $this->Session->write('User.role',NULL);
        $this->redirect($this->Auth->logout());
    }
 
    public function index() {
        $this->validerRole('admin');
        
        if($this->request->is('Post', 'put')) {
            $user = $this->User->findByUsernameOrNomcomplet($this->request->data['User']['username'], $this->request->data['User']['username']);
            if(!empty($user)) {
                $this->redirect(array('action' => 'edit', $user['User']['id']));
            }
        }
        
        $this->paginate = array(
            'conditions' => array('Suppression' => null,
                                  'IFNULL(username,"") !=' => ''),
            'limit' => 18,
            'order' => array('User.username' => 'asc' )
        );
        $users = $this->paginate('User');
        $this->set(compact('users'));
    }
 
 
    public function add() {
        
        $this->validerRole('admin');
        //$this->User->create();
        
        if ($this->request->is('post')) {      
            //ajouter le role de départ
            //var_dump($this->request->data);
            if($this->request->data['User']['Admin'] == 1) {
                $this->request->data['User']['Role'] = 'admin';
            } elseif($this->request->data['User']['Entraineur'] == 1) {
                $this->request->data['User']['Role'] = 'entraineur';
            } elseif($this->request->data['User']['Terrain'] == 1) {
                $this->request->data['User']['Role'] = 'terrain';
            } elseif($this->request->data['User']['Arbitre'] == 1) {
                $this->request->data['User']['Role'] = 'arbitre';
            } elseif($this->request->data['User']['Parent'] == 1) {
                $this->request->data['User']['Role'] = 'parent';
            }
            
            $err = $this->creerUsager($this->Session->read('NouveauUser.idParent'),$this->request->data);
            
            if($err == 0) {
                $this->Session->setFlash("L'usager a été créé", 'default', array(), 'good');
                $this->redirect(array('controller' => 'users', 'action' => 'index'));
            }
        }

        //si besoin d'afficher la page d'ajout de user
        if($this->Session->read('NouveauUser.idParent') > 0) {
            $this->loadModel('VueParent');
            $parent = $this->VueParent->findByIdparent($this->Session->read('NouveauUser.idParent'));
            $this->set(compact('parent'));
        }

        $this->set('liste',$this->listerParents());
    }

/**
 * Modifer les accès d'un usager
 * 
 * @param type $id : id de l'usager
 */
    public function edit($id = null) {
        if ($this->request->is(array('post','put'))) {
            $this->request->data['User']['DerniereModif'] = date('Y-m-d H:i:s');
            if($this->request->data['User'][ucfirst($this->request->data['User']['Role'])] == 0) {
                if($this->request->data['User']['Admin'] == 1) $this->request->data['User']['Role'] = 'admin';
                elseif($this->request->data['User']['Entraineur'] == 1) $this->request->data['User']['Role'] = 'entraineur';
                elseif($this->request->data['User']['Terrain'] == 1) $this->request->data['User']['Role'] = 'terrain';
                elseif($this->request->data['User']['Arbitre'] == 1) $this->request->data['User']['Role'] = 'arbitre';
                elseif($this->request->data['User']['Parent'] == 1) $this->request->data['User']['Role'] = 'parent';
                else {
                    $this->request->data['User']['Role'] = 'parent';
                    $this->request->data['User']['DateSuppression'] = date('Y-m-d H:i:s');
                }
            }
            $this->User->id = $id;
            if($this->User->save($this->request->data)) {
                $this->Session->setFlash(__("L'usager a été modifié"));
            } else {
                $this->Session->setFlash(__("Impossible de modifier cet usager"));
            } 
            $this->redirect(array('action' => 'index'));
        } 
            
        $this->request->data = $this->User->findById($id);
    }
    
 /**
 * Endroit où l'usager peut changer ses informations de profil
 * @param $type : password ou infos
 */   
    public function profil($type=null) {
        $this->set('type',$type);
        $this->loadModel('Adulte');
        //$this->loadModel('User');
         
        $user = $this->User->findById($this->Auth->User('id'));
         
        if($type == 'infos' && $this->request->is('post')) {
             //charger les modifications de l'usager
            $this->request->data['User']['CodePostal'] = str_replace(" ","",$this->request->data['User']['CodePostal']);
            $this->request->data['User']['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['User']['TelMaison']);
            $this->request->data['User']['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['User']['TelMobile']);
            $data['Adulte'] = $this->request->data['User'];
            $this->Adulte->create($data['Adulte']);
            $this->Adulte->id = $this->Session->read('User.idParent');
            if($this->Adulte->save($this->request->data)) {

                //ajouter le username selon le Courriel #1
                $data['User'] = array('username' => $this->request->data['User']['Courriel1'],
                                      'DerniereModif' => date('Y-m-d h:i:s'));
                $this->User->id = $this->Auth->User('id');
                if($this->User->save($data['User'])) {
                   $this->Session->setFlash(__("Vos informations ont été modifiées avec succès"));
                } else {
                    $this->Session->setFlash(__("Impossible de modifier votre nom d'usager"));
                }
            } else {
                 $this->Session->setFlash(__("Impossible de modifier vos informations"));
            }
             
            $this->redirect(array('action' => 'profil'));
         
        } elseif($type == 'password' && $this->request->is('post')) {
             //var_dump($this->request->data);
             if($this->request->data['User']['password'] != $this->request->data['User']['confirmPassword']) {
                 $this->Session->setFlash("Impossible de modifer le mot de passe", 'bad');
                 
             } else {
                 $this->User->id = $this->Auth->User('id');
                 $this->User->saveField('password',$this->request->data['User']['password']);
                 $this->Session->setFlash("Le mot de passe a été modifié avec succès", 'good');
             }
             $this->redirect(array('action' => 'profil'));
         
        } elseif($type == 'infos' && !$this->request->is('post')) {
            $parent = $this->Adulte->findById($user['User']['IdParent']);
            $this->set('data',$parent['Adulte']);
        }
    }
    
    public function perduMotDePasse() {
        if($this->request->is(array('Post','Put'))) {
            $user = $this->User->findByUsername($this->request->data['User']['Courriel']);
            if(!empty($user)) {
                $password = $this->generatePassword();
                $this->User->id = $user['U']['id'];
                $this->User->saveField('password',$password);
                $this->emailNouveauPassword($user,$password);
                
                $this->Session->setFlash("Votre nouveau mot de passe vous a été envoyé", 'default', array(), 'good');
                
                $this->redirect(array('action' => 'login'));
            }
            else {
                $this->Session->setFlash("Cette adresse n'est pas enregistré", 'default', array(), 'bad');
            }
        }
        
        $this->layout = 'accueil';
    }
    
    public function demande() {
        if($this->request->is('post','put')) {
            $parent = $this->User->findByCourriel($this->request->data['User']['Courriel'],
                                                  $this->request->data['User']['Prenom'],
                                                  $this->request->data['User']['NomFamille']);
            //si le parent existe mais pas le user
            //var_dump($parent);
            if(!empty($parent) && $parent[0]['UsagerExiste'] == 0) {
                $this->User->create();
                $this->request->data['User']['username'] = $this->request->data['User']['Courriel'];
                $this->request->data['User']['IdParent'] = $parent['P']['IdParent'];
                $this->request->data['User']['NomComplet'] = $this->request->data['User']['Prenom'].' '.$this->request->data['User']['NomFamille'];
                $this->request->data['User']['Role'] = 'parent';
                /*$this->request->data['User']['Admin'] = 0;
                $this->request->data['User']['Entraineur'] = 0;
                $this->request->data['User']['Terrain'] = 0;
                $this->request->data['User']['Arbitre'] = 0;*/
                
                if($parent[0]['Entraineur'] == 1) {
                    $this->request->data['User']['Entraineur'] = 1;
                    $this->request->data['User']['Role'] = 'entraineur';
                }
            
                $this->request->data['User']['password'] = $this->generatePassword();
                $this->request->data['User']['DateCreation'] = date('Y-m-d H:i:s');
                $this->request->data['User']['DerniereModif'] = date('Y-m-d H:i:s');
                //var_dump($this->request->data);
                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__('Votre compte a été créé, vous recevrez un message électronique dans les prochaines secondes'));
                    $this->emailNouvelUsager($this->request->data['User']);
                    $this->redirect(array('action' => 'login'));

                } else {
                    $this->Session->setFlash(__('The user could not be created. Please, try again.'));
                }
            }
        }
    }
     
    public function activate($id = null) {
         
        if (!$id) {
            $this->Session->setFlash('Please provide a user id');
            $this->redirect(array('action'=>'index'));
        }
         
        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid user id provided');
            $this->redirect(array('action'=>'index'));
        }
        if ($this->User->saveField('status', 1)) {
            $this->Session->setFlash(__('User re-activated'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not re-activated'));
        $this->redirect(array('action' => 'index'));
    }
    
    public function generatePassword ($length = 10){ 
        // inicializa variables 
        $password = ""; 
        $i = 0; 
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";  
         
        // agrega random 
        while ($i < $length){ 
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1); 
             
            if (!strstr($password, $char)) {  
                $password .= $char; 
                $i++; 
            } 
        } 
        return $password;
    } 
    
    public function setRole() { 
        $user = $this->User->findById($this->Auth->User('id'));
        //var_dump($user);
        $this->Session->write('User.idParent',$user['User']['IdParent']);
        $this->Session->write('User.nomComplet',$user['User']['NomComplet']);
        $this->Session->write('User.courriel',$user['User']['username']);
        $this->Session->write('User.role',$user['User']['Role']);
        $this->Session->write('User.nomRole',$this->getNomRole($user['User']['Role']));
        
        
        if($user['User']['Terrain'] == 1) {
            $this->loadModel('Terrain');
            $terrain = $this->Terrain->findByIdusager($this->Auth->User('id'));
            $this->Session->write('Terrain.id',$terrain['Terrain']['id']);
            
            //compter le nombre de terrain de l'usager
            $count = $this->Terrain->find('count',array(
                'conditions' => array('idUsager' => $this->Auth->User('id'))
            ));
            $this->Session->write('Terrain.Count',$count);
            
            //compter le nombre de confirmation en attente
            $this->loadModel('VueEvenement');
            $nbConfirm = $this->VueEvenement->find('count',array('conditions' => array(
                                        'IdUsagerTerrain' => $this->Auth->User('id'),
                                        'Confirmation' => '0')
            ));
            $this->Session->write('Terrain.nbConfirm',$nbConfirm);
        }  
        elseif($user['User']['Entraineur'] == 1) {
            $this->loadModel('VueEntraineur');
            //trouver la première équipe de l'entraineur
            $equipe = $this->VueEntraineur->find('first',array('conditions' => array(
                                        'IdParent' => $user['User']['IdParent'],
                                        'IdCategorie > ' => '1'
            )));
            //trouver le nom d'équipe
            if(!empty($equipe)) {
                $this->Session->write('Equipe.id',$equipe['VueEntraineur']['IdEquipe']);
            }
            $count = $this->VueEntraineur->find('count',array('conditions' => array(
                                        'IdParent' => $user['User']['IdParent'],
                                        'IdCategorie > ' => '0'
            )));
            $this->Session->write('Equipe.Count',$count);
        }
        
        if($user['User']['Admin'] == 1 && $user['User']['Role'] != 'admin') {
            $this->Session->write('User.role2','admin');
            $this->Session->write('User.nomRole2',$this->getNomRole('admin'));
        }
        elseif($user['User']['Entraineur'] == 1 && $this->Session->read('Equipe.Count') > 0 
                && $user['User']['Role'] != 'entraineur') {
            $this->Session->write('User.role2','entraineur');
            $this->Session->write('User.nomRole2',$this->getNomRole('entraineur'));
        }
        elseif($user['User']['Terrain'] == 1 && $user['User']['Role'] != 'terrain') {
            $this->Session->write('User.role2','terrain');
            $this->Session->write('User.nomRole2',$this->getNomRole('terrain'));
        }
        elseif($user['User']['Arbitre'] == 1 && $user['User']['Role'] != 'arbitre') {
            $this->Session->write('User.role2','arbitre');
            $this->Session->write('User.nomRole2',$this->getNomRole('arbitre'));
        }
        elseif($user['User']['Parent'] == 1 && $user['User']['Role'] != 'parent') {
            $this->Session->write('User.role2','parent');
            $this->Session->write('User.nomRole2',$this->getNomRole('parent'));
        }
    }
    
    public function existe() {
        $user = $this->User->findByUsername($this->Session->read('NouveauUser.courriel'));
        if(!empty($user)) {
            if($this->Session->read('NouveauUser.idParent') == $user['U']['IdParent']) {
                //un usager est déjà créé pour ce parent
                $type = 1;
            }
            else {
                //le courriel est associé à un autre parent
                $type = 2;
            }
        } else {
            //le parent existe mais n'a pas de compte
            $type = 3;
        }
        $this->set(compact('type'));
        $this->set('idParent',$user['U']['IdParent']);
        $this->layout = 'accueil';
    }
    
    public function creerUsager($idParent=null,$data=null) {
        if($idParent == null) {
            $idParent = $this->Session->read('NouveauUser.idParent');
            $this->Session->write('NouveauUser.idParent',null);
        }
        $this->loadModel('VueParent');
        $rs = $this->VueParent->findByIdparent($idParent);
        
        if(!empty($rs)) {
            //vérifier si le courriel est déjà attitré à un autre user
            $existe = $this->User->findByUsername($rs['VueParent']['Courriel1']);
            if(empty($existe)) {

                $data['User']['username'] = $rs['VueParent']['Courriel1'];
                $data['User']['NomComplet'] = $rs['VueParent']['nomComplet'];
                $data['User']['IdParent'] = $idParent;
                $data['User']['password'] = $this->generatePassword();
                $data['User']['DateCreation'] = date('Y-m-d H:i:s');
                $data['User']['DerniereModif'] = date('Y-m-d H:i:s');
                if(!isset($data['User']['Role'])) {
                    $data['User']['Role'] = 'parent';
                }
                //var_dump($data);
                if ($this->User->save($data)) {
                    $this->emailNouvelUsager($data['User']);
                    return 0;
                }
            }
        }
        $this->Session->setFlash("Impossible de créer l'accès à l'usager", 'bad');
    }
    
    public function changerRole($role) {
        //Vérifer les accès de l'usager
        $user = $this->User->findById($this->Auth->User('id'));
        if($user['User'][ucfirst($role)] == 1) {
            if($this->Session->read('User.role2') == $role) {
                $this->Session->write('User.role2',$this->Session->read('User.role'));
                $this->Session->write('User.nomRole2',$this->Session->read('User.nomRole'));
            }
            $this->Session->write('User.role',$role);
            $this->Session->write('User.nomRole',$this->getNomRole($role));

            $this->User->id = $this->Auth->User('id');
            $this->User->saveField('Role', $role);
        }
        $this->redirect(array('controller' => 'pages', 'action' => 'home'));
    }
    
    public function getNomRole($role) {
        switch ($role) {
            case 'admin':
                return 'Adminstrateur';
                break;
            case 'entraineur':
                return 'Entraineur';
                break;
            case 'terrain':
                return 'Resp. Terrain';
                break;
            case 'arbitre':
                return 'Arbitre';
                break;
            case 'parent':
                return 'Parent';
                break;
        }
    }
    
    function listerParents() {
        $this->loadModel('VueParent');
        $liste = $this->VueParent->find('list',array(
                                    'fields' => array('idParent','nomPrenom'),
                                    'order' => array(
                                        'NomFamille' => 'asc', 
                                        'Prenom' => 'asc')
        ));

        return $liste;
    }
    
    public function selectParent() {
        //var_dump($this->request->data);
        $this->Session->write('NouveauUser.idParent',$this->request->data['User']['idParent']);
        $this->redirect(array('action' => 'add'));
    }
    
    public function emailNouvelUsager($coordonnees) {

        $texte = '<html><p>Bonjour,</p>';
        $texte.= '<p>voici vos coordonnées pour accéder à la plateforme bSHadmin</p>';
        $texte.= '<p>Nom d\'usager: '.$coordonnees['username'].'<br/>';
        $texte.= 'Mot de passe: '.$coordonnees['password'].'</p>';
        $texte.= '<p><a href="http://www.baseballsthyacinthe.com/bshadmin/">Cliquez ici pour accéder à la plateforme</a></p>';
        $texte.= '<p>Pour toutes questions, vous pouvez communiquer avec le webmaster<br/>';
        $texte.= 'Tommy Lafleur<br/><a href="mailto:tomlafleur25@gmail.com">tomlafleur25@gmail.com</a></p></html>';
        //echo $texte;
        $Email = new CakeEmail();
        $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
            ->to($coordonnees['username'])
            ->emailFormat('html')
            ->subject('Voici vos accès à bSHadmin')
            ->send($texte);
    }
    
    public function emailNouveauPassword($user,$password) {

        $texte = '<html><p>Bonjour,</p>';
        $texte.= '<p>voici votre nouveau mot de passe pour vous connecter à bSHadmin</p>';
        $texte.= '<p>'.$password.'</p>';
        $texte.= '<p><a href="http://www.baseballsthyacinthe.com/bshadmin/">Cliquez ici pour accéder à la plateforme</a></p>';
        $texte.= '<p>Pour toutes questions, vous pouvez communiquer avec le webmaster<br/>';
        $texte.= 'Tommy Lafleur<br/><a href="mailto:tomlafleur25@gmail.com">tomlafleur25@gmail.com</a></p></html>';
        //echo $texte;
        $Email = new CakeEmail();
        $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
            ->to($user['U']['username'])
            ->emailFormat('html')
            ->subject('Nouveau mot de passe')
            ->send($texte);
    }
    
    public function envoyerEmailsParentPrincipal($type=null) {
        if($this->Session->read('User.role') == 'admin') {
        
            $parents = $this->User->findParentsPrincipaux();
            $this->set(compact('parents'));

            /*foreach($parents as $parent) {         
                $existe = $this->User->findByUsername($parent['p']['Courriel1']);
                if(empty($existe)) {
                        
                    $data['User']['username'] = $parent['p']['Courriel1'];
                    $data['User']['NomComplet'] = $parent['p']['Prenom'].' '.$parent['p']['NomFamille'];
                    $data['User']['IdParent'] = $parent['p']['id'];
                    $data['User']['password'] = $this->generatePassword();
                    $data['User']['DateCreation'] = date('Y-m-d H:i:s');
                    $data['User']['DerniereModif'] = date('Y-m-d H:i:s');
                    $data['User']['Role'] = 'parent';

                    //var_dump($data);
                    //if($data['User']['username'] == 'me.gagne11@gmail.com') {
                        $this->User->id = null;
                        if ($this->User->save($data)) {
                            $this->emailNouvelUsager($data['User']);
                        }
                    //}
                }
            }*/
        }
        
        //$this->redirect(array('action' => 'index'));
    }
    
    
}