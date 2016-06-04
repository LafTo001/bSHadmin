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
//App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends Model {
    public $name = 'User';
    
    public $validate = array(
        'username' => array(
            'nonEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Un nom d\'usager est requis',
                'allowEmpty' => false
            ),
            'between' => array( 
                'rule' => array('between', 8, 50), 
                'required' => true, 
                'message' => 'Usernames must be between 5 to 15 characters'
            ),
             'unique' => array(
                'rule'    => array('isUniqueUsername'),
                'message' => 'This username is already in use'
            ),
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            ),
            'min_length' => array(
                'rule' => array('minLength', '6'),  
                'message' => 'Password must have a mimimum of 6 characters'
            )
        ),
         
        'password_confirm' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please confirm your password'
            ),
             'equaltofield' => array(
                'rule' => array('equaltofield','password'),
                'message' => 'Both passwords must match.'
            )
        ),
         
        'email' => array(
            'required' => array(
                'rule' => array('email', true),    
                'message' => 'Please provide a valid email address.'   
            ),
             'unique' => array(
                'rule'    => array('isUniqueEmail'),
                'message' => 'This email is already in use',
            ),
            'between' => array( 
                'rule' => array('between', 6, 60), 
                'message' => 'Usernames must be between 6 to 60 characters'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('king', 'queen', 'bishop', 'rook', 'knight', 'pawn')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
         
         
        'password_update' => array(
            'min_length' => array(
                'rule' => array('minLength', '6'),   
                'message' => 'Password must have a mimimum of 6 characters',
                'allowEmpty' => true,
                'required' => false
            )
        ),
        'password_confirm_update' => array(
             'equaltofield' => array(
                'rule' => array('equaltofield','password_update'),
                'message' => 'Both passwords must match.',
                'required' => false,
            )
        )
 
         
    );
     
        /**
     * Before isUniqueUsername
     * @param array $options
     * @return boolean
     */
    function isUniqueUsername($check) {
 
        /*$username = $this->find(
            'first',
            array(
                'fields' => array(
                    'User.IdParent',
                    'User.username'
                ),
                'conditions' => array(
                    'User.username' => $check['username']
                )
            )
        );
 
        if(!empty($username)){
            var_dump($this->data[$this->alias]);
            var_dump($username['User']['idParent']);
            if($this->data[$this->alias]['idParent'] === $username['User']['idParent']){
                return true; 
            }else{
                return false; 
            }
        }else{
            return true; 
        }*/
        return true;
    }
 
    /**
     * Before isUniqueEmail
     * @param array $options
     * @return boolean
     */
    function isUniqueEmail($check) {
 
        $email = $this->find(
            'first',
            array(
                'fields' => array(
                    'User.id'
                ),
                'conditions' => array(
                    'User.email' => $check['email']
                )
            )
        );
 
        if(!empty($email)){
            if($this->data[$this->alias]['id'] == $email['User']['id']){
                return true; 
            }else{
                return false; 
            }
        }else{
            return true; 
        }
    }
     
    public function alphaNumericDashUnderscore($check) {
        // $data array is passed using the form field name as the key
        // have to extract the value to make the function generic
        $value = array_values($check);
        $value = $value[0];
 
        return preg_match('/^[a-zA-Z0-9_ \-]*$/', $value);
    }
     
    public function equaltofield($check,$otherfield) 
    { 
        //get name of field 
        $fname = ''; 
        foreach ($check as $key => $value){ 
            $fname = $key; 
            break; 
        } 
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname]; 
    } 
 
    /**
     * Before Save
     * @param array $options
     * @return boolean
     */
     public function beforeSave($options = array()) {
        // hash our password
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
         
        // if we get a new password, hash it
        /*if (isset($this->data[$this->alias]['password_update']) &amp;&amp; !empty($this->data[$this->alias]['password_update'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password_update']);
        }*/
     
        // fallback to our parent
        return parent::beforeSave($options);
    }
    
    public function findByUsername($username) {
        $rs = $this->query('SELECT U.id, U.username, P.Prenom, CONCAT(P.Prenom," ",P.NomFamille) AS NomComplet,
                                U.IdParent, U.Admin, U.Entraineur, U.Terrain, U.Arbitre,
                                U.password, Ent.IdEquipe, P.Courriel1, U.Role
                                FROM users U
                                INNER JOIN adultes P ON P.id = U.IdParent
                                LEFT JOIN entraineurs Ent ON Ent.IdParent = P.id
                                LEFT JOIN equipes E ON E.id = Ent.IdEquipe
                                WHERE (Username = "'.$username.'"
                                     OR Courriel1 = "'.$username.'") AND YEAR(NOW())
                                ORDER BY Ent.titre');
        if(!empty($rs)) {
            return $rs[0];
        }
    }
    /*if(!empty($rs))
    {
        $user = new Usager($q->fetch(PDO::FETCH_ASSOC));
        $user->asgNbEquipe($q->rowCount());

        if($user->terrain() == 1)
                $q = $bdd->prepare('SELECT IdTerrain FROM Terrains WHERE IdUsager = "'.$user->id().'"');

        else
                $q = $bdd->prepare('SELECT IdTerrain FROM Terrains WHERE NomTerrain = "Douville 1"');

        $q->execute() or die(print_r($q->errorInfo()));
        if($q->rowCount() > 0)
                $user->hydrate($q->fetch(PDO::FETCH_ASSOC));
        $user->asgNbTerrain($q->rowCount());

        return $user;
    }

    return 0;
    }*/
    
    public function findByCourriel($courriel, $prenom, $nomFamille) {
        $rs = $this->query('SELECT P.id AS IdParent, 
                                CASE WHEN U.id IS NULL THEN 0 ELSE 1 END AS UsagerExiste,
                                CASE WHEN E.id IS NULL THEN 0 ELSE 1 END AS Entraineur
                                FROM adultes P
                                LEFT JOIN users U ON P.id = U.IdParent
                                LEFT JOIN entraineurs Ent ON Ent.IdParent = P.id
                                LEFT JOIN equipes E ON E.id = Ent.IdEquipe AND Saison = YEAR(NOW())
                                WHERE (Courriel1 = "'.$courriel.'" OR Courriel2 = "'.$courriel.'")
                                    AND Prenom = "'.$prenom.'" AND NomFamille = "'.$nomFamille.'"');
        return $rs[0];
    }
    
    public function findParentsPrincipaux() {
        $rs = $this->query('SELECT DISTINCT p.*
                                FROM joueurs j
                                INNER JOIN adultes p ON p.id = j.idParentPrincipal');
        return $rs;
    }
    
    public function findParentsPrincipauxNonUsagers() {
        $rs = $this->query('SELECT DISTINCT p.*
                                FROM joueurs j
                                INNER JOIN adultes p ON p.id = j.idParentPrincipal
                                LEFT JOIN users u ON u.idParent = p.id
                                WHERE u.id IS NULL');
        return $rs;
    }
}