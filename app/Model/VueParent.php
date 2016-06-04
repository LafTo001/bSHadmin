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
class VueParent extends Model {
    public $name = 'VueParent';
    
    function findParentsFamille($idJoueur,$idParent) {
        $rs = $this->query('SELECT DISTINCT VueParent.*
                                FROM joueurs J 
                                INNER JOIN familles F ON F.IdJoueur = J.id
                                INNER JOIN adultes P ON P.id = F.IdParent
                                INNER JOIN familles F1 ON F1.IdParent = P.id
                                INNER JOIN joueurs FS ON FS.id = F1.IdJoueur
                                INNER JOIN familles F2 ON F2.IdJoueur = FS.id
                                INNER JOIN vue_parents VueParent ON VueParent.idParent = F2.IdParent
                                WHERE J.id = '.$idJoueur.'
                                AND VueParent.idParent NOT IN ('.$idParent.')');
        return $rs;
    }
}