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
App::import('Controller','Users');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ParentsController extends AppController {

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
        $this->Auth->allow('inscriptionEnLigne'); 
    }
     
/**
 * Rechercher un parent
 *
 * @return void
 *
 */
    public function rechercher($idJoueur = 0) {
        if(!empty($this->request->data)) {
            $this->loadModel('Adulte');
            $parent = $this->Adulte->rechercherParent($this->request->data['Adulte']);
            //echo count($parent);
            //si ne trouve pas exactement un parent
            if(count($parent) == 0) {
                $this->loadModel('Adulte');
                $parent = $this->Adulte->find('all',
                    array('conditions' => array('Adulte.NomFamille' => $this->request->data['Adulte']['NomFamille'],
                                               'Adulte.Prenom' => $this->request->data['Adulte']['Prenom'])));
                //Si trouve au moins un parent ce nom
                //echo count($parent);
                if(count($parent) > 0) {
                    $this->set('parents',$parent);
                    $this->set('idJoueur',$idJoueur);
                } else {
                    if($idJoueur > 0) {
                        //si ajout de parent à un joueur
                        $this->ajouter('joueur',$idJoueur);
                    } else {
                        //si nouveau parent
                        $this->ajouter('inscription',null);
                    }
                }
            }
            //le parent existe déjà
            else {
                if($idJoueur > 0) {
                    $this->lierParentJoueur($parent[0]['Adulte']['id'],$idJoueur);
                } else {
                    if($this->Auth->User('id') > 0) {
                        $this->redirect(array('action' => 'fiche',$parent[0]['Adulte']['id']));
                    } else {
                        //demande d'inscription en ligne mais le parent existe déjà
                        
                        // vérifier si le courriel correspond à un compte déjà existant
                        $this->Session->write('NouveauUser.idParent',$parent[0]['Adulte']['id']);
                        $this->Session->write('NouveauUser.courriel',$this->request->data['Adulte']['Courriel1']);
                        $this->redirect(array('controller' => 'users', 'action' => 'existe'));
                    }
                    
                }
            }
            
        }
        /*else {
            $this->set('idJoueur',$idJoueur);
        }*/
    }
    
    public function select($idJoueur) {
        $this->loadModel('Adulte');
        $parent = $this->Adulte->find('all',
            array('conditions' => array('Adulte.NomFamille' => $this->request->data['NomFamille'],
                                        'Adulte.Prenom' => $this->request->data['Prenom'])));
    }
    
/**
 * Afficher la fiche d'un parent
 *
 * @param $id le id du parent
 * @return void
 */
    public function fiche($idParent=null) {
        
        if($idParent != null && $this->Session->read('User.role') != 'admin') {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        if($idParent == null) {
            $idParent = $this->Auth->User('IdParent');
        }
        
        $this->loadModel('Adulte');
        $parent = $this->Adulte->findById($idParent);
        $this->set('parent',$parent['Adulte']);
        
        $this->loadModel('Joueur');
        $joueurs = $this->Joueur->findJoueursParParent($idParent);
        $this->set(compact('joueurs'));
        
        //trouver les inscrits pour la saison d'inscription
        $inscrits = $this->Joueur->findJoueursParParent($idParent,1);
        $this->set('inscrits',count($inscrits));
        
        $annee = $this->getConfigValueByName('Annee inscription');
        $this->set(compact('annee'));
    }
    
    public function paiement($idParent=null) {
        
        if($idParent != null && $this->Session->read('User.role') != 'admin') {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        if($idParent == null) {
            $idParent = $this->Auth->User('IdParent');
        }
        
        $this->loadModel('Adulte');
        $parent = $this->Adulte->findById($idParent);
        $this->set('parent',$parent['Adulte']);
        
        $this->loadModel('Joueur');
        $joueurs = $this->Joueur->findJoueursParParent($idParent,1);
        $this->set(compact('joueurs'));
        
        $annee = $this->getConfigValueByName('Annee inscription');
        $this->set(compact('annee'));
        
        $this->layout = 'empty';
    }
    
/**
 * Éditer les informations d'un parent
 *
 * @param $id le id du parent
 * @return void
 */
    public function edit($id=null, $idJoueur=null) {
        
        if($id == null) {
            $id = $this->Auth->User('IdParent');
        }
        
        //bypasser les conditions car admin
        if($this->Auth->User('Admin') == 0) {
            
            if($idJoueur == null && $id != $this->Auth->User('IdParent')) {
                $this->Session->setFlash("Vous ne pouvez pas les droits pour modifier ce parent", 'default', array(), 'bad');
                $this->redirect(array('controller' => 'pages', 'action' => 'home'));
            }
            
            if($id != $this->Auth->User('IdParent')) {
                $this->loadModel('Famille');
                //vérifier si le parent à modifier est vraiment parent avec l'enfant 
                $rs = $this->Famille->findByIdjoueurAndIdparent($idJoueur, $id);
                if(empty($rs)) {
                    $this->Session->setFlash("Vous ne pouvez pas les droits pour modifier ce parent", 'default', array(), 'bad');
                    $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                }

                //vérifier si le joueur est vraiment parent avec vous
                $rs = $this->Famille->findByIdjoueurAndIdparent($idJoueur, $this->Auth->User('IdParent'));
                if(empty($rs)) {
                    $this->Session->setFlash("Vous ne pouvez pas les droits pour modifier ce parent", 'default', array(), 'bad');
                    $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                }
            }
        }
        
        $this->loadModel('Adulte');
        
        if(!$this->request->is('Post')) {
            $parent = $this->Adulte->findById($id);
            $this->set('content',$parent['Adulte']);
        } else {
            $this->Adulte->id = $id;
            $this->request->data['Adulte']['CodePostal'] = str_replace(" ","",$this->request->data['Adulte']['CodePostal']);
            $this->request->data['Adulte']['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Adulte']['TelMaison']);
            $this->request->data['Adulte']['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Adulte']['TelMobile']);
            $this->request->data['Adulte']['DerniereModif'] = date('Y-m-d h:i:s');
            $this->Adulte->save($this->request->data);
            
            if($this->Session->read('User.role') == 'admin') {
                $this->redirect(array('action' => 'fiche',$id));
            } else {
                $this->redirect(array('action' => 'fiche'));
            }
        }
    }

/**
 * Ajouter un parent
 *
 * @return void
 * @post affiche la fiche du parent
 */
    public function ajouter($source='inscription',$idJoueur=null) {
        
        $this->set(compact('source', 'idJoueur'));
        if($source == 'nouvelUsager') {
            $this->set('controller','Users');
        } elseif($source == 'inscription') {
            $this->set('controller','Joueurs');
        }
                
        if($this->request->is('Post')) {  
            $this->loadModel('Adulte');

            if($source == 'nouvelUsager') {
                if(!empty($this->request->data)) {
                    //var_dump($this->request->data);
                    $this->request->data['CodePostal'] = str_replace(" ","",$this->request->data['CodePostal']);
                    $this->request->data['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['TelMaison']);
                    $this->request->data['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['TelMobile']);
                    $this->request->data['DateCreation'] = date('Y-m-d H:i:s');
                    $this->request->data['DerniereModif'] = date('Y-m-d H:i:s');
                    $this->Adulte->save($this->request->data);
                    $idAdulte = $this->Adulte->id;

                    $this->Session->write('NouveauUser.idParent', $idAdulte);
                    $this->redirect(array('controller' => 'users', 'action' => 'add'));
                }
            } elseif($source == 'inscription') {
                $this->request->data['Adulte']['CodePostal'] = str_replace(" ","",$this->request->data['Adulte']['CodePostal']);
                $this->request->data['Adulte']['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Adulte']['TelMaison']);
                $this->request->data['Adulte']['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Adulte']['TelMobile']);
                $this->request->data['Adulte']['DateCreation'] = date('Y-m-d h:i:s');
                $this->request->data['Adulte']['DerniereModif'] = date('Y-m-d h:i:s');
                if($this->Adulte->save($this->request->data)) {
                    $idParent = $this->Adulte->id;
                    if($this->request->data['Adulte']['Courriel1'] != null) {
                        $UsersController = new UsersController();
                        $this->request->data['User']['Role'] = 'parent';
                        $err = $UsersController->creerUsager($idParent,$this->request->data);
                        
                        if($err == 0) {
                            if($this->Session->read('User.role') == 'admin') {
                                $this->Session->setFlash("L'usager a été créé", 'default', array(), 'good');
                                $this->redirect(array('action' => 'fiche', $idParent));
                                
                            } else {
                                $this->Session->setFlash("Vous allez recevoir votre mot de passe dans votre boite de réception", 'default', array(), 'good');
                            }  
                        }
                        
                        $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                    }
                    
                } else {
                    $this->Session->setFlash("Une erreur est survenue", 'bad');
                    $this->redirect(array('action' => 'recherche'));
                }
                
            } elseif($source == 'joueur') {
                $this->request->data['Adulte']['CodePostal'] = str_replace(" ","",$this->request->data['Adulte']['CodePostal']);
                $this->request->data['Adulte']['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Adulte']['TelMaison']);
                $this->request->data['Adulte']['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Adulte']['TelMobile']);
                $this->request->data['Adulte']['DateCreation'] = date('Y-m-d h:i:s');
                $this->request->data['Adulte']['DerniereModif'] = date('Y-m-d h:i:s');
                $this->Adulte->save($this->request->data);
                $idParent = $this->Adulte->id;
                
                $this->lierParentJoueur($idParent, $idJoueur);
            }
        } 
    }
    
    public function inscriptionEnLigne() {
        
        if($this->request->is('Post') && 
            $this->request->data['Adulte']['Courriel1'] == $this->request->data['Adulte']['CourrielConfirm']) {
            
            $this->loadModel('User');
            $user = $this->User->findByUsername($this->request->data['Adulte']['Courriel1']);
            if(!empty($user)) {
                $this->Session->setFlash("Cette adresse électronique est déjà utilisée par un usager", 'default', array(), 'bad');
            } else {
                $this->request->data['Adulte']['Courriel2'] = '';
                $this->request->data['Adulte']['TelMaison'] = '';
                $this->request->data['Adulte']['TelMobile'] = '';
                $this->rechercher();
            }
        }
        
        $this->layout = 'accueil';
    }
    
    public function recu($saison,$idParent=null) {
        if($idParent != null && $this->Session->read('User.role') != 'admin') {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        if($idParent == null) {
            $idParent = $this->Auth->User('IdParent');
        }
        
        $this->loadModel('Adulte');
        $parent = $this->Adulte->findById($idParent);
        $this->set('parent',$parent['Adulte']);
        
        //trouver les inscrits pour la saison
        $this->loadModel('Joueur');
        $joueurs = $this->Joueur->findInscritsParParent($idParent,$saison);
        
        $this->set(compact('joueurs','saison'));
    }
    
    public function existe($courriel) {
        $this->set(compact('courriel'));
        $this->layout = 'accueil';
    }
        
    
    public function lierParentJoueur($idParent, $idJoueur) {
        if($this->verifierLienParentJoueur($idJoueur) == false) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
            
        $this->loadModel('Famille');
        $this->Famille->Create();
        $data = array('idJoueur' => $idJoueur,
                      'idParent' => $idParent);
        $this->Famille->save($data);

        $this->redirect(array('controller' => 'joueurs', 'action' => 'fiche', $idJoueur));
    }
    
    public function supprimer($idLienFamille,$idJoueur) {
        if($this->verifierLienParentJoueur($idJoueur) == false) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
            
        $this->loadModel('Famille');
        $this->Famille->delete($idLienFamille);

        $this->redirect(array('controller' => 'joueurs', 'action' => 'fiche', $idJoueur));
    }

}
