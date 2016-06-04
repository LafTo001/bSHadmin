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
class VueEquipe extends Model {
    public $name = 'VueEquipe';
    
    public function findClassesByCategorie() {
        $rs = $this->query('SELECT DISTINCT Classe FROM Equipes WHERE IdCategorie = '.$_SESSION['idCategorie'].' AND Saison = 2014'); //YEAR(NOW())';
        return $rs;
    }

    public function findEquipesByCategorie($categorie, $classe, $local=1) {
        if($categorie == 0) {
            $sql = 'SELECT idEquipe, NomEquipe, Ville, NomCategorie, Classe, IFNULL(Ent.IdParent,0) AS IdEntraineur
                        FROM vue_equipes E 
                        LEFT JOIN Entraineurs Ent ON Ent.IdEquipe = E.IdEquipe AND Titre = 1
                        WHERE Saison = 2014'; //YEAR(NOW())';
        }

        else {
            $sql = 'SELECT idEquipe, NomEquipe, Ville FROM vue_equipes E 
                        WHERE IdCategorie = '.$categorie.' AND Classe = "'.$classe.'"
                        AND Saison = 2014'; //YEAR(NOW())';
        }

        if($local == 1) {
            $sql .= ' AND Ville = "St-Hyacinthe"';
        }

        $sql .= ' ORDER BY idAssociation, idCategorie, Classe, NomEquipe';

        $rs = $this->query($sql);
        return $rs;
    }
}