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
class Joueur extends Model {
    
    public function findJoueursParParent($idParent,$inscrit=0) {
        
        $rs = $this->query('SELECT VueJoueur.*
                        FROM vue_joueurs VueJoueur
                        INNER JOIN familles F ON F.idJoueur = VueJoueur.idJoueur
                        WHERE F.idParent = '.$idParent.'
                            AND Reinscrit >= '.$inscrit);
        return $rs;
    }
    
    public function findInscritsParParent($idParent,$saison) {
        
        $rs = $this->query('SELECT VueImpot.*
                        FROM vue_impots VueImpot
                        INNER JOIN familles F ON F.idJoueur = VueImpot.idJoueur
                        WHERE F.idParent = '.$idParent.'
                            AND VueImpot.Saison = '.$saison);
        return $rs;
    }
    
    public function getListeCourrielsParCat ($cat=null, $classe=null, $equipe=null) {
        $rs = $this->query('SELECT DISTINCT P.Courriel1 AS Courriel
                            FROM joueurs J
                            INNER JOIN familles F ON F.IdJoueur = J.id
                            INNER JOIN adultes P ON P.id = F.IdParent
                            LEFT JOIN inscriptions I ON J.id = I.idJoueur
                            LEFT JOIN categories c ON c.id = I.idCategorie
                            LEFT JOIN equipes e ON e.id = I.idEquipe
                            WHERE c.NomCategorie= '.$cat.'
                                AND IFNULL(I.Classe,"") = '.$classe.'
                                AND IFNULL(e.NomEquipe,"") = '.$equipe.'
                                AND IFNULL(Courriel1,"") != ""
                                AND I.Saison = (SELECT Valeur FROM configs WHERE NomConfig = "Annee inscription")
                            UNION
                            SELECT DISTINCT P.Courriel2 AS Courriel
                            FROM joueurs J
                            INNER JOIN familles F ON F.IdJoueur = J.id
                            INNER JOIN adultes P ON P.id = F.IdParent
                            LEFT JOIN inscriptions I ON J.id = I.idJoueur
                            LEFT JOIN categories c ON c.id = I.idCategorie
                            LEFT JOIN equipes e ON e.id = I.idEquipe
                            WHERE c.NomCategorie= '.$cat.'
                                AND IFNULL(I.Classe,"") = '.$classe.'
                                AND IFNULL(e.NomEquipe,"") = '.$equipe.'
                                AND IFNULL(Courriel2,"") != ""
                                AND I.Saison = (SELECT Valeur FROM configs WHERE NomConfig = "Annee inscription")');
        return $rs;
    }
    
    public function getListeCourriels($data) {
        $rs = $this->query('SELECT DISTINCT P.Courriel1 AS Courriel
                            FROM joueurs J
                            INNER JOIN familles F ON F.IdJoueur = J.id
                            INNER JOIN adultes P ON P.id = F.IdParent
                            LEFT JOIN inscriptions I ON J.id = I.idJoueur
                            WHERE IFNULL(Courriel1,"") != ""
                                AND Saison >= (SELECT Valeur FROM configs WHERE NomConfig = "Annee equipe")
                                AND IdCategorie IN ('.$data['rallyecap'].', '.$data['atome'].', '.$data['moustique'].', '.$data['peewee'].', '.$data['bantam'].', '.$data['midget'].')
                                AND IdCategorie > 0
                            UNION
                            SELECT DISTINCT P.Courriel2 AS Courriel
                            FROM joueurs J
                            INNER JOIN familles F ON F.IdJoueur = J.id
                            INNER JOIN adultes P ON P.id = F.IdParent
                            LEFT JOIN inscriptions I ON J.id = I.idJoueur
                            WHERE IFNULL(Courriel2,"") != ""
                                AND Saison >= (SELECT Valeur FROM configs WHERE NomConfig = "Annee equipe")
                                AND IdCategorie IN ('.$data['rallyecap'].', '.$data['atome'].', '.$data['moustique'].', '.$data['peewee'].', '.$data['bantam'].', '.$data['midget'].')'
            . '                 AND IdCategorie > 0');
        return $rs;
    }
    
    public function getlisteCourrielsJoueur($idJoueur) {
        $rs = $this->query('SELECT DISTINCT P.Courriel1 AS Courriel
                                FROM familles F
                                INNER JOIN adultes P ON P.id = F.IdParent
                                WHERE IdJoueur = '.$idJoueur.'
                            UNION
                            SELECT DISTINCT P.Courriel2 AS Courriel
                                FROM familles F
                                INNER JOIN adultes P ON P.id = F.IdParent
                                WHERE IdJoueur = '.$idJoueur);
        return $rs;
    }
}