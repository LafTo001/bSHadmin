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
class VuePartie extends Model {

    public function getPartieEntraineur($idPartie,$idParent) {
        $rs = $this->query('SELECT IdPartie, NoPartie, IdCategorie, NomCategorie, Classe, IdLigue, NomLigue,
                                DateFormat, Heure, Date, NomEquipeVisiteur, NomEquipeReceveur, DropDownPartieDesc,
                                CASE WHEN EV.IdParent IS NOT NULL THEN IdEquipeVisiteur
                                         WHEN ER.IdParent IS NOT NULL THEN IdEquipeReceveur 
                                         ELSE 0 END AS IdEquipe,
                                CASE WHEN EV.IdParent IS NOT NULL THEN NomEquipeVisiteur
                                         WHEN ER.IdParent IS NOT NULL THEN NomEquipeReceveur 
                                         ELSE "" END AS NomEquipe,
                                CASE WHEN EV.IdParent IS NULL AND ER.IdParent IS NULL THEN 0
                                         WHEN EV.IdParent IS NULL THEN IdEquipeVisiteur
                                         WHEN ER.IdParent IS NULL THEN IdEquipeReceveur END AS IdOpposant,
                                CASE WHEN EV.IdParent IS NULL AND ER.IdParent IS NULL THEN ""
                                         WHEN EV.IdParent IS NULL THEN NomEquipeVisiteur
                                         WHEN ER.IdParent IS NULL THEN NomEquipeReceveur END AS NomOpposant,
                                CASE WHEN ER.IdParent IS NOT NULL THEN 1 ELSE 0 END AS EstReceveur,
                                CASE WHEN ER.IdParent IS NOT NULL THEN "x" ELSE "" END AS XReceveur,
                                CASE WHEN ER.IdParent IS NOT NULL THEN "" ELSE "x" END AS XVisiteur
                                FROM vue_parties P
                                LEFT JOIN entraineurs ER ON ER.IdEquipe = P.IdEquipeReceveur 
                                    AND ER.IdParent = '.$idParent.'
                                LEFT JOIN entraineurs EV ON EV.IdEquipe = P.IdEquipeVisiteur 
                                    AND EV.IdParent = '.$idParent.'
                                WHERE IdPartie = '.$idPartie);
        return $rs;
    }
    
    public function getDerniersResultats($limit) {
        $rs = $this->query('SELECT DATE_FORMAT(Datetime,"%e/%m %kh%i") AS Datetime, NomEquipeReceveur, PointsReceveur,
                                NomEquipeVisiteur, PointsVisiteur, NomCategorie, Classe, NomLigue
                                FROM vue_parties VuePartie
                                WHERE (PointsReceveur > 0 || PointsVisiteur > 0)
                                AND (IdEquipeVisiteur > 0 || IdTerrain > 0)
                                ORDER BY VuePartie.Datetime DESC
                                LIMIT 0,'.$limit);
        return $rs;
    }
}