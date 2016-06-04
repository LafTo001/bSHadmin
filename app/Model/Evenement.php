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
class Evenement extends Model {
    
    public function HeureReserve($datetime, Terrain $terrain, $limite)
    {
        $sql = 'SELECT COUNT(*) AS count FROM Evenements E
                    WHERE IdTerrain = '.$terrain->id().' AND "'.$datetime.'" BETWEEN E.DebutEvenement AND E.FinEvenement
                    AND E.IdEvenement != '.$_SESSION['idEvent'].' AND E.idSerie != '.$_SESSION['idSerie'];

        if ($limite == 'debut') {
            $sql .= ' AND E.FinEvenement != "' . $datetime . '"';
        } elseif ($limite == 'fin') {
            $sql .= ' AND E.DebutEvenement != "' . $datetime . '"';
        }

        //echo $sql;

        $reponse = $bdd->query($sql) or die(print_r($bdd->errorInfo()));
        $donnees = $reponse->fetch();

        if($donnees['count'] > 0)
                return true;

        else return false;
    }
}