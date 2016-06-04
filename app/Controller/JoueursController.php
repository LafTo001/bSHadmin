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
class JoueursController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
    public $uses = array();
    //public $root = '/bshadmin';

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
        'limit' => 16,
        'order' => array('VueJoueur.NomPrenom' => 'asc')
    );
    
    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
    
        
/**
 * Liste des joueurs
 *
 * @return void
 */
    public function index($categorie=null,$classe=null,$equipe=null) {
        
        $categorie = str_replace("_", " ", $categorie);
        
        $this->validerRole('admin');
        
        //vérifier si recherche
        if(isset($this->request->data['Joueur']['nomComplet']) && $this->request->data['Joueur']['nomComplet']) {
            $this->rechercher($this->request->data['Joueur']);
        }
        
        //redirection de l'index
        if(isset($this->request->data['Joueur'])) {
            if(isset($this->request->data['Joueur']['nomEquipe'])) {
                $this->redirect(array('action' => 'index', $categorie, $classe, $this->request->data['Joueur']['nomEquipe']));
            }
            if(isset($this->request->data['Joueur']['classe'])) {
                $this->redirect(array('action' => 'index', $categorie, $this->request->data['Joueur']['classe']));
            }
            $this->loadModel('Categorie');
            $cat = $this->Categorie->findById($this->request->data['Joueur']['IdCategorie']);
            if(!empty($cat)) {
                $this->redirect(array('action' => 'index', str_replace(" ", "_", $cat['Categorie']['NomCategorie'])));
            }
            $this->redirect(array('action' => 'index'));
        }
        
        //assigner une classe ou une équipe à un joueur
        if(isset($this->request->data['Inscription']['id'])) {
            $this->loadModel('Inscription');
            $this->Inscription->id = $this->request->data['Inscription']['id'];
            if($this->request->data['Inscription']['Classe'] == '') {
                $this->request->data['Inscription']['idEquipe'] = 0;
            }
            $this->Inscription->saveField('Classe', $this->request->data['Inscription']['Classe']);
            if(isset($this->request->data['Inscription']['idEquipe'])) {
                $this->Inscription->saveField('idEquipe', $this->request->data['Inscription']['idEquipe']);
            }
        }
        
        $this->loadModel('VueJoueur');
        $this->paginate = array(
            'limit' => 16,
            'order' => array('VueJoueur.nomPrenom' => 'asc')
        );

        if($categorie != null) {
            $this->paginate['conditions'] = array('NomCategorie' => $categorie);

            if($classe != null) {
                $this->paginate['conditions'][] = array('Classe' => $classe);

                if($equipe != null) {
                    $this->paginate['conditions'][] = array('nomEquipe' => $equipe);
                }
            }
        }
        //var_dump($this->paginate);
        $joueurs = $this->paginate('VueJoueur');

        if($categorie != null && $this->getConfigValueByName('Annee equipe') == date('Y')) {
            foreach($joueurs as $cle => $joueur) {
                $joueurs[$cle]['Select']['Classe'] = $this->listerClasses($joueur['VueJoueur']);
                if($joueur['VueJoueur']['Classe'] != '') {
                    $joueurs[$cle]['Select']['Equipe'] = $this->listerEquipes($joueur['VueJoueur']);
                }
            }
        }
        $this->set(compact('joueurs'));

        //créer la liste des courriels
        if($categorie != null) {
            $courriels = $this->creerListeCourriel($categorie, $classe, $equipe);
            $this->set('courriels',$courriels);
        }

        //créer les listes
        $this->loadModel('Categorie');
        $cat = $this->Categorie->findByNomcategorie(str_replace("_", " ", $categorie));
        if(!empty($cat)) {
            $this->request->data['Joueur']['IdCategorie'] = $cat['Categorie']['id'];
        }
        $this->request->data['Joueur']['Classe'] = $classe;
        $this->request->data['Joueur']['Equipe'] = $equipe;
        
        $this->set('listeCategories', $this->listerCategories());
        if($categorie != null) {
            $this->set('listeClasses', $this->listerClasses($this->request->data['Joueur']));
        }
        if($classe != null) {
            $this->set('listeEquipes', $this->listerNomEquipes($this->request->data['Joueur']));
        }
        
        $this->set(compact('categorie', 'classe', 'equipe'));
        //var_dump($this->request->data['Joueur']);
              
    }
    
    public function rechercher($data) {
        $this->loadModel('VueJoueur');
        //vérifier le nombre de joueur ayant ce nom
        $count = $this->VueJoueur->find('count',
            array('conditions' => array('NomComplet' => $data['nomComplet'])));
        //si un seul, accéder à la fiche de ce joueur
        if($count == 1) {
            $rs = $this->VueJoueur->findByNomcomplet($data['nomComplet']);
            $this->redirect(array('action' => 'fiche', $rs['VueJoueur']['idJoueur']));
        }
        //si plus qu'un, trouver tous et page Rechercher
        elseif($count > 1) {
            $rs = $this->VueJoueur->findAllByNomcomplet($data['nomComplet']);
            $this->set('joueurs',$rs);
        } 
        //sinon retourner à la page Index
        else {
            $this->Session->setFlash("Ce joueur n'existe pas", 'bad');
            $this->redirect(array('action' => 'index'));
        }
    }
    
/**
 * Afficher la fiche d'un joueur
 *
 * @param $id le id du joueur
 * @return void
 */
    public function fiche($id) {
        if($this->verifierLienParentJoueur($id) == false && $this->Session->read('User.role') != 'entraineur') {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        $this->loadModel('VueJoueur');
        $joueur = $this->VueJoueur->findByIdjoueur($id);
        $this->set('data',$joueur);
        
        $this->loadModel('Adulte');
        $parents = $this->Adulte->getParentsJoueur($id);
        $this->set('parents',$parents);
        $this->set('nbParents',count($parents));
        
        $numeros = array();
        for($n = 1; $n < 100; $n++) {
            $numeros[$this->root.'/joueurs/assignerNumeroJoueur/'.$id.'/'.$n] = $n;
        }
        $this->set('numeros',$numeros);
        $this->set('parent', $this->verifierLienParentJoueur($id));
        
        $this->loadModel('VueImpot');
        $saisons = $this->VueImpot->findAllByIdjoueur($id);

        $this->set(compact('saisons'));
            
    }

/**
 * Éditer la fiche d'un joueur
 *
 * @param $id le id du joueur
 * @return void
 */
    public function edit($id) {
       
        if($this->verifierLienParentJoueur($id) == false)  {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        if(!$this->request->is('Post')) {
            $this->loadModel('Joueur');
            $joueur = $this->Joueur->findById($id);
            $this->set('content',$joueur['Joueur']);

            $ouiNon = array('0' => 'Non',
                            '1' => 'Oui');
            $this->set('ouiNon',$ouiNon);

            $this->loadModel('Adulte');
            $parents = $this->Adulte->getParentsJoueur($id);
            $this->set('parents',$parents);

        } else {
            $this->loadModel('Joueur');
            $this->Joueur->id = $id;
            $this->request->data['Joueur']['DateExpCAL'] .= '-01';
            $this->Joueur->save($this->request->data);

            $this->redirect(array('action' => 'fiche', $id));
        }
    }
    
/**
 * Ajouter un joueur à un parent connu
 *
 * @param $id le id du parent
 * @return void
 */
    public function ajout($idParent=null) {
        
        if($idParent == null) {
            $idParent = $this->Session->read('User.idParent');
        }
        
        if($this->Auth->User('Admin') == 0 && $idParent != $this->Session->read('User.idParent'))  {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        $ouiNon = array('0' => 'Non',
                        '1' => 'Oui');
        
        $this->set(compact('ouiNon', 'idParent'));
        
        if($this->request->is('Post')) {
            
            //enregistrement du joueur
            $this->request->data['Joueur']['idParentPrincipal'] = $idParent;
            $this->request->data['Joueur']['DateCreation'] = date('Y-m-d H:i:s');
            $this->request->data['Joueur']['DerniereModif'] = date('Y-m-d H:i:s');
            $this->request->data['Joueur']['DateExpCAL'].= '-01';
            
            if($this->Joueur->save($this->request->data)) {
                $idJoueur = $this->Joueur->id;
                
                $this->loadModel('Famille');
                $this->Famille->Create();
                $data = array('idJoueur' => $idJoueur,
                              'idParent' => $idParent);
                $this->Famille->save($data);
                
                $this->redirect(array('controller' => 'joueurs', 'action' => 'inscription',$idJoueur));
            }
        }
    }

/**
 * Ajouter un joueur à un parent connu
 *
 * @param $id le id du parent
 * @return void
 */
    public function inscription($idJoueur) {
        
        $this->loadModel('VueJoueur');
        $joueur = $this->VueJoueur->findByIdjoueur($idJoueur);
        $this->set(compact('joueur'));
        
        $saison = $this->getConfigValueByName('Annee inscription');
        $this->set(compact('saison'));
        
        if($this->Auth->User('Admin') == 0 && $this->verifierLienParentJoueur($idJoueur) == false) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        if($this->request->is(array('post','put'))) {
            $this->loadModel('Inscription');
            $inscrit = $this->Inscription->findByIdjoueurAndSaison($idJoueur,$this->getConfigValueByName('Annee inscription'));
            if(!empty($inscrit)) {
                $this->Inscription->id = $inscrit['Inscription']['id'];
            }
            
            $inscrit['Inscription'] = $this->request->data['Joueur'];
            $inscrit['Inscription']['IdJoueur'] = $idJoueur;
            $inscrit['Inscription']['Saison'] = $this->getConfigValueByName('Annee inscription');
            $inscrit['Inscription']['DateCreation'] = date('Y-m-d H:i:s');
            $inscrit['Inscription']['DerniereModif'] = date('Y-m-d H:i:s');
            
            if($inscrit['Inscription']['IdCategorie'] == 0) {
                $inscrit['Inscription']['Classe'] = '';
                $inscrit['Inscription']['idEquipe'] = 0;
            }

            if($this->Inscription->save($inscrit)) {
                $this->Session->setFlash("Le joueur a été inscrit avec succès", 'good');
            } else {
                $this->Session->setFlash("Erreur", 'bad');
            }
            
            $this->redirect(array('controller' => 'joueurs', 'action' => 'fiche',$idJoueur));
        }
        $this->request->data['Joueur'] = $joueur['VueJoueur'];
        
        $this->set('chandails', $this->listerTaillesChandail());
        if($joueur['VueJoueur']['IdCategorie'] == null) {
            $this->request->data['Joueur']['IdCategorie'] = $this->categorieParDefaut($joueur['VueJoueur']);
        }
        if($this->Session->read('User.role') == 'admin') {
            $this->set('categories', $this->listerCategories());
        } else {
            $this->loadModel('Categorie');
            $cat = $this->Categorie->findById($this->request->data['Joueur']['IdCategorie']);
            $this->request->data['Joueur']['NomCategorie'] = $cat['Categorie']['NomCategorie'];
        }
        $this->set('modePaiement',$this->listerModesPaiement());

    }

/**
 * Renouveller l'inscription d'un joueur
 *
 * @param $id le id du joueur
 * @return void
 */
    public function renouveller($id) {
        if($this->verifierLienParentJoueur($id) == false) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
            
        $this->loadModel('Inscription');
         if ($this->request->is(array('post', 'put'))) {
            if($this->Inscription->save($this->request->data)) {
                $this->Session->setFlash('Inscription du joueur effectée avec succès','good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash("L'Inscription du joueur a échouée", 'bad');
            }
        } else {
            $inscrit = $this->Inscription->find('first', array('conditions' => array(
                                                            'IdJoueur' => $id, 
                                                            'Saison' => date('Y')
            )));
            $this->request->data = $inscrit;
            $this->request->data['Inscription']['IdJoueur'] = $id;
            $this->request->data['Inscription']['Saison'] = date('Y');

            $this->loadModel('Joueur');
            $joueur = $this->Joueur->findById($id);
            $this->set('joueur',$joueur['Joueur']);

            $this->set('chandails', $this->listerTaillesChandail());
            if(!isset($this->request->data['Inscription']['IdCategorie'])) {
                $this->request->data['Inscription']['IdCategorie'] = $this->categorieParDefaut($joueur['Joueur']);
            }
            if($this->Session->read('User.role') == 'admin') {
                $this->set('categories', $this->listerCategories());
            } else {
                $this->loadModel('Categorie');
                $cat = $this->Categorie->findById($this->request->data['Inscription']['IdCategorie']);
                $this->request->data['Inscription']['NomCategorie'] = $cat['Categorie']['NomCategorie'];
            }
            $this->set('modePaiement',$this->listerModesPaiement());
        }
       
    }
    
/**
 * Imprimer le reçu d'impôt du joueur
 *
 * @param $id le id du joueur
 * @return void
 */
    public function recuImpot($idInscrit) {
        $this->loadModel('VueImpot');
        $rs = $this->VueImpot->findByIdinscription($idInscrit);
        
        if($this->verifierLienParentJoueur($rs['VueImpot']['idJoueur']) == false) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        $this->set('recu', $rs);
        
        $this->layout = 'empty';
    }
    
    public function ajoutParent($idJoueur) {
        if($this->verifierLienParentJoueur($idJoueur) == false) {
            $this->Session->setFlash("Vous n'avez pas accès à cette page", 'bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        $this->loadModel('VueJoueur');
        $joueur = $this->VueJoueur->findByIdjoueur($idJoueur);
        
        $this->loadModel('VueParent');
        $parents = $this->VueParent->findParentsFamille($idJoueur,$joueur['VueJoueur']['idParentPrincipal']);
        
        if(empty($parents)) {
            $this->redirect(array('controller' => 'parents', 'action' => 'rechercher',$idJoueur));
        }
        
        $this->set(compact('joueur','parents'));
    }
    
    /*public function changerCategorie($cat) {
        $this->Session->write('Joueur.Categorie',$cat);
        $this->Session->write('Joueur.Classe','');
        $this->Session->write('Joueur.Equipe',0);
        $this->redirect(array('action' => 'index'));
    }
    
    public function changerClasse($classe='') {
        $this->Session->write('Joueur.Classe',$classe);
        $this->Session->write('Joueur.Equipe',0);
        $this->redirect(array('action' => 'index'));
    }
    
    public function changerEquipe($equipe) {
        $this->Session->write('Joueur.Equipe',$equipe);
        $this->redirect(array('action' => 'index'));
    }
    
    public function assignerClasseJoueur($idJoueur=0, $classe='') {
        $this->loadModel('Inscription');
        $rs = $this->Inscription->findByIdjoueurAndSaison($idJoueur,date('Y'));
        
        $this->Inscription->id = $rs['Inscription']['id'];
        $fields['Inscription'] = array('Classe' => $classe,
                                       'idEquipe' => 0);
        $this->Inscription->save($fields);
        $this->redirect(array('action' => 'index'));
    }
    
    public function assignerEquipeJoueur($idJoueur, $equipe=0) {
        $this->loadModel('Inscription');
        $rs = $this->Inscription->findByIdjoueurAndSaison($idJoueur,date('Y'));
        
        $this->Inscription->id = $rs['Inscription']['id'];
        $this->Inscription->saveField('idEquipe',$equipe);
        $this->redirect(array('action' => 'index'));
    }
    
    function selectCategories()
    {
        $this->loadModel('Categorie');
        $rs = $this->Categorie->find('all');
           //var_dump($rs);
        $select = array();
        foreach($rs as $cat) :
            $select[$this->root.'/joueurs/changerCategorie/'.$cat['Categorie']['id']] = $cat['Categorie']['NomCategorie'];
        endforeach;
        
        return $select;
    }*/
    
    private function listerCategories() {
        
        $this->loadModel('Categorie');
        $liste = $this->Categorie->find('list', array(
            'fields' => array('Id', 'NomCategorie')
        ));
    
        return $liste;
    }

    private function listerClasses($data)
    {
        $rs = array();
        if ($data['IdCategorie'] != null) {
            $this->loadModel('VueEquipe');
            $rs = $this->VueEquipe->find('list', array(
                                        'fields' => array('Classe', 'Classe'),
                                        'conditions' => array('IdCategorie' => $data['IdCategorie'],
                                                              'IdAssociation' => 2)
                ));
        }
        return $rs;
    }
    
    private function listerNomEquipes($data)
    {
        $rs = array();
        if ($data['IdCategorie'] != null && $data['Classe'] != "") {
            $this->loadModel('VueEquipe');
            $rs = $this->VueEquipe->find('list', array(
                                        'fields' => array('NomEquipe','NomEquipe'),
                                        'conditions' => array(
                                                    'IdCategorie' => $data['IdCategorie'],
                                                    'Classe' => $data['Classe'],
                                                    'IdAssociation' => 2)
            ));  
        }
        return $rs;
    }

    private function listerEquipes($data)
    {
        $rs = array();
        if ($data['NomCategorie'] != "" && $data['Classe'] != "") {
            $this->loadModel('VueEquipe');
            $rs = $this->VueEquipe->find('list', array(
                                        'fields' => array('idEquipe','NomEquipe'),
                                        'conditions' => array(
                                                    'NomCategorie' => $data['NomCategorie'],
                                                    'Classe' => $data['Classe'],
                                                    'IdAssociation' => 2)
            ));  
        }
        return $rs;
    }
    
    public function listeCourriels() {
        
        if(!$this->request->is('post','put')) {
            $this->request->data['Joueur']['rallyecap'] = 1;
            $this->request->data['Joueur']['atome'] = 2;
            $this->request->data['Joueur']['moustique'] = 3;
            $this->request->data['Joueur']['peewee'] = 4;
            $this->request->data['Joueur']['bantam'] = 5;
            $this->request->data['Joueur']['midget'] = 6;
        }
        
        $this->validerRole('admin');
        //var_dump($this->request->data['Joueur']);
        $rs = $this->Joueur->getListeCourriels($this->request->data['Joueur']);
        
        $liste = '';
        foreach($rs as $adr):
            $liste.= $adr[0]['Courriel'].'; ';
        endforeach;
        
        $this->set(compact('liste'));
    }
    
    private function creerListeCourriel($categorie=null, $classe=null, $equipe=null) {
        
        $this->loadModel('Joueur');
        if($categorie == null) {
            $categorie = 'c.NomCategorie';
        } else {
            $categorie = '"'.$categorie.'"';
        }
         
        if($classe == null) {
            $classe = 'IFNULL(I.Classe,"")';
        } else {
            $classe = '"'.$classe.'"';
        }
        
        if($equipe == null) {
            $equipe = 'IFNULL(e.NomEquipe,"")';
        } else {
            $equipe = '"'.$equipe.'"';
        }
        $rs = $this->Joueur->getListeCourrielsParCat($categorie, $classe, $equipe);
        
        $liste = '';
        foreach($rs as $adr):
            $liste.= $adr[0]['Courriel'].'; ';
        endforeach;
        
        return $liste;
    } 
    
    public function listerTaillesChandail() {
        
        $chandails = array(
            'YS' => 'Enfant / Small',
            'YM' => 'Enfant / Medium',
            'YL' => 'Enfant / Large',
            'YXL' => 'Enfant / X-Large',
            'AXS' => 'Adulte / X-Small',
            'AS' => 'Adulte / Small',
            'AM' => 'Adulte / Medium',
            'AL' => 'Adulte / Large',
            'AXL' => 'Adulte / X-Large',
            'AXXL' => 'Adulte / XX-Large');
    
        $this->set('chandail',$chandails);
    }
    
    public function listerModesPaiement() {
        $liste = array('1' => 'Argent comptant',
                        '2' => '1 chèque',
                        '3' => '2 chèques',
                        '4' => 'Transfert bancaire');
        
        return $liste;
    }
    
    function categorieParDefaut($joueur) {
        $annee = $this->getConfigValueByName('Annee inscription');
        $anneeNaissance = date('Y',strtotime($joueur['DateNaissance']));
        $age = $annee - $anneeNaissance;
        if($age >= 5 && $age <=7) {
            return 1;
        } elseif($age == 8 || $age == 9) {
            return 2;
        } elseif($age == 10 || $age == 11) {
            return 3;
        } elseif($age == 12 || $age == 13) {
            return 4;
        } elseif($age == 14 || $age == 15) {
            return 5;
        } elseif($age == 16 || $age == 17) {
            return 6;
        }
    }
}
