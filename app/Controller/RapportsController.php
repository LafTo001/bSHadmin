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
class RapportsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        //public $helpers = array('PhpExcel'); 
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
        
        public function index() {
            $this->loadModel('Inscription');
            $saison = $this->getConfigValueByName('Annee inscription');
            $liste = $this->Inscription->countParCategorie($saison);
            
            $this->set(compact('liste', 'annee'));
        }
        
        public function inscritsParCategorie() {
            $this->loadModel('Inscription');
            $saison = $this->getConfigValueByName('Annee inscription');
            $liste = $this->Inscription->countParCategorie($saison);
            
            $annees = array('2015', '2016');
            $this->set(compact('liste', 'annee'));
        }
        
        public function paiements() {
            $this->loadModel('VueJoueur');
            $joueurs = $this->VueJoueur->find('all', array(
                                                'conditions' => array('Paiement' => 0, 
                                                                      'IdCategorie > ' => 0),
                                                'order' => array('nomPrenom')
                ));
            $this->set(compact('joueurs'));
        }
        
        public function carteAccesLoisirs() {
            
        }
        
        public function listeJoueurs() {
            $this->loadModel('VueJoueur');
            $extract = $this->VueJoueur->find('all',array('conditions' => array('IdCategorie >' => '0'),'order' => 'nomPrenom'));
            foreach($extract as $cle => $joueur) {
                $extract[$cle]['VueJoueur']['Courriels'] = $this->listerCourrielsJoueur($joueur['VueJoueur']['idJoueur']);
            }
            $this->set('joueurs',$extract);
            
            $this->layout = 'ajax'; 
        }
        
        public function listerCourrielsJoueur($idJoueur) {
            $this->loadModel('Joueur');
            $liste = $this->Joueur->getListeCourrielsJoueur($idJoueur);
            
            $string = '';
            foreach($liste as $courriel) {
                $string .= $courriel[0]['Courriel'].' ';
            }
            
            return $string;
        }
        
        public function maintenance() {
            //trouver les parties à effacer
            $this->loadModel('VuePartiesInexistante');
            $parties = $this->VuePartiesInexistante->findByIdligue(1);
            //var_dump($parties);
            $this->set('parties',$parties);
            
            //trouver les doublons dans les événements
            //$this->loadModel('VueDoublons');
            //$doublons = $this->VueDoublons->findAll();
        }
        
        public function configs($action=null) {
            
            $this->loadModel('Config');
            if($this->request->is('post','put')) {
                $this->Config->id = $this->request->data['Config']['id'];
                $this->Config->saveField('Valeur', $this->request->data['Config']['Valeur']);
            }
            
            unset($this->request->data);

            $configs = $this->Config->find('all');
            
            $this->set(compact('configs'));
        }
        
        /**
        * Dumps the MySQL database that this controller's model is attached to.
        * This action will serve the sql file as a download so that the user can save the backup to their local computer.
        *
        * @param string $tables Comma separated list of tables you want to download, or '*' if you want to download them all.
        */
       function admin_database_mysql_dump($tables = '*') {

           $return = '';

           $modelName = $this->modelClass;

           $dataSource = $this->{$modelName}->getDataSource();
           $databaseName = $dataSource->getSchemaName();


           // Do a short header
           $return .= '-- Database: `' . $databaseName . '`' . "\n";
           $return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";


           if ($tables == '*') {
               $tables = array();
               $result = $this->{$modelName}->query('SHOW TABLES');
               foreach($result as $resultKey => $resultValue){
                   $tables[] = current($resultValue['TABLE_NAMES']);
               }
           } else {
               $tables = is_array($tables) ? $tables : explode(',', $tables);
           }

           // Run through all the tables
           foreach ($tables as $table) {
               $tableData = $this->{$modelName}->query('SELECT * FROM ' . $table);

               $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
               $createTableResult = $this->{$modelName}->query('SHOW CREATE TABLE ' . $table);
               $createTableEntry = current(current($createTableResult));
               $return .= "\n\n" . $createTableEntry['Create Table'] . ";\n\n";

               // Output the table data
               foreach($tableData as $tableDataIndex => $tableDataDetails) {

                   $return .= 'INSERT INTO ' . $table . ' VALUES(';

                   foreach($tableDataDetails[$table] as $dataKey => $dataValue) {

                       if(is_null($dataValue)){
                           $escapedDataValue = 'NULL';
                       }
                       else {
                           // Convert the encoding
                           $escapedDataValue = mb_convert_encoding( $dataValue, "UTF-8", "ISO-8859-1" );

                           // Escape any apostrophes using the datasource of the model.
                           $escapedDataValue = $this->{$modelName}->getDataSource()->value($escapedDataValue);
                       }

                       $tableDataDetails[$table][$dataKey] = $escapedDataValue;
                   }
                   $return .= implode(',', $tableDataDetails[$table]);

                   $return .= ");\n";
               }

               $return .= "\n\n\n";
           }

           // Set the default file name
           $fileName = $databaseName . '-backup-' . date('Y-m-d_H-i-s') . '.sql';

           // Serve the file as a download
           $this->autoRender = false;
           $this->response->type('Content-Type: text/x-sql');
           $this->response->download($fileName);
           $this->response->body($return);
       }
}
