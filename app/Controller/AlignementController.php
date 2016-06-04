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
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'PHPExcel.php'));
App::import('Vendor', 'PHPExcel_IOFactory', array('file' => 'PHPExcel'.DS.'IOFactory.php'));

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AlignementController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        public $minutes15 = array('00','15','30','45');
        public $helpers = array('PhpExcel'); 

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
    
    public function validerEquipe($idEquipe) {
        if($this->Session->read('Equipe.id') != $idEquipe) {
            $this->Session->setFlash(__("Vous n'avez pas accès à cette page"));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
    
    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->Session->setFlash(__("Vous n'avez pas accès à cette page"));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }

    public function gestion() {
        if($this->request->is('post', 'put')) {
            for($m=1; $m<=6; $m++) {
                if(isset($this->request->data['Alignement']['Manche'.$m])) {
                    $this->request->data['Alignement']['Manche'] = $m;
                    $this->request->data['Alignement']['Position'] = $this->request->data['Alignement']['Manche'.$m];
                    $this->assignerPositionDefensive($this->request->data);
                }
            }
            
            $this->request->data = null;
        }
        
        $this->set('listeParties',$this->listerParties());
        
        //s'il n'y a pas de partie à venir
        if($this->Session->read('Partie.id') == null) {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        $this->loadModel('VuePartie');
        $partie = $this->VuePartie->getPartieEntraineur($this->Session->read('Partie.id'),
                                                        $this->Session->read('User.idParent'));
        $this->set('partie',$partie[0]);

        $this->loadModel('VueAlignement');
        $count = $this->VueAlignement->find('count',array('conditions' => array(
                                        'IdPartie' => $this->Session->read('Partie.id'),
                                        'IdEquipe' => $this->Session->read('Equipe.id'),
                                        'Statut' => '0'
        )));

        if($count > 0) {
            $presents = $this->VueAlignement->find('all',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'Statut' => '0'),
                                            'order' => array('NomCompletJoueur' => 'asc')
            ));
            $this->set('presents',$presents);

            $absents = $this->VueAlignement->find('all',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'Statut' => '1'),
                                            'order' => array('NomCompletJoueur' => 'asc')
            ));
            $this->set('absents',$absents);
            
            $remplacants = $this->VueAlignement->find('all',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'Statut' => '2'),
                                            'order' => array('NomCompletJoueur' => 'asc')
            ));
            $this->set('remplacants',$remplacants);
            
            $entraineurs = $this->VueAlignement->find('all',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'Statut' => array('98','99')),
                                            'order' => array('Statut' => 'asc',
                                                             'TitreEntraineur' => 'asc',
                                                             'NomCompletEnt' => 'asc')
            ));
            $this->set('entraineurs',$entraineurs);

            $this->set('existe',true);

        } else {
            $this->set('existe',false);
        }
        
        if($this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes', $this->requestAction('equipes/listerEquipesEntraineur'));
        }
    }

    public function frappeurs() {

        if($this->request->is('Post', 'put')) {
            $this->assignerFrappeur($this->request->data);
            if(isset($this->request->data['Alignement']['Position'])) {
                $this->request->data['Alignement']['Manche'] = 1;
                $this->assignerPositionDefensive($this->request->data);
            }
            
            $this->request->data = null;
        }
        
        $this->set('listeParties',$this->listerParties());

        //s'il n'y a pas de partie à venir
        if($this->Session->read('Partie.id') == null) {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        $this->loadModel('VuePartie');
        $partie = $this->VuePartie->getPartieEntraineur($this->Session->read('Partie.id'),
                                                        $this->Session->read('User.idParent'));
        $this->set('partie',$partie[0]);
        
        //trouver le nombre de joueur présent avec les remplaçants
        $this->loadModel('VueAlignement');
        $this->loadModel('Alignement');
        $count = $this->Alignement->find('count',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'IdJoueur > ' => 0
        )));
        
        if($count > 0) {
            //lister les joueurs
            $lstJoueurs = $this->VueAlignement->find('list',array(
                                                'fields' => array('IdJoueur', 'NomCompletJoueur'),
                                                'conditions' => array(
                                                    'IdPartie' => $this->Session->read('Partie.id'),
                                                    'IdEquipe' => $this->Session->read('Equipe.id'),
                                                    'Statut' => array('0','2')),
                                                'order' => array('NomCompletJoueur' => 'asc',
                                                                 'Statut' => 'asc')
                ));
        }
        
        $this->set('count',$count);
        
        $liste = array();
        for($i = 1; $i <= $count; $i++) {
            $rs = null;
            $rs = $this->VueAlignement->find('first',array('conditions' => array(
                                                    'IdPartie' => $this->Session->read('Partie.id'),
                                                    'IdEquipe' => $this->Session->read('Equipe.id'),
                                                    'Ordre' => $i
            )));
            if(!empty($rs)) {
                $liste[$i] = $rs;
                //drop down des positions partantes
                $liste[$i]['Select']['Position'] = $this->listerPositions($rs['VueAlignement'],$count);
            } else {
                $vide = array();
                $vide['VueAlignement']['IdJoueur'] = 0;
                $liste[$i] = $vide;
            }
            
            $liste[$i]['Select']['Joueur'] = $lstJoueurs; 
        }

        $this->set('alignements',$liste);
        
        //liste des joueurs disponibles
        $dispo = $this->VueAlignement->find('all',array('conditions' => array(
                                                    'IdPartie' => $this->Session->read('Partie.id'),
                                                    'IdEquipe' => $this->Session->read('Equipe.id'),
                                                    'IFNULL(Ordre,0)' => 0,
                                                    'Statut' => array(0,2),
                                                    'IdJoueur >' => 0
        )));
        //var_dump($dispo);
        $this->set('listeDispo',$dispo);
        
        if($this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes', $this->requestAction('equipes/listerEquipesEntraineur'));
        }
    }

    public function defensive() {
        
        if($this->request->is('Post', 'put')) {
            for($m=1; $m<=6; $m++) {
                if(isset($this->request->data['Alignement']['Position'])) {
                    $this->assignerPositionDefensive($this->request->data);
                }
            }
            
            $this->request->data = null;
        }
        
        $this->set('listeParties',$this->listerParties());

        //s'il n'y a pas de partie à venir
        if($this->Session->read('Partie.id') == null) {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        $this->loadModel('VuePartie');
        $partie = $this->VuePartie->getPartieEntraineur($this->Session->read('Partie.id'),
                                                        $this->Session->read('User.idParent'));
        $this->set('partie',$partie[0]);
        
        $this->loadModel('VueAlignement');
        $count = $this->VueAlignement->find('count',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'Statut' => array('0','2')
        )));
        $rs = $this->VueAlignement->find('all',array('conditions' => array(
                                                'IdPartie' => $this->Session->read('Partie.id'),
                                                'IdEquipe' => $this->Session->read('Equipe.id'),
                                                'Statut' => array('0','2'),
                                                'Ordre >' => 0),
                                            'order' => array('Ordre' => 'asc')
        ));
        foreach($rs as $cle => $joueur) {
            for($i=1; $i<=6; $i++) {
                $rs[$cle]['Select'][$i] = $this->listerPositions($joueur['VueAlignement'],$count,$i,'defensive');
            }
        }
        $this->set('alignements',$rs);
        
        if($this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes', $this->requestAction('equipes/listerEquipesEntraineur'));
        }
    }
    
    public function ajouterAlignement() {
        $this->loadModel('Inscription');
        $joueurs = $this->Inscription->findAllByIdequipe($this->Session->read('Equipe.id'));
        //var_dump($joueurs);
        
        $this->loadModel('Alignement');
        foreach($joueurs as $joueur) {
            $this->Alignement->create();
            $a['Alignement']['IdPartie'] = $this->Session->read('Partie.id');
            $a['Alignement']['IdEquipe'] = $this->Session->read('Equipe.id');
            $a['Alignement']['IdJoueur'] = $joueur['Inscription']['IdJoueur'];
            $a['Alignement']['Statut'] = 0;
            $a['Alignement']['DateCreation'] = date('Y-m-d H:i:s');
            $a['Alignement']['DerniereModif'] = date('Y-m-d H:i:s');
            $a['Alignement']['ModifParUsager'] = $this->Auth->User('id');
            $this->Alignement->save($a);
            $a = array();
        }

        if(!empty($joueurs)) {
            $this->loadModel('Entraineur');
            $ent = $this->Entraineur->findAllByIdequipe($this->Session->read('Equipe.id'));

            foreach($ent as $entraineur) {
                $this->Alignement->create();
                $a['Alignement']['IdPartie'] = $this->Session->read('Partie.id');
                $a['Alignement']['IdEquipe'] = $this->Session->read('Equipe.id');
                $a['Alignement']['IdParent'] = $entraineur['Entraineur']['IdParent'];
                $a['Alignement']['Statut'] = 98;
                $a['Alignement']['DateCreation'] = date('Y-m-d H:i:s');
                $a['Alignement']['DerniereModif'] = date('Y-m-d H:i:s');
                $a['Alignement']['ModifParUsager'] = $this->Auth->User('id');
                $this->Alignement->save($a);
            }
        }

        $this->redirect(array('action' => 'gestion'));
    }
    
    public function listerParties() {
        $this->loadModel('Partie');
        $this->loadModel('VuePartie');
        
        $rs = $this->Partie->find('first',array(
                                    'conditions' => array(
                                        'id' => $this->Session->read('Partie.id'),
                                        'OR' => array('idEquipeVisiteur' => $this->Session->read('Equipe.id'),
                                                      'idEquipeReceveur' => $this->Session->read('Equipe.id')
            ))));
        
        if(empty($rs)) {
            $this->Session->write('Partie.id', null);
        }

        $rs = $this->VuePartie->find('list',array(
                                    'fields' => array('idPartie', 'DropDownPartieDesc'),
                                    'conditions' => array(
                                        'date >=' => date('Y-m-d'),
                                        'OR' => array('idEquipeVisiteur' => $this->Session->read('Equipe.id'),
                                                      'idEquipeReceveur' => $this->Session->read('Equipe.id')
                                        )),
                                    'order' => array('date','heure')
        ));
        
        if(!empty($rs) && $this->Session->read('Partie.id') == null) {
            //trouver prochaine partie si aucune sélectionnée
            reset($rs);
            $this->Session->write('Partie.id',key($rs));
        }

        return $rs;
    }
    
    function listerPositions($joueur, $nbJoueurs) {
        $nbPositions = ($joueur['IdCategorie'] == 2 && $joueur['Classe'] == 'B') ? 6 : 9;
          
        $select = array();
        for ($i=1; $i <= $nbPositions; $i++) {
            $select[$i] = $i;
        }
        if($nbJoueurs > $nbPositions) {
            $select[0] = 'Banc';
        }
        return $select;
    }
    
    public function listerEquipesEntraineur($source) {
        $this->loadModel('VueEntraineur');
        $rs = $this->VueEntraineur->find('all',array('conditions' => array(
                                                'Idusager' => $this->Auth->User('id'),
                                                'IdCategorie > ' => '1'
        )));   

        $liste = array();
        foreach($rs as $equipe):
            $liste['changerEquipe/'.$equipe['VueEntraineur']['IdEquipe'].'/'.$source] = $equipe['VueEntraineur']['NomCompletEquipe'];
        endforeach;

        return $liste;
    }
    
/********************************************
 * section alignement
 *******************************************/
    public function assignerFrappeur($data)
    {	
        //enlever ancien joueur à cette ordre
        $this->loadModel('Alignement');
        $joueur = $this->Alignement->find('first',array('fields' => 'id', 'conditions' => array(
                                            'IdPartie' => $this->Session->read('Partie.id'),
                                            'IdEquipe' => $this->Session->read('Equipe.id'),
                                            'Ordre' => $data['Alignement']['Ordre']
            )));
        
        if(!empty($joueur)) {
            $this->Alignement->id = $joueur['Alignement']['id'];
            $this->Alignement->save(array('Ordre' => '0',
                                          'DerniereModif' => date('Y-m-d H:i:s')
            ));
        }
        
        //assigner nouveau frappeur
        $joueur2 = $this->Alignement->find('first',array('fields' => 'id', 'conditions' => array(
                                        'IdPartie' => $this->Session->read('Partie.id'),
                                        'IdEquipe' => $this->Session->read('Equipe.id'),
                                        'IdJoueur' => $data['Alignement']['IdJoueur']
            )));
        if(!empty($joueur2)) {
            $this->Alignement->id = $joueur2['Alignement']['id'];
            $this->Alignement->save(array('Ordre' => $data['Alignement']['Ordre'],
                                          'DerniereModif' => date('Y-m-d H:i:s')                   
            ));
        }
        
        return 0;
    }

    public function monterFrappeur($id,$ordre)
    {	
        $this->loadModel('Alignement');
        //trouver le id de la ligne une position plus basse
        $rs = $this->Alignement->find('first',array('fields' => 'id', 'conditions' => array(
                                        'IdPartie' => $this->Session->read('Partie.id'),
                                        'IdEquipe' => $this->Session->read('Equipe.id'),
                                        'Ordre' => ($ordre -1)
        )));
        
        //descendre ce joueur
        if(!empty($rs)) {
            $this->Alignement->id = $rs['Alignement']['id'];
            $this->Alignement->save(array('Ordre' => $ordre,
                                          'DerniereModif' => date('Y-m-d H:i:s')
            ));
        }
        
        //monter le joueur
        $this->Alignement->id = $id;
        $this->Alignement->save(array('Ordre' => ($ordre - 1),
                                            'DerniereModif' => date('Y-m-d H:i:s')
        ));
        
        $this->redirect(array('action' => 'frappeurs'));
    }
    
    public function descendreFrappeur($id,$ordre)
    {	
        $this->loadModel('Alignement');
        //trouver le id de la ligne une position plus basse
        $rs = $this->Alignement->find('first',array('fields' => 'id', 'conditions' => array(
                                        'IdPartie' => $this->Session->read('Partie.id'),
                                        'IdEquipe' => $this->Session->read('Equipe.id'),
                                        'Ordre' => ($ordre + 1)
        )));
        
        //remonter ce joueur
        if(!empty($rs)) {
            $this->Alignement->id = $rs['Alignement']['id'];
            $this->Alignement->save(array('Ordre' => $ordre,
                                          'DerniereModif' => date('Y-m-d H:i:s')
            ));
        }

        //descendre le joueur
        $this->Alignement->id = $id;
        $this->Alignement->save(array('Ordre' => ($ordre + 1),
                                            'DerniereModif' => date('Y-m-d H:i:s')
        ));
        
        $this->redirect(array('action' => 'frappeurs'));
    }
    
    public function changerStatut($id,$statut)
    {	
        $this->loadModel('Alignement');
        $rs = $this->Alignement->findById($id);
        
        $this->ValiderEquipe($rs['Alignement']['IdEquipe']);
        
        $align = array();
        $align['Alignement']['Statut'] = $statut;
        $align['Alignement']['Ordre'] = 0;
        $align['Alignement']['DerniereModif'] = date('Y-m-d H:i:s');
        $align['Alignement']['ModifParUsager'] = $this->Auth->User('id');
        
        $this->Alignement->id = $id;
        $this->Alignement->save($align);
        
        $this->redirect(array('action' => 'gestion'));
    }
    
    private function assignerPositionDefensive($data)
    {	
        $this->loadModel('Defensive');
        //var_dump($data);
        //chercher le joueur présentement à cette position si position sur le terrain
        if($data['Alignement']['Position'] > 0) {
            $rs2 = $this->Defensive->find('first',array('conditions' => array(
                                        'IdPartie' => $this->Session->read('Partie.id'),
                                        'IdEquipe' => $this->Session->read('Equipe.id'),
                                        'Position' => $data['Alignement']['Position'],
                                        'Manche' => $data['Alignement']['Manche']
                )));
            
            //si existe, enlever la position de ce joueur
            if(!empty($rs2)) {
                $def['Defensive']['Position'] = 0;
                $def['Defensive']['DerniereModif'] = date('Y-m-d H:i:s');
                $def['Defensive']['ModifParUsager'] = $this->Auth->User('id');
                
                $this->Defensive->id = $rs2['Defensive']['id'];
                $this->Defensive->save($def);
            }
        }
        
        //chercher le joueur pour une manche
        $rs = $this->Defensive->find('first',array('conditions' => array(
                                        'IdPartie' => $this->Session->read('Partie.id'),
                                        'IdEquipe' => $this->Session->read('Equipe.id'),
                                        'IdJoueur' => $data['Alignement']['IdJoueur'],
                                        'Manche' => $data['Alignement']['Manche']
        )));
        
        $def['Defensive']['IdPartie'] = $this->Session->read('Partie.id');
        $def['Defensive']['IdEquipe'] = $this->Session->read('Equipe.id');
        $def['Defensive']['Manche'] = $data['Alignement']['Manche'];
        $def['Defensive']['IdJoueur'] = $data['Alignement']['IdJoueur'];
        $def['Defensive']['Position'] = $data['Alignement']['Position']; 
        $def['Defensive']['DerniereModif'] = date('Y-m-d H:i:s');
        $def['Defensive']['ModifParUsager'] = $this->Auth->User('id');
        
        if(!empty($rs)) {
            $this->Defensive->id = $rs['Defensive']['id'];
        } else {
            $def['Defensive']['DateCreation'] = date('Y-m-d H:i:s'); 
        }
        
        $this->Defensive->save($def);
        
        return 0;
    }
    
    public function feuilleAlignement($idPartie,$idEquipe) {
        $this->loadModel('VuePartie');
        $partie = $this->VuePartie->getPartieEntraineur($idPartie,
                                                        $this->Session->read('User.idParent'));
        
        $this->loadModel('VueAlignement');
        $joueurs = $this->VueAlignement->find('all',array('conditions' => array(
                                                'idPartie' => $idPartie,
                                                'idEquipe' => $idEquipe,
                                                'statut' => array(0,2)),
                                            'order' => array('ordre' => 'asc')
        ));
        
        $this->loadModel('VueAlignement');
        $entraineurs = $this->VueAlignement->find('all',array('conditions' => array(
                                                'idPartie' => $idPartie,
                                                'idEquipe' => $idEquipe,
                                                'statut' => 98),
                                            'order' => array('TitreEntraineur' => 'asc', 'NomCompletEnt' => 'asc')
        ));
        
        $this->traitementFeuilleExcel($partie[0],$joueurs,$entraineurs);
            
        $this->redirect(array('action' => 'gestion'));
    }
    
    private function traitementFeuilleExcel($partie,$joueurs,$entraineurs) {
        if(!empty($partie) && is_readable('files/feuille_alignement.xls')) {
            error_reporting(E_ALL);
            
            $fileType = 'Excel5';
            $fileName = 'files/feuille_alignement.xls';

            // Read the file
            $objReader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $objReader->load($fileName);

            // Change the file
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A3', $partie['P']['NoPartie'])
                        ->setCellValue('C3', $partie['P']['DateFormat'])
                        ->setCellValue('D3', $partie[0]['XVisiteur'])
                        ->setCellValue('E3', $partie[0]['XReceveur'])
                        ->setCellValue('A5', $partie[0]['NomEquipe'])
                        ->setCellValue('A7', $partie['P']['NomCategorie'])
                        ->setCellValue('D7', $partie['P']['Classe'])
                        ->setCellValue('A9', $partie['P']['NomLigue'])
                        ->setCellValue('A11', iconv("ISO-8859-1","UTF-8",$partie[0]['NomOpposant']))
            
                        ->setCellValue('G3', $partie['P']['NoPartie'])
                        ->setCellValue('I3', $partie['P']['DateFormat'])
                        ->setCellValue('J3', $partie[0]['XVisiteur'])
                        ->setCellValue('K3', $partie[0]['XReceveur'])
                        ->setCellValue('G5', $partie[0]['NomEquipe'])
                        ->setCellValue('G7', $partie['P']['NomCategorie'])
                        ->setCellValue('J7', $partie['P']['Classe'])
                        ->setCellValue('G9', $partie['P']['NomLigue'])
                        ->setCellValue('G11', iconv("ISO-8859-1","UTF-8",$partie[0]['NomOpposant']))
            
                        ->setCellValue('M3', $partie['P']['NoPartie'])
                        ->setCellValue('O3', $partie['P']['DateFormat'])
                        ->setCellValue('P3', $partie[0]['XVisiteur'])
                        ->setCellValue('Q3', $partie[0]['XReceveur'])
                        ->setCellValue('M5', $partie[0]['NomEquipe'])
                        ->setCellValue('M7', $partie['P']['NomCategorie'])
                        ->setCellValue('P7', $partie['P']['Classe'])
                        ->setCellValue('M9', $partie['P']['NomLigue'])
                        ->setCellValue('M11', iconv("ISO-8859-1","UTF-8",$partie[0]['NomOpposant']));
            
            $cmpt = 13;
            foreach($joueurs as $joueur) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$cmpt, $joueur['VueAlignement']['Numero'])
                        ->setCellValue('B'.$cmpt, iconv("ISO-8859-1","UTF-8",$joueur['VueAlignement']['NomCompletJoueur']))
                        ->setCellValue('E'.$cmpt, $joueur['VueAlignement']['Manche1'])
                
                        ->setCellValue('G'.$cmpt, $joueur['VueAlignement']['Numero'])
                        ->setCellValue('H'.$cmpt, iconv("ISO-8859-1","UTF-8",$joueur['VueAlignement']['NomCompletJoueur']))
                        ->setCellValue('K'.$cmpt, $joueur['VueAlignement']['Manche1'])
                
                        ->setCellValue('M'.$cmpt, $joueur['VueAlignement']['Numero'])
                        ->setCellValue('N'.$cmpt, iconv("ISO-8859-1","UTF-8",$joueur['VueAlignement']['NomCompletJoueur']))
                        ->setCellValue('Q'.$cmpt, $joueur['VueAlignement']['Manche1']);
                $cmpt++;
            }
            
            $cmpt = 32;
            foreach($entraineurs as $ent) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$cmpt, $ent['VueAlignement']['NumeroEnt'])
                        ->setCellValue('B'.$cmpt, iconv("ISO-8859-1","UTF-8",$ent['VueAlignement']['NomCompletEnt']))

                        ->setCellValue('G'.$cmpt, $ent['VueAlignement']['NumeroEnt'])
                        ->setCellValue('H'.$cmpt, iconv("ISO-8859-1","UTF-8",$ent['VueAlignement']['NomCompletEnt']))

                        ->setCellValue('M'.$cmpt, $ent['VueAlignement']['NumeroEnt'])
                        ->setCellValue('N'.$cmpt, iconv("ISO-8859-1","UTF-8",$ent['VueAlignement']['NomCompletEnt']));
                $cmpt++;
            }
            
            //positions défensives
            $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue('A1', iconv("ISO-8859-1","UTF-8",$partie['P']['DropDownPartieDesc'].' - '.$partie['P']['NomCategorie'].' '.$partie['P']['Classe']));
            
            $cmpt = 4;
            foreach($joueurs as $joueur) {
                $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue('A'.$cmpt, $joueur['VueAlignement']['Ordre'])
                        ->setCellValue('B'.$cmpt, $joueur['VueAlignement']['Numero'])
                        ->setCellValue('C'.$cmpt, iconv("ISO-8859-1","UTF-8",$joueur['VueAlignement']['Prenom'].' '.$joueur['VueAlignement']['NomFamille']))
                        ->setCellValue('D'.$cmpt, $joueur['VueAlignement']['Manche1'])
                        ->setCellValue('E'.$cmpt, $joueur['VueAlignement']['Manche2'])
                        ->setCellValue('F'.$cmpt, $joueur['VueAlignement']['Manche3'])
                        ->setCellValue('G'.$cmpt, $joueur['VueAlignement']['Manche4'])
                        ->setCellValue('H'.$cmpt, $joueur['VueAlignement']['Manche5'])
                        ->setCellValue('I'.$cmpt, $joueur['VueAlignement']['Manche6']);
                $cmpt++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            // Write the file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
            //$objWriter->save($fileName);
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="alignement.xls"');
            header('Cache-Control: max-age=0');
            
            $objWriter->save("php://output");
        }
    }

}
