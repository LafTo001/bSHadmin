<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class PartieDataload extends Model {
    
    public function truncateTable() {
        $this->query('TRUNCATE TABLE partie_dataloads');
    }
    
    public function insert($idLigue,$partie) {
        $this->query('INSERT INTO partie_dataloads(IdLigue,Categorie,NoPartie,Date,Heure,Visiteur,PointsV,Receveur,PointsR,Terrain)
                        VALUES('.$idLigue.',"'.$partie['Categorie'].'",'.$partie['NoPartie'].',"'.$partie['Date'].'","'.$partie['Heure'].'","'.
                            $partie['Visiteur'].'","'.$partie['PointsV'].'","'.$partie['Receveur'].'","'.$partie['PointsR'].'","'.$partie['Terrain'].'")');
    }
    
    public function findPartiesInexistantes($idLigue) {
        $parties = $this->query('SELECT P.* FROM vue_parties P
                            LEFT JOIN partie_dataloads D
                                ON P.IdLigue = D.idLigue
                                AND P.NomCategorie = D.Categorie
                                AND P.Classe = D.Classe
                                AND P.NoPartie = D.NoPartie
                                AND YEAR(P.Date) = YEAR(D.Date)
                            WHERE P.IdLigue = '.$idLigue.' AND YEAR(P.Date) = YEAR(NOW())
                                AND D.NoPartie IS NULL AND Active = 1');
        return $parties;
    }
}