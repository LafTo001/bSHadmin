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
class Adulte extends Model {
    public $name = 'Adulte';
    
    public function getParentsJoueur($id) {
        $rs = $this->query('SELECT P.id, F.TypeParent, P.NomFamille, P.Prenom, Adresse, Ville, CodePostal, Courriel1, Courriel2, 
				CASE WHEN telMaison = "" THEN "" ELSE CONCAT( "(", LEFT(telMaison,3) , ") " , MID(telMaison,4,3) , "-", RIGHT(telMaison,4)) END AS TelMaison,
				CASE WHEN telMobile = "" THEN "" ELSE CONCAT( "(", LEFT(telMobile,3) , ") " , MID(telMobile,4,3) , "-", RIGHT(telMobile,4)) END AS TelMobile,
				TelTravail,
				CASE WHEN P.Id = IdParentPrincipal THEN 1 ELSE 0 END AS Ordre,
                                CONCAT(P.Prenom," ",P.NomFamille) AS NomComplet, F.id AS IdLienFamille
				FROM joueurs J
				INNER JOIN familles F ON F.IdJoueur = J.id
                                INNER JOIN adultes P ON P.id = F.IdParent
				WHERE J.id = '.$id.'
				ORDER BY Ordre DESC');
        return $rs;
    }
    
    public function rechercherParent($data) {
        $rs = $this->query('SELECT * FROM adultes Adulte
                                WHERE Prenom = "'.$data['Prenom'].'"
                                AND NomFamille = "'.$data['NomFamille'].'"
                                AND ((Adresse = "'.$data['Adresse'].'" AND Adresse != "")
                                OR (TelMaison = "'.preg_replace('/[^0-9.]+/', '',$data['TelMaison']).'" AND TelMaison != "")
                                OR (TelMobile = "'.preg_replace('/[^0-9.]+/', '',$data['TelMobile']).'" AND TelMobile != "")
                                OR (Courriel1 = "'.$data['Courriel1'].'" AND Courriel1 != "")
                                OR (Courriel1 = "'.$data['Courriel2'].'" AND Courriel1 != "")
                                OR (Courriel2 = "'.$data['Courriel1'].'" AND Courriel2 != "")
                                OR (Courriel2 = "'.$data['Courriel2'].'" AND Courriel2 != ""))');
        return $rs;
    }
    
    public function listeParentsEquipe($idEquipe) {
        $rs = $this->query('SELECT P.id, CONCAT(P.Prenom," ",P.NomFamille) AS NomComplet
				FROM joueurs J
                                INNER JOIN inscriptions I ON I.IdJoueur = J.id AND Saison = YEAR(NOW())
				INNER JOIN familles F ON F.IdJoueur = J.id
                                INNER JOIN adultes P ON P.id = F.IdParent
				WHERE I.IdEquipe = '.$idEquipe.'
				ORDER BY P.NomFamille, P.Prenom');
        return $rs;
    }

}