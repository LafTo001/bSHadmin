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
class EmailsController extends AppController {

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
 
    public function composer($id=null) {
        
        $this->validerRole('admin');
        
        //var_dump($this->request->data);
        
        if($this->request->is('Post', 'put')) {
            
            //enregistrement si demandé
            if((isset($this->request->data['Email']['Enregistrer']) && $this->request->data['Email']['Enregistrer'] == 1)
                || isset($this->request->data['Enregistrer'])) {
                
                $this->request->data['Email']['IdUser'] = $this->Auth->User('id');
                $this->request->data['Email']['DateCreation'] = date('Y-m-d H:i:s');
                $this->Email->id = $id;
                $this->Email->save($this->request->data);
                if(isset($this->request->data['Enregistrer'])) {
                    $this->Session->setFlash("Votre message a été enregistré", 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                }
            }
            
            //envoi du courriel
            
            //préparer l'expéditeur
            if($this->request->data['Email']['Expediteur'] == 'noreply') {
                $this->request->data['Email']['Expediteur'] = array('noreply@baseballsthyacinthe.com' => 'Baseball Saint-Hyacinthe');
            } else {
                $this->loadModel('User');
                $user = $this->User->findByUsername($this->request->data['Email']['Expediteur']);
                $this->request->data['Email']['Expediteur'] =
                    array($this->request->data['Email']['Expediteur'] => $user[0]['NomComplet']);
            }
            
            //préparer le destinataire
            if($this->request->data['Email']['Destinataire'] == 'Principaux') {
                $liste = $this->Email->findEmailParentsPrincipaux();
                //var_dump($liste);
            }

            if(isset($this->request->data['Envoyer'])) {
                foreach($liste as $adresse) {
                    $this->request->data['Email']['Destinataire'] = $adresse['p']['Courriel1'];
                    echo $this->request->data['Email']['Destinataire'].'; ';
                    
                    /*if($this->request->data['Email']['Destinataire'] == 'amandine_maude23@hotmail.com') {
                        $succes = $this->envoyer($this->request->data['Email']);
                        if($succes == false) {
                            $this->Session->setFlash("Erreur lors de l'envoi du message électronique", 'default', array(), 'bad');
                            $this->redirect(array('action' => 'index'));
                        }
                    }*/
                }
                $this->Email->saveField('DateEnvoi', date('Y-m-d H:i:s'));
            }
           
            $this->Session->setFlash("Le message a été envoyé avec succès", 'default', array(), 'good');
        }
        
        if($id != null) {
            $this->request->data = $this->Email->findById($id);
        }
        
        //sélection de l'expéditeur
        $expediteurs = array($this->Auth->User('username') => $this->Auth->User('NomComplet'),
                            'noreply' => 'Baseball Saint-Hyacinthe');
        
        //sélection du destinataire
        $destinataires = array( 'Parents' => 'Tous les parents',
                                'Entraineurs' => 'Tous les entraineurs',
                                'Principaux' => 'Seulements les parents principaux',
                                'Terrains' => 'Responsables de terrains',
                                'Arbitres' => 'Arbitres / marqueurs');
        
        $this->set(compact('expediteurs', 'destinataires'));
    }
    
    public function supprimer($id) {
        
    }
    
    public function envoyer($data) {
        $Email = new CakeEmail('default');
        var_dump($data);
        
        $Email->from($data['Expediteur']);
        $Email->to($data['Destinataire']);
        $Email->emailFormat('html');
        $Email->subject($data['Sujet']);
        $Email->send($data['Message']);
        
        return true;
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
    
    
}