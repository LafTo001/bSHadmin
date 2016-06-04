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
class Partie extends Model {
    
    public function processClassement($equipe, $ligue) {
        $rs = $this->query('SELECT  '.$equipe.' AS idEquipe,
                                    '.$ligue.' AS idLigue,
                                    SUM(CASE
                                            WHEN IdEquipeVisiteur = '.$equipe.'
                                                THEN 1
                                            WHEN IdEquipeReceveur = '.$equipe.'
                                                THEN 1
                                            ELSE 0 END) AS Parties,
                                    SUM(CASE
                                            WHEN IdEquipeVisiteur = '.$equipe.'
                                                    AND PointsVisiteur > PointsReceveur
                                                THEN 1
                                            WHEN IdEquipeReceveur = '.$equipe.'
                                                    AND PointsReceveur > PointsVisiteur
                                                THEN 1
                                            ELSE 0 END) AS Victoires,
                                    SUM(CASE
                                            WHEN IdEquipeReceveur = '.$equipe.'
                                                    AND PointsReceveur < PointsVisiteur
                                                THEN 1
                                            WHEN IdEquipeVisiteur = '.$equipe.'
                                                    AND PointsVisiteur < PointsReceveur
                                                THEN 1
                                            ELSE 0 END) AS Defaites,
                                    SUM(CASE
                                            WHEN IdEquipeVisiteur = '.$equipe.'
                                                    AND PointsVisiteur = PointsReceveur
                                                THEN 1
                                            WHEN IdEquipeReceveur = '.$equipe.'
                                                    AND PointsReceveur = PointsVisiteur
                                                THEN 1
                                            ELSE 0 END) AS Nulles,
                                    SUM(CASE
                                            WHEN IdEquipeVisiteur = '.$equipe.'
                                                THEN PointsVisiteur
                                            WHEN IdEquipeReceveur = '.$equipe.'
                                                THEN PointsReceveur
                                            ELSE 0 END) AS PtsPour,
                                    SUM(CASE
                                            WHEN IdEquipeVisiteur = '.$equipe.'
                                                THEN PointsReceveur
                                            WHEN IdEquipeReceveur = '.$equipe.'
                                                THEN PointsVisiteur
                                            ELSE 0 END) AS PtsContre
                                FROM parties
                                WHERE IdLigue = '.$ligue.'
                                    AND YEAR(Datetime) = '.date('Y').'
                                    AND Statut = 2');
        
        return $rs[0];
    }
    
}