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
class AccueilController extends AppController {

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
        $this->Auth->allow('index','contacts','inscriptions','rallyecap','evenements','ligue'); 
    }

    public function index() {
        
        //derniers résultats de parties à présenter
        $this->loadModel('VuePartie');
        $resultats = $this->VuePartie->getDerniersResultats(15);
        $listeParties = array();
        $i = 0;
        $cpt = 0;
        $listePauseContents = array();
        $pausecontent = 'pausecontent['.$cpt.']= "';
        foreach($resultats as $partie) {
            $pausecontent .= "<div id='resultats'><span><b>";
            $pausecontent .= $partie[0]['Datetime'].' &nbsp; '.$partie['VuePartie']['NomCategorie'].' '.$partie['VuePartie']['Classe'];
            $pausecontent .= "</b></span><br/><table><tr><td id='equipe'>";
            $pausecontent .= substr($partie['VuePartie']['NomEquipeVisiteur'],0,24)."</td><td id='pointage'>".$partie['VuePartie']['PointsVisiteur'];
            $pausecontent .= '</td></tr><tr><td>';
            $pausecontent .= substr($partie['VuePartie']['NomEquipeReceveur'],0,24)."</td><td id='pointage'>".$partie['VuePartie']['PointsReceveur'];
            $pausecontent .= '</td></tr></table></div>';
            
            if(++$i == 3) {
                $i = 0;
                $pausecontent .= '";'."\n";
                $listePauseContents[$cpt++] = $pausecontent;
                $pausecontent = 'pausecontent['.$cpt.']= "';
            }
        
        }
        
        //var_dump($listePauseContents);
        
        $this->set(compact('listePauseContents'));

        //les derniers articles
        $this->loadModel('VueCommunique');
        $articles = $this->VueCommunique->find('all',array(
                                            'conditions' => array('DateSuppression' => null,
                                                                  'IdEquipe' => 0),
                                            'order' => array('DerniereModif desc'),
                                            'limit' => 5
            ));
        
        $this->set(compact('listeParties','articles'));
        
        $this->layout = 'accueil';
    }
    
    public function contacts($comite=null) {
        
        if($comite == null) {
            $comite = 'conseil';
        }
        $this->set(compact('comite'));
        
        $this->layout = 'accueil';
    }

    public function inscriptions() {
        $this->layout = 'accueil';
    }
    
    public function evenements() {
        $this->layout = 'accueil';
    }

    public function rallyecap() {
        $this->layout = 'accueil';
    }
    
    public function ligue($categorie=null, $nomEquipe=null) {
        
        if($this->request->is('Post','put')) {
            $this->redirect(array('action' => 'ligue', 
                $this->request->data['Equipe']['NomCategorie'], $this->request->data['Equipe']['NomEquipe']));
        }
        
        if($categorie == null) {
            $categorie = 'Atome';
        }
        
        if($nomEquipe != null) {
            $this->loadModel('VueEquipe');
            $equipe = $this->VueEquipe->findByNomequipeAndNomcategorieAndClasse($nomEquipe, $categorie, 'B');
        }
        
        //$parties
        
        $this->loadModel('Categorie');
        $lstCategories = $this->Categorie->find('list', array(
                            'fields' => array('NomCategorie', 'NomCategorie'),
                            'conditions' => array('id BETWEEN ? AND ?' => array(2,3))
            ));
        
        $this->loadModel('VueEquipe');
        $lstEquipes = $this->VueEquipe->find('list', array(
                            'fields' => array('NomEquipe', 'NomEquipe'),
                            'conditions' => array(
                                'NomCategorie' => $categorie, 
                                'Classe' => 'B')
            ));
        
        $this->loadModel('VueClassement');
        $class = $this->VueClassement->find('all', array('conditions' => array(
                                                            'Saison' => date('Y'),
                                                            'NomCategorie' => $categorie),
                                                         'order' => array(
                                                            'Points' => 'desc',
                                                            'Victoires' => 'desc',
                                                            'Defaites' => 'asc')
            ));
        
        $cmpt = 0;
        foreach($class as $cle => $e) {
            $class[$cle]['VueClassement']['Position'] = ++$cmpt;
            if($cmpt == 1) {
                $class[$cle]['VueClassement']['Difference'] = 0;
                $victoires1er = $e['VueClassement']['Victoires'];
                $defaites1er = $e['VueClassement']['Defaites'];
            } else {
                $class[$cle]['VueClassement']['Difference'] = floatval((($victoires1er - $e['VueClassement']['Victoires'])
                                                                + ($e['VueClassement']['Defaites'] - $defaites1er)) / 2);
            }
        }

        $this->loadModel('VuePartie');
        if($nomEquipe != null) {
            $parties = $this->VuePartie->find('all',array('conditions' => 
                                                            array('OR' => array('IdEquipeVisiteur' => $equipe['VueEquipe']['idEquipe'],
                                                                                'IdEquipeReceveur' => $equipe['VueEquipe']['idEquipe']),
                                                            'idLigue' => 17),
                                                        'order' => array('Datetime', 'NoPartie')
            ));
        } else {
            $parties = $this->VuePartie->find('all',array('conditions' => array(
                                                            'nomCategorie' => $categorie,
                                                            'idLigue' => 17),
                                                        'order' => array('Datetime', 'NoPartie')
            ));
        }
        
        $this->loadModel('VueEvenement');
        $pratiques = $this->VueEvenement->find('all', array(
                                                'fields' => array(
                                                    'DISTINCT DateFormat', 'DebutEvenement', 'FinEvenement', 
                                                    'REPLACE(REPLACE(NomTerrain, " 1", ""), " 2", "") AS NomTerrain'),
                                                'conditions' => array(
                                                    'TypeEvenement' => 2,
                                                    'NomCategorie' => $categorie,
                                                    'Classe' => 'B',
                                                    'DateEvenement >' => date('Y-m-d H:i:s'),
                                                    'Confirmation' => '1'),
                                                'order' => 'dateEvenement'
            ));
        
        $this->set(compact('lstCategories', 'lstEquipes', 'class', 
                            'categorie', 'equipe', 'parties', 'pratiques'));
        
        $this->layout = 'accueil';
    }
    
}
