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
class Equipe extends Model {
    public $name = 'Equipe';
    
    public function findClassesByCategorie($idCategorie) {
        $rs = $this->query('SELECT DISTINCT Classe FROM equipes E WHERE IdCategorie = '.$idCategorie.' AND Saison = YEAR(NOW())');
        return $rs;
    }

    public function findEquipesByCategorie($categorie, $classe, $local=1) {
        if($categorie == 0) {
            $sql = 'SELECT E.IdEquipe, E.NomEquipe, A.Ville, C.NomCategorie, E.Classe, IFNULL(Ent.IdParent,0) AS IdEntraineur
                        FROM Equipes E 
                        INNER JOIN Associations A ON A.IdAssociation = E.IdAssociation
                        INNER JOIN Niveaux N ON N.idNiveau = E.idNiveau
                        LEFT JOIN Entraineurs Ent ON Ent.IdEquipe = E.IdEquipe AND Titre = 1
                        WHERE Saison = YEAR(NOW())';
        }

        else {
            $sql = 'SELECT IdEquipe, NomEquipe, A.Ville FROM Equipes E 
                        INNER JOIN Associations A ON A.IdAssociation = E.IdAssociation
                        WHERE IdCategorie = '.$categorie.' AND Classe = "'.$classe.'"
                        AND Saison = YEAR(NOW())';
        }

        if($local == 1) {
            $sql .= ' AND A.Ville = "St-Hyacinthe"';
        }

        $sql .= ' ORDER BY A.IdAssociation, E.IdNiveau, Categorie, NomEquipe';

        $rs = $this->query($sql);
        return $rs;
    }
}