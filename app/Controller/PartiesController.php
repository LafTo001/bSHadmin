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
class PartiesController extends AppController {

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
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('horaire'); 
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

/*
 * Utilisé dans la section des arbitres
 */
    public function jour($date=null) {
        if($this->request->is(array('post', 'put'))) {
            $this->loadModel('Partie');
            $this->Partie->save($this->request->data);
        }
        
        if($date == null) {
            $date = date('Y-m-d');
        }

        $this->loadModel('VuePartie');
        $parties = $this->VuePartie->find('all',array('conditions' => array(
                           'Locale' => 1,
                           'Date' => $date),
                           'order' => array('Datetime' => 'asc','NomTerrain' => 'asc')
        ));
        if($this->Session->read('User.role') == 'arbitre') {
            foreach($parties as $cle => $partie) {
                $parties[$cle]['Select']['Arbitre'] = $this->listerArbitres(1);
                $parties[$cle]['Select']['Marqueur'] = $this->listerArbitres(2);
            }
        }
        $this->set('parties',$parties);

        $this->loadModel('VueDate');
        $rs = $this->VueDate->findByDate($date);
        $this->set('date',$date);
        $this->set('dateFormat',$rs['VueDate']['DateFormat']);
    }
    
    public function resultats($id) {
        
        if($this->request->is('post', 'put')) {
            $this->loadModel('Partie');
            $this->Partie->id = $id;
            $this->Partie->save($this->request->data);
            
            $this->processClassement($id);
            
            if($this->Session->read('User.role') == 'admin') {
                $this->redirect(array('action' => 'jour', $this->request->data['Partie']['Date']));
            }
            $this->redirect(array('action' => 'horaire'));
        }
        
        $this->loadModel('VuePartie');
        $partie = $this->VuePartie->findByIdpartie($id);
        
        $this->loadModel('Entraineur');
        $rs = $this->Entraineur->findByIdequipeAndIdparent($partie['VuePartie']['IdEquipeReceveur'], 
                                                           $this->Auth->User('IdParent'));
        
        $statuts = array('0' => '',
                         '1' => 'Annulée',
                         '2' => 'Complétée');
        
        if(empty($rs) && $this->Session->read('User.role') != 'admin') {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        $this->set(compact('statuts'));
        $this->request->data['Partie'] = $partie['VuePartie'];
    }

    public function ajouter($idLigue=null) {

        if(isset($this->request->data['Envoyer']) && $this->request->data['Envoyer'] == 'Submit') {
            if($this->request->data['Partie']['parties'] != '') {
                if($this->request->data['Partie']['IdLigue'] == 5 ) { //ligue AA
                    $this->ajouterAA($this->request->data);
                } else {  //données venant d'un fichier Excel avec template
                    $this->enregistrerParties($this->request->data);
                }
            }
            //préparer le mapping de la partie
            $this->request->data['Partie']['DateTime'] = $this->request->data['Partie']['Date'].' '.$this->request->data['Partie']['Heure'].':00';

            $this->request->data['Partie']['Locale'] = 0;
            if(isset($this->request->data['Partie']['IdTerrain'])) {
                $this->loadModel('Terrain');
                $terrain = $this->Terrain->findById($this->request->data['Partie']['IdTerrain']);
                $this->request->data['Partie']['Locale'] = ($terrain['Terrain']['IdAssociation'] == 2) ? 1 : 0;
                $this->request->data['Partie']['NomTerrain'] = $terrain['Terrain']['NomTerrain'];
            }

            if($this->request->data['Partie']['NomEquipeVisiteur'] == '') {
                $equipe = $this->getEquipe($this->request->data['Partie']['IdEquipeVisiteur']);
                $this->request->data['Partie']['NomEquipeVisiteur'] = $equipe['VueEquipe']['VilleNomEquipe'];
            }

            if($this->request->data['Partie']['NomEquipeReceveur'] == '') {
                $equipe = $this->getEquipe($this->request->data['Partie']['IdEquipeReceveur']);
                $this->request->data['Partie']['NomEquipeReceveur'] = $equipe['VueEquipe']['VilleNomEquipe'];
            }

            //var_dump($this->request->data);
            $this->mapPartie($this->request->data);
            if($this->Session->read('User.role') == 'admin') {
                $this->redirect(array('action' => 'jour',$this->request->data['Partie']['Date']));
            } else {
                $this->redirect(array('controller' => 'tournois', 'action' => 'parties',$idLigue));
            }
        }
        
        if(!$this->request->is(array('post','put'))) {
            $this->request->data['Partie']['IdLigue'] = 17;
            $this->request->data['Partie']['IdCategorie'] = 3;
            $this->request->data['Partie']['Classe'] = 'B';
            $this->request->data['Partie']['IdTerrain'] = 103;
            $this->request->data['Partie']['Date'] = date('Y-m-d');
            $this->request->data['Partie']['Heure'] = '19h00';
        }
        
        if($this->Session->read('User.role') == 'admin') {
            $this->set('lstLigues', $this->listerLigues());
            $this->set('lstTerrains',$this->listerTerrains());
        } else {
            $this->loadModel('VueTournoi');
            $tournoi = $this->VueTournoi->findByIdligue($idLigue);
            $this->set('tournoi',$tournoi);
        }
        
        $this->loadModel('Ligue');
        $ligue = $this->Ligue->findById($this->request->data['Partie']['IdLigue']);
        
        $this->set('lstCategories',$this->listerCategories($ligue['Ligue']['NomCourtLigue']));
        $this->set('lstClasses',$this->listerClasses($ligue['Ligue']['NomCourtLigue'],$this->request->data['Partie']['IdCategorie']));
        $this->set('lstEquipes',$this->listerEquipes($this->request->data['Partie']));
        $this->set('lstHeures',$this->listerHeures());
        
        $this->Session->write('Terrain.id', $this->request->data['Partie']['IdTerrain']);
        $this->set('terrain',$this->requestAction('terrain/getTerrain'));
        
        $this->loadModel('VueDate');
        $date = $this->VueDate->findByDate($this->request->data['Partie']['Date']);
        $this->set('dateFormat',$date['VueDate']['DateFormat']);
    }
    
    private function ajouterAA($data) {
        $fichier = fopen('parties.txt', 'w+');
        fputs($fichier, $data['Partie']['parties']);
        fseek($fichier, 0);

        $ligne = fgets($fichier, 255);
        $ligne = explode(" ",trim($ligne));
        $this->loadModel('Date');
        $date = $this->Date->getDate($ligne);
        while(!feof($fichier))
        {
            $ligne = fgets($fichier, 255);
            if(substr($ligne,0,1) != "0")  {
                $ligne = explode(" ",trim($ligne));
                $date = $this->Date->getDate($ligne);
            } else {
                //001 - 12:30	Gouverneurs Noirs	-		-	Pirates AA	Landry (Iberville)
                $array = explode("\t",$ligne);
                $numeroHeure = explode(" - ",trim($array[0]));

                $partie = array();
                $partie['IdCategorie'] = intVal($data['Partie']['IdCategorie']);
                $partie['Classe'] = 'AA';
                $partie['IdLigue'] = intVal($data['Partie']['IdLigue']);
                $partie['NoPartie'] = intVal($numeroHeure[0]);
                $partie['DateTime'] = $date.' '.$numeroHeure[1].':00';  

                $partie['IdTerrain'] = 0;
                $partie['Locale'] = 0;
                $partie['NomTerrain'] = trim($array[6]);

                if(strpos($array[6], "(")) {
                    $terrain = explode(" (",$array[6]);
                    if(trim(str_replace(")","",$terrain[1])) == 'St-Hyacinthe') {
                        $partie['Locale'] = 1;
                        $partie['IdTerrain'] = $this->lookupTerrain($terrain[0],'St-Hyacinthe');
                        $partie['NomTerrain'] = $terrain[0];
                    }
                }

                $partie['NomEquipeVisiteur'] = trim($array[1]);
                $partie['NomEquipeReceveur'] = trim($array[5]);

                //trouver id équipe
                $partie['IdEquipeVisiteur'] = 0;
                $partie['IdEquipeReceveur'] = 0;

                if($partie['NomEquipeVisiteur'] == 'St-Hyacinthe' || substr($partie['NomEquipeVisiteur'],0,9) == 'Guerriers') {
                    $partie['IdEquipeVisiteur'] = $this->lookupEquipe($partie,$partie['NomEquipeVisiteur']);
                } 
                elseif($partie['NomEquipeReceveur'] == 'St-Hyacinthe' || 
                        $partie['NomEquipeReceveur'] == 'Richelieu-Yamaska' || 
                        substr($partie['NomEquipeReceveur'],0,9) == 'Guerriers') {
                    $partie['IdEquipeReceveur'] = $this->lookupEquipe($partie,$partie['NomEquipeReceveur']);
                }

                if($array[2] != '-') {
                    $partie['PointsVisiteur'] = $array[2];
                    $partie['PointsReceveur'] = $array[4];
                }

                $partieAMapper['Partie'] = $partie;
                $this->mapPartie($partieAMapper);
            }
        }

        return 0;

    }
    
    private function enregistrerParties($data) {
        
        //var_dump($data);
        $fichier = fopen('parties.txt', 'w+');
        fputs($fichier, $data['Partie']['parties']);
        fseek($fichier, 0);

        while(!feof($fichier))
        {
            $ligne = fgets($fichier, 255);
            
            if($ligne != "") {
                $array = explode("\t",$ligne);

                //Catégorie	Classe	No partie	Terrain	Date	Heure	Visiteur	Receveur
                $partie = array();
                $partie['IdCategorie'] = $this->lookupCategorie($array[0]);
                $partie['Classe'] = $array[1];
                $partie['IdLigue'] = intVal($data['Partie']['IdLigue']);
                $partie['NoPartie'] = $array[2];
                $partie['DateTime'] = $array[4].' '.str_replace("h", ":", $array[5]).':00';  

                $partie['IdTerrain'] = $this->lookupTerrain($array[3],'St-Hyacinthe');
                $partie['Locale'] = 1;
                $partie['NomTerrain'] = $array[3];

                $partie['NomEquipeVisiteur'] = 'St-Hyacinthe '.$array[6];
                $partie['NomEquipeReceveur'] = 'St-Hyacinthe '.$array[7];

                //trouver id équipe
                $partie['IdEquipeVisiteur'] = $this->lookupEquipe($partie, $partie['NomEquipeVisiteur']);
                $partie['IdEquipeReceveur'] = $this->lookupEquipe($partie, $partie['NomEquipeReceveur']);

                $partieAMapper['Partie'] = $partie;
                $this->mapPartie($partieAMapper);
            }
        }

        return 0;
    }
    
    public function horaire($nomCategorie='',$classe='',$nomEquipe='') {
        
        if($nomCategorie == '') {
            $this->validerRole('entraineur');
            $equipe = $this->getEquipe($this->Session->read('Equipe.id'));
        } elseif($classe != '' && $nomEquipe != '') {
            $this->loadModel('VueEquipe');
            $equipe = $this->VueEquipe->findByNomcategorieAndClasseAndNomequipe($nomCategorie,$classe,$nomEquipe);
            $this->layout = 'accueil';
        } else {
            $this->redirect(array('controller' => 'equipes', 'action' => 'index'));
        }
        $this->set(compact('nomCategorie','classe','equipe'));
        $this->set('nomEquipe',str_replace(" ","_",$nomEquipe));
        
        $this->loadModel('VuePartie');
        $parties = $this->VuePartie->find('all',array('conditions' => 
                                                        array('OR' => array('IdEquipeVisiteur' => $equipe['VueEquipe']['idEquipe'],
                                                                            'IdEquipeReceveur' => $equipe['VueEquipe']['idEquipe'])),
                                                    'order' => array('Datetime' => 'asc')
        ));
        $this->set(compact('parties'));
        
        if($this->Session->read('Equipe.Count') > 1) {
            $this->set('listeEquipes',$this->listerEquipesEntraineur('horaire'));
            $this->set('source','horaire');
        }
    }
    
    public function extrairePartiesLBAVR($idLigue=null) {
        $this->loadModel('Partie');
        $this->loadModel('Ligue');
        $ligue = $this->Ligue->findById($idLigue);
        
        if(empty($ligue)) {
            $this->Session->setFlash(__("Impossible d'extraire les parties, paramètres manquants"));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        
        if($idLigue == 2) {
            $coupeLabelle = $this->Ligue->findByNomcourtligue('Coupe Labelle');
        }

        $fichierS = fopen($ligue['Ligue']['CalendrierUrl'], 'r');
        $fichierD = fopen('parties.txt', 'w+');

        $char = array("<tr>","</tr>","<td>","</a>",'<tr class="rose">');

        while(!feof($fichierS))
        {
            $ligne = fgets($fichierS, 2000000);
            //echo $ligne;
            $array = explode("</tr>", $ligne);
            //var_dump($array);
            foreach($array as $ligne)
            {
                $ligne = trim($ligne);
                $ligne = str_replace("  ","",$ligne);
                if(substr($ligne, 0, 3) == '<tr')
                {
                    $ligne = str_replace($char, "", $ligne);
                    $array = explode("</td>", $ligne);
                    if(count($array) >= 9) {
                        $nomEquipeVisiteur = explode(" ", $array[5]);
                        $nomEquipeReceveur = explode(" ", $array[7]);

                        if($nomEquipeReceveur[0] == 'St-Hyacinthe' || $nomEquipeVisiteur[0] == 'St-Hyacinthe')
                        {					
                            $this->Partie->create();
                            $partie = array();

                            $partie['IdLigue'] = $ligue['Ligue']['id'];

                            //Catégorie 	# 	Jour 	Date 	Heure 	Visiteur 	Points 	Receveur 	Points 	Terrain
                            $division = explode(" ", $array[0]);
                            
                            if($idLigue == 2 && isset($division[2]) && 
                                    $division[2] == 'Coupe' && $division[3] == 'Labelle') {
                                $partie['IdLigue'] = $coupeLabelle['Ligue']['id'];
                            } else {
                                $partie['IdLigue'] = $ligue['Ligue']['id'];
                            }

                            $partie['IdCategorie'] = $this->lookupCategorie($division[0]);
                            $partie['Classe'] = $division[1];

                            $partie['NoPartie'] = $array[1];
                            $partie['DateTime'] = $array[3].' '.str_replace("h",":",$array[4]).':00';

                            $partie['NomEquipeVisiteur'] = iconv('utf-8','ISO-8859-1',$array[5]);
                            
                            $partie['IdEquipeVisiteur'] = $this->lookupEquipe($partie, $array[5]);
                            $partie['PointsVisiteur'] = ($array[6] == 'Pluie') ? -1 : str_replace('</span>','',str_replace('<span style="border-bottom: 2px solid red" title="Forfait">','',$array[6]));

                            $partie['NomEquipeReceveur'] = iconv('utf-8','ISO-8859-1',$array[7]);
                            $partie['Locale'] = ($nomEquipeReceveur[0] == 'St-Hyacinthe') ? 1 : 0; 

                            $partie['IdEquipeReceveur'] = $this->lookupEquipe($partie, $array[7]);
                            $partie['PointsReceveur'] = ($array[8] == 'Pluie') ? -1 : str_replace('</span>','',str_replace('<span style="border-bottom: 2px solid red" title="Forfait">','',$array[8]));

                            $partie['NomTerrain'] = iconv('utf-8','ISO-8859-1',substr($array[9],strpos($array[9],">")+1));
                            $partie['IdTerrain'] = $this->lookupTerrain($partie['NomTerrain'],$nomEquipeReceveur[0]);

                            $partieAMapper['Partie'] = $partie;
                            $this->mapPartie($partieAMapper);
                        }
                    }
                }
            }
        }
        
        if($this->Session->read('User.role') == 'admin') {
            $this->Session->setFlash(__("Les parties de la LBAVR ont été mises à jour."));
        }
        $this->redirect(array('controller' => 'pages', 'action' => 'home'));
    }

    /*public function mapParties($idLigue) {
        $this->loadModel('Partie');
        $this->loadModel('Ligue');
        $this->loadModel('PartieDataload');
        //$ligue = $this->Ligue->findById($idLigue);
        
        $rs = $this->PartieDataload->find('all');

        foreach($rs as $partie) {
            
            if($idLigue <= 2) {
                //$nomEquipeVisiteur = explode(" ", $partie['PartieDataload']['Visiteur']);
                $nomEquipeReceveur = explode(" ", $partie['PartieDataload']['Receveur']);

                $this->Partie->create();
                $partie = array();

                $partie['IdLigue'] = $idLigue;
                $division = explode(" ", $partie['PartieDataload']['Categorie']);

                $partie['IdCategorie'] = $this->lookupCategorie($division[0]);
                $partie['Classe'] = $division[1];

                $partie['NoPartie'] = $partie['PartieDataload']['NoPartie'];
                $partie['DateTime'] = $partie['PartieDataload']['Date'].' '.str_replace("h",":",$partie['PartieDataload']['Heure']).':00';

                $partie['NomEquipeVisiteur'] = $partie['PartieDataload']['Visiteur'];

                $partie['IdEquipeVisiteur'] = $this->lookupEquipe($partie, $partie['PartieDataload']['Visiteur']);
                $partie['PointsVisiteur'] = ($partie['PartieDataload']['PointsV'] == 'Pluie') ? -1 : $partie['PartieDataload']['PointsV'];

                $partie['NomEquipeReceveur'] = $partie['PartieDataload']['Receveur'];
                $partie['Locale'] = ($nomEquipeReceveur[0] == 'St-Hyacinthe') ? 1 : 0; 

                $partie['IdEquipeReceveur'] = $this->lookupEquipe($partie, $partie['PartieDataload']['Receveur']);
                $partie['PointsReceveur'] = ($partie['PartieDataload']['PointsR'] == 'Pluie') ? -1 : $partie['PartieDataload']['PointsR'];

                $partie['NomTerrain'] = $partie['PartieDataload']['Terrain'];
                $partie['IdTerrain'] = $this->lookupTerrain($partie['NomTerrain'],$nomEquipeReceveur[0]);

                $partieAMapper['Partie'] = $partie;
                $this->mapPartie($partieAMapper);
            }
        }
        
        $this->Session->setFlash(__("Les parties de la LBAVR ont été mises à jour, merci de votre patience."));
        $this->redirect(array('controller' => 'pages', 'action' => 'home'));
    }*/

    public function mapPartie($partie) {
        $this->loadModel('Partie');
        $p = $this->Partie->find('first',array('conditions' => array(
                                'IdCategorie' => $partie['Partie']['IdCategorie'],
                                'Classe' => $partie['Partie']['Classe'],
                                'NoPartie' => $partie['Partie']['NoPartie'],
                                'IdLigue' => $partie['Partie']['IdLigue'],
                                'YEAR(DateTime)' => date('Y')
        )));
        $this->Partie->id = null;

        if(!empty($p)) {
            $this->Partie->id = $p['Partie']['id'];
        } else {
            $partie['Partie']['DateCreation'] = date('Y-m-d H:i:s');
        }
        $partie['Partie']['DerniereModif'] = date('Y-m-d H:i:s');
        $partie['Partie']['ModifParUsager'] = 0;
        $partie['Partie']['DateSuppression'] = null;
        $this->Partie->save($partie);
        $partie['Partie']['IdPartie'] = $this->Partie->id;

        if($partie['Partie']['Locale'] == 1) {
            //enregistrer l'événement
            $this->mapEvenement($partie);
        }
    }
    
    public function mapEvenement($partie) {
        $event['Evenement']['IdTerrain'] = $partie['Partie']['IdTerrain'];
        $event['Evenement']['DebutEvenement'] = $partie['Partie']['DateTime'];
        $event['Evenement']['FinEvenement'] = $this->calculDateFin($partie['Partie']);
        $event['Evenement']['TypeEvenement'] = 1;
        $event['Evenement']['IdSerie'] = 0;
        $event['Evenement']['IdEquipe'] = $partie['Partie']['IdEquipeReceveur'];
        $event['Evenement']['IdEquipe2'] = $partie['Partie']['IdEquipeVisiteur'];
        $event['Evenement']['IdPartie'] = $partie['Partie']['IdPartie'];
        $event['Evenement']['Demandeur'] = 0;
        $event['Evenement']['Confirmation'] = 3; //authorisé par la ligue
            
        $this->loadModel('Evenement');
        $this->Evenement->create();
        $ancien = $this->Evenement->findByIdpartie($partie['Partie']['IdPartie']);
        
        //comparer ancien et nouveau, envoyer courriel si changement
        if($this->comparerEvenement($ancien,$event) == false) {
        
            if($ancien) {
                if($ancien['Evenement']['Confirmation'] == 3 || 
                        $ancien['Evenement']['DebutEvenement'] == $event['Evenement']['DebutEvenement']) {
                    $event['Evenement']['DerniereModif'] = date('Y-m-d H:i:s');
                    $this->Evenement->id = $ancien['Evenement']['id'];
                    $this->Evenement->save($event);
                }
            } else {
                $event['Evenement']['DateCreation'] = date('Y-m-d H:i:s');
                $event['Evenement']['DerniereModif'] = date('Y-m-d H:i:s');
                $this->Evenement->save($event);
            }
        }
    }
    
    private function processClassement($idPartie) {
        $this->loadModel('Classement');
        $this->loadModel('Partie');
        $partie = $this->Partie->findById($idPartie);
        
        $rs = $this->Partie->processClassement($partie['Partie']['IdEquipeReceveur'], $partie['Partie']['IdLigue']);
        $classR = $this->Classement->findByIdequipeAndIdligue($partie['Partie']['IdEquipeReceveur'], $partie['Partie']['IdLigue']);
        $rs[0]['Points'] = $rs[0]['Victoires'] * 2 + $rs[0]['Nulles'];
        $rs[0]['PlusMoins'] = $rs[0]['PtsPour'] - $rs[0]['PtsContre'];
        $this->Classement->id = null;
        if(!empty($classR)) {
            $this->Classement->id = $classR['Classement']['id'];
        }
        $this->Classement->save($rs[0]);
        
        $rs = $this->Partie->processClassement($partie['Partie']['IdEquipeVisiteur'], $partie['Partie']['IdLigue']);
        $classV = $this->Classement->findByIdequipeAndIdligue($partie['Partie']['IdEquipeVisiteur'], $partie['Partie']['IdLigue']);
        $rs[0]['Points'] = $rs[0]['Victoires'] * 2 + $rs[0]['Nulles'];
        $rs[0]['PlusMoins'] = $rs[0]['PtsPour'] - $rs[0]['PtsContre'];
        $this->Classement->id = null;
        if(!empty($classV)) {
            $this->Classement->id = $classV['Classement']['id'];
        }
        $this->Classement->save($rs[0]);
        
        return 0;
    }
    
    private function comparerEvenement($ancien,$nouveau) {
        if(!empty($ancien) && $ancien['Evenement']['Confirmation'] == 1 && 
            ($ancien['Evenement']['IdTerrain'] == $nouveau['Evenement']['IdTerrain'] && 
            $ancien['Evenement']['DebutEvenement'] == $nouveau['Evenement']['DebutEvenement'])) {
            
            $this->loadModel('Evenement');
            $this->Evenement->id = $ancien['Evenement']['id'];
            $this->Evenement->saveField('Confirmation',3);
            return true;
        }
        
        if(empty($ancien) || ($ancien['Evenement']['Confirmation'] == 3 &&
            ($ancien['Evenement']['IdTerrain'] != $nouveau['Evenement']['IdTerrain'] || 
            $ancien['Evenement']['DebutEvenement'] != $nouveau['Evenement']['DebutEvenement']))) {
            
            $this->loadModel('VuePartie');
            $partie = $this->VuePartie->findByIdpartie($nouveau['Evenement']['IdPartie']);

            $texte = '<html><p>Changement pour une partie</p>';
            $texte.= '<p>Équipe receveuse: '.$partie['VuePartie']['NomEquipeReceveur'].' ';
            $texte.= $partie['VuePartie']['NomCategorie'].' '.$partie['VuePartie']['Classe'].'<br/>';
            $texte.= '# Partie: '.$partie['VuePartie']['NoPartie'].'</p>';
            
            if(!empty($ancien)) {
                $terrain = $this->getTerrain($ancien['Evenement']['IdTerrain']);
                $ancienneDate = explode(" ", $ancien['Evenement']['DebutEvenement']);
                $texte.= '<p>Ancien terrain: '.$terrain['NomTerrain'].'<br/>';
                $texte.= 'Date: '.$ancienneDate[0].' à '.$ancienneDate[1].'</p>';
            }
            
            $texte.= '<p>Nouveau terrain: '.$partie['VuePartie']['NomTerrain'].'<br/>';
            $texte.= 'Date: '.$partie['VuePartie']['Date'].' à '.$partie['VuePartie']['Heure'].'</p>';

            $Email = new CakeEmail();
            $this->loadModel('Config');
            $adresse = $this->Config->findByNomconfig('Adresse changement partie');
            $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to($adresse['Config']['Valeur'])
                ->emailFormat('html')
                ->subject('Changement pour une partie')
                ->send($texte);
            
            $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to('tomlafleur25@gmail.com')
                ->emailFormat('html')
                ->subject('Changement pour une partie')
                ->send($texte);
            
            $envoi = $this->Config->findByNomconfig('Courriel terrain');
            if((empty($ancien) || $ancien['Evenement']['Confirmation'] == 3) && $envoi['Config']['Valeur'] == 1) {
                $this->loadModel('Terrain');
                $terrain = $this->Terrain->findById($partie['VuePartie']['IdTerrain']);
                if(!empty($terrain) && $terrain['Terrain']['Courriel'] != null) {
                    $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                    ->to($terrain['Terrain']['Courriel'])
                    ->emailFormat('html')
                    ->subject('Changement pour une partie')
                    ->send($texte);
                
                    if($terrain['Terrain']['CourrielAdjoint'] != null && $terrain['Terrain']['CourrielAdjoint'] != $terrain['Terrain']['Courriel']) {
                        $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                        ->to($terrain['Terrain']['CourrielAdjoint'])
                        ->emailFormat('html')
                        ->subject('Changement pour une partie')
                        ->send($texte);
                    }
                }
                
                $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to('tomlafleur25@gmail.com')
                ->emailFormat('html')
                ->subject('Changement pour une partie')
                ->send($texte);
            }            
            
            return false;
        }
        return true;
    }

    function calculDateFin($partie) {
        $date = new DateTime($partie['DateTime']);

        if($partie['IdCategorie'] == 2 && $partie['Classe'] == 'B') {
            $date->add(new DateInterval('PT1H30M'));
        }
        elseif($partie['IdCategorie'] <= 3) {
            //atome - moustique
            $date->add(new DateInterval('PT2H'));
        } else {
            // pee-wee en montant
            $date->add(new DateInterval('PT2H30M'));
        }
        return $date->format('Y-m-d H:i:s');
    }

    function getEquipe($idEquipe) {
        if($idEquipe > 0) {
            $this->loadModel('VueEquipe');
            $equipe = $this->VueEquipe->findByIdequipe($idEquipe);
        } else {
            $this->Session->setFlash(__("Impossible de créer cette partie, paramètres manquants"));
            $this->redirect(array('action' => 'jour'));
        }
        return $equipe;
    }
    
    function getTerrain($idTerrain) {
        $this->loadModel('Terrain');
        $terrain = $this->Terrain->findById($idTerrain);
        
        return $terrain['Terrain'];
    }

    function lookupCategorie($nomCat) {
        $this->loadModel('Categorie');
        $rs = $this->Categorie->findByNomcategorie($nomCat);
        return $rs['Categorie']['id'];
    }
    
    function lookupAssociation($ville) {
        $this->loadModel('Association');
        $rs = $this->Association->findByVille($ville);
        
        if(empty($rs)) {
            $this->Association->create();
            $a['Association'] = array('Ville' => $ville);
            $this->Association->save($a);
            $rs['Association']['id'] = $this->lookupAssociation($ville);
        }
        return $rs['Association']['id'];
    }

    function lookupEquipe($partie,$nomEquipeComplet) {
        //équipes AA
        if($partie['Classe'] == 'AA') {
            if($partie['IdCategorie'] == 3) {
                $idAssociation = $this->lookupAssociation('St-Hyacinthe');
                $nom = 'Spartiates';
            } else {
                $idAssociation = $this->lookupAssociation('Richelieu-Yamaska');
                if($partie['IdCategorie'] == 4) {
                    $nom = $nomEquipeComplet;
                }
                elseif($partie['IdCategorie'] == 5) {
                    $nom = 'Guerriers';
                }
            }
        //équipes A et B  
        } else {
            //trouver l'association
            $nomEquipe = explode(" ",$nomEquipeComplet);
            $idAssociation = $this->lookupAssociation($nomEquipe[0]);
            if(empty($nomEquipe[1])) {
                $nom = '';
            } elseif(!empty($nomEquipe[2])) {
                $nom = $nomEquipe[1].' '.$nomEquipe[2];
            } else {
                $nom = $nomEquipe[1];
            }
        }
        
        $this->loadModel('Equipe');
        $rs = $this->Equipe->find('first',array('conditions' => array(
                                    'idCategorie' => $partie['IdCategorie'],
                                    'Classe' => $partie['Classe'],
                                    'IdAssociation' => $idAssociation,
                                    'NomEquipe' => iconv('utf-8','ISO-8859-1',$nom),
                                    'Saison' => date('Y')
        )));
        
        if(empty($rs)) {
            $this->Equipe->create();
            $e['Equipe'] = array('idCategorie' => $partie['IdCategorie'],
                                    'Classe' => $partie['Classe'],
                                    'IdAssociation' => $idAssociation,
                                    'NomEquipe' => iconv('utf-8','ISO-8859-1',$nom),
                                    'Saison' => date('Y'),
                                    'DateCreation' => date('Y-m-d H:i:s'),
                                    'DerniereModif' => date('Y-m-d H:i:s'));
            $this->Equipe->save($e);
            $rs['Equipe']['id'] = $this->lookupEquipe($partie,$nomEquipeComplet);
        }
        return $rs['Equipe']['id'];

    }

    function lookupTerrain($nomTerrain, $ville) {
        //trouver l'association receveuse
        $idAssociation = $this->lookupAssociation($ville);
        
        //gérer exceptions
        $nomTerrain = str_replace(" d'Aquin","",$nomTerrain);
        
        $this->loadModel('Terrain');
        $rs = $this->Terrain->findByNomterrainAndIdassociation($nomTerrain, $idAssociation);
        
        if(empty($rs) && $ville == 'St-Hyacinthe') {
            return 0;
        }
        elseif(empty($rs)) {
            $this->Terrain->create();
            $t['Terrain'] = array('NomTerrain' => $nomTerrain,
                                    'IdAssociation' => $idAssociation,
                                    'DateCreation' => date('Y-m-d H:i:s'),
                                    'DerniereModif' => date('Y-m-d H:i:s'));
            $this->Terrain->save($t);
            return $this->lookupTerrain($nomTerrain, $ville);
        }
        return $rs['Terrain']['id'];
    }

    public function changerClasse($classe) {
        $this->Session->write('Partie.Classe',$classe);
        $this->redirect(array('action' => 'ajouter'));
    }

    public function changerLigue($id) {
        $this->Session->write('Partie.IdLigue',$id);
        if($id == 4) {
            $this->Session->write('Partie.NoMois',6);
        }
        $this->redirect(array('action' => 'ajouter'));
    }

    public function changerMois($noMois) {
        $this->Session->write('Partie.NoMois',$noMois);
        $this->redirect(array('action' => 'ajouter'));
    }

    public function changerPartie() {
        if($this->request->is('post','put')) {
            $this->Session->write('Partie.id',$this->request->data['Partie']['idPartie']);
            $this->redirect(str_replace("/".basename(dirname(APP)), "", $this->request->data['Partie']['url']));
        } else {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }

    function listerArbitres($type) {
        $this->loadModel('VueArbitre');
        $arbitres = $this->VueArbitre->find('list', array(
                                'fields' => array('id', 'NomComplet'),
                                'conditions' => array('Type' => $type)
            ));
        
        return $arbitres;
    }

    function listerTerrains() {
        $this->loadModel('Terrain');
        $liste = $this->Terrain->find('list',array(
                        'fields' => array('id','NomTerrain'),
                        'conditions' => array('IdAssociation' => 2), 
                        'order' => array('Terrain.NomTerrain' => 'asc')
        ));

        return $liste;
    }

    function listerEquipes($data) {
        $this->loadModel('VueEquipe');
        $liste = $this->VueEquipe->find('list',array(
                            'fields' => array('idEquipe','VilleNomEquipe'),
                            'conditions' => array(
                                'Classe' => $data['Classe'],
                                'IdCategorie' => $data['IdCategorie']),
                            'order' => 'VilleNomEquipe'
        ));
        return $liste;
    }

    private function listerHeures() {
        $liste = array();
        for ($h = 8; $h <= 21; $h++) {
            foreach($this->minutes15 as $minute) {
                $liste[$h.':'.$minute] = $h.'h'.$minute;
            }
        }
        return $liste;
    }
    
    private function listerCategories($ligue) {
        
        $this->loadModel('Categorie');
        if($ligue == 'Saison locale SH' || $ligue == 'Séries locales SH') { // ligue locale
            $liste = $this->Categorie->find('list', array(
                                'fields' => array('id', 'NomCategorie'),
                                'conditions' => array('id BETWEEN ? AND ?' => array(2,3))
                ));
        } elseif($ligue == 'Saison LBACAA' || $ligue == 'Séries LBACAA') { //ligue AA
            $liste = $this->Categorie->find('list', array(
                                'fields' => array('id', 'NomCategorie'),
                                'conditions' => array('id BETWEEN ? AND ?' => array(3,5))
                ));
        } elseif($ligue == 'Tournoi moustique') { //tournoi moustique
            $liste = $this->Categorie->find('list', array(
                                'fields' => array('id', 'NomCategorie'),
                                'conditions' => array('id' => 3)
                ));
        } else {
            $liste = $this->Categorie->find('list', array(
                                'fields' => array('id', 'NomCategorie')
                ));
        }
    
        return $liste;
    }

    private function listerClasses($ligue, $idCategorie=null) {
        if($ligue == 'Saison LBACAA' || $ligue == 'Séries LBACAA') { //ligue AA
            $liste = array('AA');
        }
        elseif($ligue == 'Saison locale SH' || $ligue == 'Séries locales SH') { // ligue locale
            $liste = array('B');
        }
        else {
            $this->loadModel('Equipe');
            $liste = $this->Equipe->find('list', array(
                                'fields' => array('Classe', 'Classe'),
                                'conditions' => array('IdAssociation' => 2,
                                                      'IdCategorie' => $idCategorie,
                                                      'Saison' => $this->getConfigValueByName('Annee equipe')
                )));
        }
        return $liste;
    }

    function listerLigues() { 
        $this->loadModel('Ligue');
        $liste = $this->Ligue->find('list', array(
                                        'fields' => array('id', 'nomCourtLigue'),
                                        'conditions' => array('OR' => array('AutoMapping' => 2, 
                                                                            'LigueLocale' => 1)
            )));

        return $liste;
    }
   
}
