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
class Date extends Model {
    
    public function getJoursMois($noMois) {
    
        $rs = $this->query('SELECT D.NoSemaine FROM dates D WHERE date = (SELECT min(Date) FROM dates WHERE NoMois = '.$noMois.' AND Annee = YEAR(NOW()))');
	$noSemaine = $rs[0]['D']['NoSemaine'];
	
	$jours = $this->query('SELECT date, JourMois, NoMois, NoSemaine 
				FROM dates D 
                                WHERE NoSemaine BETWEEN '.$noSemaine.' AND '.($noSemaine+5).'
				ORDER BY Date');
	return $jours;
    }
    
    public function getDate($dateFormate) {
        $rs = $this->query('SELECT D.date FROM dates D
                                INNER JOIN months M ON M.id = D.NoMois
                                WHERE JourMois = '.$dateFormate[1].' AND NomMois = "'.$dateFormate[2].'" AND Annee = '.$dateFormate[3]);
        return $rs[0]['D']['date'];
    }
}