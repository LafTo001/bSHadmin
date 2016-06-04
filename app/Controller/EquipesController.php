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
App::import('Controller', 'Parties');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class EquipesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        public $root = '/bshadmin';

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
        $this->Auth->allow('index'); 
    }

    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->Session->setFlash(__("Vous n'avez pas accès à cette page"));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
    
    public function index($nomCategorie='',$classe='') {
        
        if($this->request->is(array('post', 'put'))) {
            if(isset($this->request->data['Equipe']['classe'])) {
                $this->redirect(array('action' => 'index', $this->request->data['Equipe']['nomCategorie'], 
                    $this->request->data['Equipe']['classe']));
            }
            $this->redirect(array('action' => 'index', $this->request->data['Equipe']['nomCategorie']));
        }
        
        $this->set('categories', $this->listerNomCategories());
        if($nomCategorie != '')
            $this->set('classes', $this->listerClasses($nomCategorie));
        if($classe != '')
            $this->set('equipes', $this->listerEquipes($nomCategorie,$classe));
        $this->set(compact('nomCategorie','classe'));
        
        $this->layout = 'accueil';
    }

    public function liste() {
        $this->loadModel('VueEquipe');
        $listeSH = $this->VueEquipe->find('all',array('conditions' => array('idAssociation' => 2),
                                            'order' => array('idCategorie','Classe','NomEquipe')
            ));
            
        $this->set('listeCourriels',$this->listerCourrielsEntraineurs());


        $listeRY = $this->VueEquipe->find('all',array('conditions' => array('ville' => 'Richelieu-Yamaska'),
                                            'order' => array('idCategorie','Classe','NomEquipe')
            ));
        
        $this->set('annee', $this->getConfigValueByName('Annee equipe'));

        $this->set(compact('listeSH', 'listeRY'));
    }

    public function ajouter() {
        if($this->request->is('post')) {
            $this->request->data['Equipe']['Saison'] = date('Y');
            $this->request->data['Equipe']['IdAssociation'] = 2;
            $this->request->data['Equipe']['DateCreation'] = date('Y-m-d H:i:s');
            $this->request->data['Equipe']['DerniereModif'] = date('Y-m-d H:i:s');
            //$this->request->data['Equipe']['ModifParUsager'] = $this->Auth->User('id');
            
            $this->Equipe->save($this->request->data);
            $id = $this->Equipe->id;

            $this->loadModel('Entraineur');
            $this->Entraineur->create();
            $fields = array('IdParent' => $this->request->data['Equipe']['Ent1'],
                            'IdEquipe' => $id,
                            'Titre' => '1',
                            'DateCreation' => date('Y-m-d H:i:s'),
                            'DerniereModif' => date('Y-m-d H:i:s'),
                            'ModifParUsager' => $this->Auth->User('id'));
            $this->Entraineur->save($fields);

            $this->redirect(array('action' => 'liste'));
        }
            
        $this->set('listeCategories',$this->listerCategories());
        $this->set('listeParents',$this->listerParents());
    }

    public function editer($id) {

        $this->loadModel('Entraineur');
        if($this->request->is(array('post','put'))) {
            $this->request->data['Equipe']['DerniereModif'] = date('Y-m-d H:i:s');
            $this->request->data['Equipe']['ModifParUsager'] = $this->Auth->User('id');
            $this->Equipe->id = $id;
            $this->Equipe->save($this->request->data);

            $this->Entraineur->id = $this->lookupEntraineur($id,1);
            $this->Entraineur->saveField('IdParent',$this->request->data['Equipe']['Ent1']);

            $this->redirect(array('action' => 'liste'));
            
        } else {
            $equipe = $this->Equipe->findById($id);
            $this->request->data = $equipe;

            $ent = $this->Entraineur->findByIdequipeAndTitre($id,1);
            if(!empty($ent)) {
                $this->set('ent1',$ent['Entraineur']['IdParent']);
            } else {
                $this->set('ent1',0);
            }

            $this->set('listeCategories',$this->listerCategories());
            $this->set('listeParents',$this->listerParents());
        }
    }

    public function fiche($idEquipe=null) {
        if($this->request->is(array('post', 'put'))) {
            if(isset($this->request->data['Inscription'])) {
                $this->loadModel('Inscription');
                $this->Inscription->id = $this->request->data['Inscription']['id'];
                $this->Inscription->saveField('Numero', $this->request->data['Inscription']['Numero']);
                
            } elseif(isset($this->request->data['Entraineur'])) {
                $this->loadModel('Entraineur');
                $this->Entraineur->id = $this->request->data['Entraineur']['id'];
                $this->Entraineur->save($this->request->data);
            }
        }
        
        $this->loadModel('VueEquipe');
        if($idEquipe == null) {
            $idEquipe = $this->Session->read('Equipe.id');
        }
        $equipe = $this->VueEquipe->findByIdequipe($idEquipe);
        $this->set('equipe',$equipe);

        $this->loadModel('VueJoueur');
        $joueurs = $this->VueJoueur->find('all',array('conditions' => array(
                                        'IdEquipe' => $idEquipe),
                                    'order' => array('NomPrenom' => 'asc')
        ));

        $this->set('joueurs',$joueurs);

        $this->loadModel('VueEntraineur');
        $rs = $this->VueEntraineur->find('all',array('conditions' => array(
                                        'IdEquipe' => $idEquipe),
                                    'order' => array('NoTitre' => 'asc',
                                                     'NomPrenom' => 'asc')
        ));
        
        if($equipe['VueEquipe']['ConfirmationChandail'] == 0) {
            $numeros = array();
            for($n = 1; $n < 100; $n++) {
                $numeros[$n] = $n;
            }
            
            $taillesChandail = array(   'AS' => 'AS',
                                        'AM' => 'AM',
                                        'AL' => 'AL',
                                        'AXL' => 'AXL',
                                        'AXXL' => 'AXXL');
            
            $this->set(compact('numeros', 'taillesChandail'));
        }
        
        $this->set('entraineurs',$rs);
        
        
        if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->listerEquipesEntraineur('fiche'));
            $this->set('source','fiche');
        }
    }

    public function supprimer($id,$confirm=0) {
        $this->validerRole('admin');

        if($confirm == 1) {
            $this->Equipe->id = $id;
            $this->Equipe->saveField('DateSuppression',date('Y-m-d H:i:s'));

            $this->redirect(array('action' => 'liste'));

        } else {
            $this->loadModel('VueEquipe');
            $equipe = $this->VueEquipe->findByIdequipe($id);
            $this->set('equipe',$equipe);
            $this->set('id',$id);
        }
    }

    public function reserviste() {
        $this->set('listeParties',$this->listerParties());

        //s'il n'y a pas de partie à venir
        if($this->Session->read('Partie.id') == null) {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        $this->loadModel('VuePartie');
        $partie = $this->VuePartie->getPartieEntraineur($this->Session->read('Partie.id'),
                                                        $this->Session->read('User.idParent'));

        $this->set('listeEquipesReserviste',$this->listerEquipesReserviste($partie[0]['P']['IdCategorie']));

        $this->loadModel('VueJoueur');
        $joueurs = $this->VueJoueur->find('all',array('conditions' => array(
                                    'IdEquipe' => $this->Session->read('Reserviste.idEquipe')),
                                    'order' => array('NomPrenom' => 'asc')
        ));
        $this->set('joueurs',$joueurs);
        
        //chercher si l'équipe sélectionnée joue la journée du match
        $partiesJour = $this->VuePartie->find('all',array('conditions' => array(
                                                'OR' => array('IdEquipeVisiteur' => $this->Session->read('Reserviste.idEquipe'),
                                                              'IdEquipeReceveur' => $this->Session->read('Reserviste.idEquipe')),
                                                'Date' => $partie[0]['P']['Date'])));
        $this->set('partiesJour',$partiesJour);
        
        if($this->Session->read('User.role') == 'entraineur' && $this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->listerEquipesEntraineur('reserviste'));
            $this->set('source','reserviste');
        }
    }
    
    public function ajoutEntraineur($idEquipe=null) {
        if($idEquipe == null) {
            $idEquipe = $this->Session->read('Equipe.id');
        }
        if($this->request->is('post')) {
            $this->loadModel('Entraineur');
            $this->request->data['Entraineur']['IdEquipe'] = $idEquipe;
            $this->request->data['Entraineur']['DateCreation'] = date('Y-m-d H:i:s');
            $this->request->data['Entraineur']['DerniereModif'] = date('Y-m-d H:i:s');
            $this->request->data['Entraineur']['ModifParUsager'] = $this->Auth->User('id');
            $this->Entraineur->save($this->request->data);
            
            $this->redirect(array('action' => 'fiche'));
        } else {
            //créer la liste des parents de l'équipe
            $this->loadModel('Adulte');
            $rs = $this->Adulte->listeParentsEquipe($idEquipe);

            $liste = array();
            foreach($rs as $parent) {
                $liste[$parent['P']['id']] = $parent[0]['NomComplet'];
            }
            $this->set('listeParents',$liste);

            //drop down des titres
            $listeTitres = array(2 => 'Assistant-entraineur',
                                 3 => 'Gérant(e)');
            $this->set('listeTitres',$listeTitres);
        }
    }
    
    public function confirmerChandails($confirm=0) {
        if($confirm == 1) {
            $this->Equipe->id = $this->Session->read('Equipe.id');
            $this->Equipe->saveField('ConfirmationChandail',date('Y-m-d H:i:s'));
            $this->Session->setFlash(__("La commande a été envoyé au responsable des chandails"));
            $this->redirect(array('action' => 'fiche'));
        }
    }

    public function lookupEntraineur($idEquipe, $titre) {
        $this->loadModel('Entraineur');
        $ent = $this->Entraineur->findByIdequipeAndTitre($idEquipe, $titre);
        if(empty($ent)) {
            $entraineur = array('IdEquipe' => $idEquipe,
                                'Titre' => $titre);
            $this->Entraineur->save($entraineur);
            return $this->Entraineur->id;
        }
        return $ent['Entraineur']['id'];
    }

    public function changerPartie($id) {
        $this->Session->write('Partie.id',$id);
        $this->redirect(array('action' => 'reserviste'));
    }

    public function changerEquipe() {
        if($this->request->is('post','put')) {
            $this->Session->write('Equipe.id', $this->request->data['Equipe']['idEquipe']);
            $this->redirect(str_replace("/".basename(dirname(APP)), "", $this->request->data['Equipe']['url']));
        } else {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }

    public function changerEquipeReserviste($idEquipe) {
        $this->Session->write('Reserviste.idEquipe', $idEquipe);
        $this->redirect(array('action' => 'reserviste'));
    }
    
    public function ajouterReservisteAlignement($idJoueur, $equipe) {
        $this->loadModel('Alignement');
        $alignement = array();
        $alignement['IdPartie'] = $this->Session->read('Partie.id');
        $alignement['IdEquipe'] = $this->Session->read('Equipe.id');
        $alignement['IdJoueur'] = $idJoueur;
        $alignement['Statut'] = 2;
        $alignement['DateCreation'] = date('Y-m-d H:i:s');
        $alignement['DerniereModif'] = date('Y-m-d H:i:s');
        $alignement['ModifParUsager'] = $this->Auth->User('id');
        
        $this->Alignement->save($alignement);
        
        $this->redirect(array('controller' => 'parties', 'action' => 'gestion'));
    }
    
    /*public function assignerNumeroJoueur($idJoueur, $numero=null) {
        $this->loadModel('Inscription');
        $rs = $this->Inscription->findByIdjoueurAndSaison($idJoueur,date('Y'));
        
        $this->Inscription->id = $rs['Inscription']['id'];
        $this->Inscription->saveField('Numero',$numero);
        $this->redirect(array('action' => 'fiche'));
    }
    
    public function assignerNumeroEntraineur($id, $numero=null) {
        $this->loadModel('Entraineur');
        $this->Entraineur->id = $id;
        $this->Entraineur->saveField('Numero',$numero);
        $this->redirect(array('action' => 'fiche'));
    }
    
    public function assignerChandailEntraineur($id, $taille=null) {
        $this->loadModel('Entraineur');
        $this->Entraineur->id = $id;
        $this->Entraineur->saveField('Chandail',$taille);
        $this->redirect(array('action' => 'fiche'));
    }*/

    public function listerParties() {
        $this->loadModel('VuePartie');
        $liste = array();
        $rs = $this->VuePartie->find('all',array('conditions' => array(
                                        'date >=' => date('Y-m-d'),
                                        'OR' => array('idEquipeVisiteur' => $this->Session->read('Equipe.id'),
                                                      'idEquipeReceveur' => $this->Session->read('Equipe.id')
                                        )),
                                    'order' => array('date','heure')
        ));

        if($rs) {
            //trouver prochaine partie si aucune sélectionnée
            //if($this->Session->read('Partie.id') == null) {
            //    $this->Session->write('Partie.id',$rs[0]['VuePartie']['idPartie']);
            //}

            foreach($rs as $partie) {
                $liste['changerPartie/'.$partie['VuePartie']['idPartie']] = 
                    $partie['VuePartie']['Date'].' - '.$partie['VuePartie']['NomLigue'].' #'.$partie['VuePartie']['NoPartie'].
                    ' - '.$partie['VuePartie']['NomEquipeVisiteur'].' vs. '.$partie['VuePartie']['NomEquipeReceveur'];
            }
        }
        return $liste;
    }

    public function listerParents() {
        $this->loadModel('VueParent');
        $liste = $this->VueParent->find('list',array(
            'fields' => array('VueParent.idParent', 'VueParent.nomPrenom'),
            'order' => array('VueParent.NomFamille' => 'asc', 'VueParent.Prenom' => 'asc')
        ));

        return $liste;
    }

    public function listerEquipesEntraineur() {
        $this->loadModel('VueEntraineur');
        $rs = $this->VueEntraineur->find('list', array(
                                            'fields' => array('IdEquipe', 'NomCompletEquipe'),
                                            'conditions' => array(
                                                'IdUsager' => $this->Auth->User('id'),
                                                'IdCategorie > ' => '1'
            )));
        
        return $rs;
    }
    
    public function listerCourrielsEntraineurs() {
        $this->loadModel('VueEntraineur');
        $rs = $this->VueEntraineur->find('all',array('conditions' => array(
                                                'IdAssociation' => 2,
                                                'IdCategorie >' => 1
        )));   

        $liste = '';
        foreach($rs as $ent):
            $liste .= $ent['VueEntraineur']['Courriel1'].'; ';
            if($ent['VueEntraineur']['Courriel2'] != null) {
                $liste .= $ent['VueEntraineur']['Courriel2'].'; ';
            }
        endforeach;

        return $liste;
    }

    public function listerEquipesReserviste($idCategorie) {
        $this->loadModel('VueEquipe');
        $liste = $this->VueEquipe->find('all',array('conditions' => array(
                                            'idAssociation' => '2',
                                            'idCategorie <=' => $idCategorie),
                                        'order' => array('idCategorie' => 'asc',
                                                         'classe' => 'asc',
                                                         'nomEquipe' => 'asc')

        ));

        $select = array();
        foreach($liste as $equipe) {
            $select['changerEquipeReserviste/'.$equipe['VueEquipe']['idEquipe']] = $equipe['VueEquipe']['NomComplet'];
        }

        return $select;
    }
    
    public function listerEquipes($nomCategorie,$classe) {
        $this->loadModel('VueEquipe');
        $rs = $this->VueEquipe->find('all', array(
            'fields' => array('NomEquipe'),
            'conditions' => array('nomCategorie' => $nomCategorie,
                                  'Classe' => $classe,
                                  'Ville' => 'St-Hyacinthe'),
            'order' => array('NomEquipe ASC')
        ));
        
        return $rs;
    }
    
    private function listerCategories() {
        
        $this->loadModel('Categorie');
        $liste = $this->Categorie->find('list', array(
                            'fields' => array('id', 'NomCategorie')
            ));
    
        return $liste;
    }
    
    private function listerNomCategories() {
        
        $this->loadModel('Categorie');
        $liste = $this->Categorie->find('list', array(
                            'fields' => array('Categorie.NomCategorie', 'Categorie.NomCategorie'),
                            'conditions' => array('id !=' => 1)
            ));
    
        return $liste;
    }
    
    private function listerClasses($nomCategorie)
    {
        $rs = array();
        if ($nomCategorie != "") {
            $this->loadModel('VueEquipe');
            $rs = $this->VueEquipe->find('list', array(
                                        'fields' => array('Classe', 'Classe'),
                                        'conditions' => array('nomCategorie' => $nomCategorie,
                                                              'IdAssociation' => 2)
                ));
        }
        return $rs;
    }
}
