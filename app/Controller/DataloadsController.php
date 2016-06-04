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
class DataloadsController extends AppController {

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
    
    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->Session->setFlash(__("Vous n'avez pas accès à cette page"));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }
    
    public function extrairePartiesAA() {
        if($this->request->is(array('post','put'))) {
            if($this->request->data['Partie']['IdCategorie'] >= 3) {
                $fichier = fopen('parties.txt', 'w+');
                fputs($fichier, $this->request->data['Partie']['Parties']);
                fseek($fichier, 0);
                
                $ligne = fgets($fichier, 255);
                $ligne = explode(" ",trim($ligne));
                $this->loadModel('Date');
                $date = $this->Date->getDate($ligne);
                while(!feof($fichier))
                {
                    $ligne = fgets($fichier, 255);
                    if(substr($ligne,0,2) == "  ")  {
                        $ligne = explode(" ",trim($ligne));
                        $date = $this->Date->getDate($ligne);
                    } else {
                        //003 - 14:00 	St-Hyacinthe 	- 		- 	Roussillon 	Leblanc 2 (St-Constant) 	
                        $array = explode("\t",$ligne);
                        $numeroHeure = explode(" - ",trim($array[0]));
           
                        $partie = array();
                        $partie['IdCategorie'] = intVal($this->request->data['Partie']['IdCategorie']);
                        $partie['Classe'] = 'AA';
                        $partie['IdLigue'] = 5;
                        $partie['NoPartie'] = intVal($numeroHeure[0]);
                        $partie['DateTime'] = $date.' '.$numeroHeure[1].':00';  
                        
                        $partie['IdTerrain'] = 0;
                        $partie['Locale'] = 0;
                        
                        if(strpos($array[6], "(")) {
                            $terrain = explode(" (",$array[6]);
                            if(trim(str_replace(")","",$terrain[1])) == 'St-Hyacinthe') {
                                $partie['Locale'] = 1;
                                $partie['IdTerrain'] = $this->lookupTerrain($terrain[0],'St-Hyacinthe');
                            }
                        }
                        $partie['NomTerrain'] = trim($array[6]);
                        
                        $partie['NomEquipeVisiteur'] = trim($array[1]);
                        $partie['NomEquipeReceveur'] = trim($array[5]);
                        $partie['IdEquipeVisiteur'] = 0;
                        $partie['IdEquipeReceveur'] = 0;
                        if($partie['NomEquipeVisiteur'] == 'St-Hyacinthe') {
                            $partie['IdEquipeVisiteur'] = $this->lookupEquipe($partie,'St-Hyacinthe Condors');
                        } 
                        elseif($partie['NomEquipeReceveur'] == 'St-Hyacinthe') {
                            $partie['IdEquipeReceveur'] = $this->lookupEquipe($partie,'St-Hyacinthe Condors');
                        }
                        if($array[2] != '-') {
                            $partie['PointsVisiteur'] = $array[2];
                            $partie['PointsReceveur'] = $array[4];
                        }

                        $partieAMapper['Partie'] = $partie;
                        $this->mapPartie($partieAMapper);

                        //$event['Partie'] = $partie;
                        //$this->mapEvenement($event);
                            
                    }
                }
                
                $this->redirect(array('action' => 'jour',$date));
            }
            else {
                //message d'erreur pas de catégorie
            }
        }
    }

    public function extrairePartiesLBAVR($idLigue=null) {
        $this->loadModel('PartieDataload');
        $this->loadModel('Ligue');
        $ligue = $this->Ligue->findById($idLigue);
        
        if(empty($ligue)) {
            $this->Session->setFlash(__("Impossible d'extraire les parties, paramètres manquants"));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        $fichierS = fopen($ligue['Ligue']['CalendrierUrl'], 'r');
        //$fichierD = fopen('parties.txt', 'w+');

        $char = array("<tr>","</tr>","<td>","</a>",'<tr class="rose">');
        
        $this->PartieDataload->truncateTable();

        while(!feof($fichierS))
        {
            $ligne = fgets($fichierS, 200000);

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
                        $partie = array();
                        
                        $nomEquipeVisiteur = explode(" ", $array[5]);
                        $nomEquipeReceveur = explode(" ", $array[7]);
                        $division = explode(" ", $array[0]);
                        
                        if($nomEquipeVisiteur[0] == 'St-Hyacinthe' || $nomEquipeReceveur[0] == 'St-Hyacinthe') {
                        
                            $partie['PartieDataload']['IdLigue'] = $idLigue;
                            $partie['PartieDataload']['Categorie'] = $division[0];
                            $partie['PartieDataload']['Classe'] = $division[1];
                            $partie['PartieDataload']['NoPartie'] = $array[1];
                            $partie['PartieDataload']['Date'] = $array[3];
                            $partie['PartieDataload']['Heure'] = $array[4];
                            $partie['PartieDataload']['Visiteur'] = iconv('utf-8','ISO-8859-1',$array[5]);
                            $partie['PartieDataload']['PointsV'] = str_replace('"','',$array[6]);
                            $partie['PartieDataload']['Receveur'] = iconv('utf-8','ISO-8859-1',$array[7]);
                            $partie['PartieDataload']['PointsR'] = str_replace('"','',$array[8]);
                            $partie['PartieDataload']['Terrain'] = iconv('utf-8','ISO-8859-1',substr($array[9],strpos($array[9],">")+1));

                            $this->PartieDataload->save($partie);
                        }
                    }
                }
            }
        }
        
        //$this->Session->setFlash(__("Les parties de la LBAVR ont été enregistrés dans la table Dataload."));
        //$this->effacerPartiesInextantes($idLigue);
        
        $this->redirect(array('controller' => 'rapports', 'action' => 'maintenance'));
    }
    
    public function supprimerPartie($idPartie) {
        
        $this->validerRole('admin');
        $this->loadModel('Partie');
        $this->loadModel('VuePartie');
        $this->loadModel('Evenement');
        
        $partie = $this->VuePartie->findByIdpartie($idPartie);
        
        $this->Partie->id = $idPartie;
        $this->Partie->saveField('DateSuppression',date('Y-m-d H:i:s'));

        if($partie['VuePartie']['Locale'] == 1) { 
            $event = $this->Evenement->findByIdpartie($idPartie);
            $this->Evenement->id = $event['Evenement']['id'];
            $this->Evenement->saveField('Confirmation',2);

            $texte = '<html><p>Retrait d\'une partie</p>';
            $texte.= '<p>Équipe receveuse: '.$partie['VuePartie']['NomEquipeReceveur'].' ';
            $texte.= $partie['VuePartie']['NomCategorie'].' '.$partie['VuePartie']['Classe'].'<br/>';
            $texte.= '# Partie: '.$partie['VuePartie']['NoPartie'].'<br/>';
            $texte.= 'Date: '.$partie['VuePartie']['Date'].' à '.$partie['VuePartie']['Heure'].'</p>';

            $Email = new CakeEmail();
            $this->loadModel('Config');
            $adresse = $this->Config->findByNomconfig('Adresse changement partie');
            $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                ->to($adresse['Config']['Valeur'])
                ->emailFormat('html')
                ->subject('Changement pour une partie')
                ->send($texte);
        }
        
        $this->redirect(array('controller' => 'rapports', 'action' => 'maintenance'));
    }
    
    function getTerrain($idTerrain) {
        $this->loadModel('Terrain');
        $terrain = $this->Terrain->findById($idTerrain);
        
        return $terrain['Terrain'];
    }


}
