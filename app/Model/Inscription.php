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
class Inscription extends Model {
    
    public function countParCategorie($saison) {
        $rs = $this->query('SELECT IdCategorie, 
                                CASE WHEN T.Classe IS NULL THEN 2
                                     ELSE 1 END AS Ordre,
                                CASE WHEN T.NomCategorie IS NULL THEN 99 ELSE T.IdCategorie END AS IdCategorie,
                                CASE WHEN T.NomCategorie IS NULL THEN "TOTAL INSCRIPTIONS"
                                         WHEN T.Classe IS NULL THEN CONCAT("Total ",T.NomCategorie)
                                         ELSE T.NomCategorie END AS Categorie,
                                Classe, T.Count
                            FROM (
                                SELECT IdCategorie, NomCategorie, IFNULL(Classe,"") AS Classe, COUNT(*) AS Count
                                FROM inscriptions I
                                INNER JOIN categories C ON C.id = I.IdCategorie
                                WHERE Saison = '.$saison.'
                                GROUP BY NomCategorie, IFNULL(Classe,"") WITH ROLLUP
                            ) T
                            ORDER BY IdCategorie, Ordre, Classe');
        return $rs;
    }
}