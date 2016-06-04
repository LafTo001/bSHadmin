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
class ArbitresController extends AppController {

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

    public $paginate = array(
        'limit' => 15,
        'order' => array('VueArbitre.NomPrenom' => 'asc')
    );

    public function validerRole($role) {
        if($this->Session->read('User.role') != $role) {
            $this->setFlash("Vous n'avez pas accès à cette page");
            $this->redirect(array('controler' => 'pages', 'action' => 'home'));
        }
    }

    public function index() {
        $this->validerRole('arbitre');

        $this->loadModel('VueArbitre');
        $this->paginate = array(
            'limit' => 15,
            'order' => array('VueArbitre.NomPrenom' => 'asc')
        );

        $arbitres = $this->paginate('VueArbitre');
        $this->set(compact('arbitres'));
        
        $this->set('courriels', $this->listerCourriels());
    }

    public function fiche($id,$nom) {
        $this->validerRole('arbitre');

        $this->loadModel('VueArbitre');
        $arbitre = $this->VueArbitre->findById($id);
        $this->set('arbitre',$arbitre['VueArbitre']);

        //liste des parties
        $this->loadModel('VuePartie');
        $parties = $this->VuePartie->find('all',array('conditions' => array(
                                        'OR' => array('IdArbitreMarbre' => $id,
                                                      'IdArbitreBut' => $id,
                                                      'IdMarqueur' => $id),
                                        'YEAR(Datetime)' => date('Y')),
                                        'order' => 'Datetime'
        ));
        $this->set('parties',$parties);
        $this->set('idArbitre',$id);
    }

    public function ajouter() {
        $this->validerRole('arbitre');

        if($this->request->is('post')) {
            $this->request->data['Arbitre']['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Arbitre']['TelMaison']);
            $this->request->data['Arbitre']['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Arbitre']['TelMobile']);
            $this->request->data['Arbitre']['DateCreation'] = date('Y-m-d H:i:s');
            $this->request->data['Arbitre']['DerniereModif'] = date('Y-m-d H:i:s');
            $this->request->data['Arbitre']['ModifParUsager'] = $this->Auth->User('id');
            $this->Arbitre->save($this->request->data);
            $this->redirect(array('action' => 'index'));
        } else {
            $this->set('listeTypes',$this->listerTypes());
            $this->set('listeGrades',$this->listerGrades());
        }
    }

    public function editer($id) {
        $this->validerRole('arbitre');

        if($this->request->is(array('post','put'))) {
            $this->request->data['Arbitre']['TelMaison'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Arbitre']['TelMaison']);
            $this->request->data['Arbitre']['TelMobile'] = preg_replace('/[^0-9.]+/', '',$this->request->data['Arbitre']['TelMobile']);
            $this->request->data['Arbitre']['DerniereModif'] = date('Y-m-d H:i:s');
            $this->request->data['Arbitre']['ModifParUsager'] = $this->Auth->User('id');
            $this->Arbitre->id = $id;
            $this->Arbitre->save($this->request->data);
            $this->redirect(array('action' => 'index'));
        } else {
            $arbitre = $this->Arbitre->findById($id);
            $this->request->data = $arbitre;
            $this->set('listeTypes',$this->listerTypes());
            $this->set('listeGrades',$this->listerGrades());
        }
    }

    public function supprimer($id,$confirm=0) {
        $this->validerRole('arbitre');

        if($confirm == 1) {
            $this->Arbitre->id = $id;
            $this->Arbitre->saveField('DateSuppression',date('Y-m-d H:i:s'));

            $this->redirect(array('action' => 'index'));

        } else {
            $this->loadModel('VueArbitre');
            $arbitre = $this->VueArbitre->findById($id);
            $this->set('arbitre',$arbitre);
            $this->set('id',$id);
        }
    }

    public function envoyerHoraire($id=null) {
        if($id == null) {
            $liste = $this->Arbitre->find('list',array('fields' => 'id', 
                                                       'conditions' => array('DateSuppression' => null)
            ));
        } else {
            $liste[] = $id;
        }
        
        foreach($liste as $id) {
            $arbitre = $this->Arbitre->findById($id);

            $this->loadModel('VuePartie');
            $parties = $this->VuePartie->find('all',array('conditions' => array(
                                            'OR' => array('IdArbitreMarbre' => $id,
                                                          'IdArbitreBut' => $id,
                                                          'IdMarqueur' => $id),
                                            'Datetime >' => date('Y-m-d H:i:s')),
                                            'order' => 'Datetime'
            ));

            if(!empty($parties)) {
                $texte = '<html><p>Bonjour,</p>';
                $texte.= '<p>Voici ton horaire pour les parties à venir</p>';
                $texte.= '<table width="100%"><thead style="background-color:#044762;color:white";><tr>';
                $texte.= '<th>Ligue</th><th>Categorie</th><th>Terrain</th><th>Date</th><th>Heure</th><th># Partie</th><th>Arbitre Marbre</th><th>Arbitre But</th><th>Marqueur</th>';
                $texte.= '</tr></thead>';

                $cmpt = 0;
                foreach($parties as $partie) {
                    if(++$cmpt % 2 == 1) {
                        $texte.= '<tr style="background-color:#D2D3D5;">';
                    } else {
                        $texte.= '<tr style="background-color:#F8F8F8;">';
                    }
                    $style = ($partie['VuePartie']['Active'] == 0) ? '<s>' : ''; 
                    
                    $texte.= '<td>'.$style.$partie['VuePartie']['NomLigue'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['NomCategorie'].' '.$partie['VuePartie']['Classe'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['NomTerrain'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['Date'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['Heure'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['NoPartie'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['NomArbitreMarbre'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['NomArbitreBut'].'</td>';
                    $texte.= '<td>'.$style.$partie['VuePartie']['NomMarqueur'].'</td>';
                    $texte.= '</tr>';
                }
                $texte.= '</table>';

                $Email = new CakeEmail();
                if($arbitre['Arbitre']['Courriel1'] != null) {
                    $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                        ->to($arbitre['Arbitre']['Courriel1'])
                        ->emailFormat('html')
                        ->subject('Horaire d\'arbitre/marqueur')
                        ->send($texte);
                }

                if($arbitre['Arbitre']['Courriel2'] != null) {
                    $Email->from(array('no-reply@baseballsthyacinthe.com' => 'Baseball St-Hyacinthe'))
                        ->to($arbitre['Arbitre']['Courriel2'])
                        ->emailFormat('html')
                        ->subject('Horaire d\'arbitre/marqueur')
                        ->send($texte);
                }
                $this->Session->setFlash(__("L'horaire a été envoyé à l'arbitre"));
            }
        }
        $this->redirect(array('action' => 'index'));
    }

    public function listerTypes() {
        $liste = array(1 => 'Arbitre',
                       2 => 'Marqueur');
        return $liste;
    }

    public function listerGrades() {
        $liste = array( '-' => '-- Grades d\'arbitre --',
                        '1' => '1',
                        '1A' => '1A',
                        '2' => '2', 
                        '3' => '3',
                        '--' => '-- Grades de marqueur --',
                        'P' => 'P',
                        'C' => 'C',
                        'V' => 'V');
        return $liste;
    }
    
    public function listerCourriels() {
        
        $rs = $this->Arbitre->find('all', array('conditions' => array('DateSuppression' => null)));
        //var_dump($rs);
        $liste = '';
        foreach($rs as $arbitre) {
            $liste.= $arbitre['Arbitre']['Courriel1'].'; ';
            if($arbitre['Arbitre']['Courriel2'] != '') {
                $liste.= $arbitre['Arbitre']['Courriel2'].'; ';
            }
        }
        
        return $liste;
    }
}
