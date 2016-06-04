<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PhotosController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
    public function display() {
            $path = func_get_args();

            $count = count($path);
            if (!$count) {
                    return $this->redirect('/');
            }
            $page = $subpage = $title_for_layout = null;

            if (!empty($path[0])) {
                    $page = $path[0];
            }
            if (!empty($path[1])) {
                    $subpage = $path[1];
            }
            if (!empty($path[$count - 1])) {
                    $title_for_layout = Inflector::humanize($path[$count - 1]);
            }
            $this->set(compact('page', 'subpage', 'title_for_layout'));

            try {
                    $this->render(implode('/', $path));
            } catch (MissingViewException $e) {
                    if (Configure::read('debug')) {
                            throw $e;
                    }
                    throw new NotFoundException();
            }
    }
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('liste','album','afficher');
    }
    
    public function index() {
        $gallerie = $this->Photo->find('all');
        //var_dump($gallerie);
        $this->set(compact('gallerie'));
    }
    
    public function albums() {
        $this->loadModel('VueAlbum');
        $albums = $this->VueAlbum->findAllByIdusager($this->Auth->User('id'));
        $this->set(compact('albums'));
    }
    
    public function liste() {
        $this->loadModel('VueAlbum');
        $albums = $this->VueAlbum->find('all');
        $this->set(compact('albums'));
        
        $this->layout = 'accueil';
    }
    
    public function album($id=0) {
        
        /*if($id == null) {
            $this->redirect(array('controller' => 'photos', 'action' => 'index'));
        }*/
        
        $this->paginate = array(
                'limit' => 12,
                'order' => array('Photo.id' => 'asc'),
                'conditions' => array('IdAlbum' => $id,
                                      'dateSuppression' => null)
        );
  
            
        $this->loadModel('Album');
        $album = $this->Album->findById($id);
        $this->set(compact('album','perso'));
        
        $gallerie = $this->paginate('Photo');
        $this->set(compact('gallerie'));
        
        $this->set('idAlbum',$id);
        
        $perso = false;
        if($album['Album']['IdUsager'] == $this->Auth->User('id')) {
            $perso = true;
        } else {
            $this->layout = 'accueil';
        }
        $this->set(compact('perso'));
            
    }
    
    public function afficher($id) {
        
        if($this->request->is('Post')) {
            $this->Photo->id = $id;
            $this->Photo->save($this->request->data);
        }
        
        $photo = $this->Photo->findById($id);
        $autres = $this->Photo->find('neighbors', array('field' => 'id', 'value' => $id, 
                                                    'conditions' => array('IdAlbum' => $photo['Photo']['IdAlbum'],
                                                                          'DateSuppression' => null)
        ));
        $this->set(compact('photo','autres'));
        $this->set('idPhoto', $id);
        $this->set('idAlbum', $photo['Photo']['IdAlbum']);
        $this->set('albums', $this->selectAlbum());
        
        $perso = false;
        if($photo['Photo']['IdUsager'] == $this->Auth->User('id')) {
            $perso = true;
        } else {
            $this->layout = 'accueil';
        }
        $this->set(compact('perso'));
    }

    public function ajouter($idAlbum=0) {
        if ($this->request->is('post')) {
            if (isset($this->request->data['Photo'])) {
                //var_dump($this->request->data);
                $idAlbum = $this->request->data['Photo']['IdAlbum'];
                //allowed image types
                $imageTypes = array("image/jpeg", "image/gif", "image/png");
                //upload folder - make sure to create one in webroot
                $uploadFolder = "img/gallerie";
                //full path to upload folder
                $uploadPath = WWW_ROOT . $uploadFolder;
                //echo  $uploadPath;
                
                foreach($this->request->data['Photo']['files'] as $image) {
                    //var_dump($image);
                    //check if image type fits one of allowed types
                    foreach ($imageTypes as $type) {
                        if ($type == $image['type']) {
                          //check if there wasn't errors uploading file on serwer
                            if ($image['error'] == 0) {
                                 //image file name
                                $imageName = $image['name'];

                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $imageName)) {
                                    //create full filename with timestamp
                                    $imageName = date('YmdHis').'_'.$imageName;
                                }
                                //create full path with image name
                                $full_image_path = $uploadPath . '/' . $imageName;
                                //upload image to upload folder
                                if (move_uploaded_file($image['tmp_name'], $full_image_path)) {

                                    //enregister la photo dans la base de données
                                    $this->Photo->create();
                                    $photo['Photo']['Filename'] = $imageName;
                                    $photo['Photo']['DateAjout'] = date('Y-m-d H:i:s');
                                    $photo['Photo']['IdAlbum'] = $idAlbum;
                                    $photo['Photo']['IdUsager'] = $this->Auth->User('id');
                                    $photo['Photo']['Confirmation'] = $this->Session->read('User.role') == 'admin';
                                    $photo['Photo']['Description'] = $this->request->data['Photo']['Description'];

                                    $this->Photo->save($photo);

                                    //redimensionement de l'image
                                    $file = $uploadFolder.'/'.$imageName;
                                    $save = $uploadFolder.'/'.$imageName;
                                    list($width, $height) = getimagesize($file);

                                    $maxWidth = 800;
                                    $maxHeight = 800;
                                    $modWidth = $width;
                                    $modHeight = $height;

                                    if($width >= $maxWidth) {
                                        $diff = $width / $maxWidth;
                                        $modWidth = $maxWidth;
                                        $modHeight = $height / $diff;
                                    }

                                    if($modHeight >= $maxHeight) {
                                        $diff = $modHeight / $maxHeight;
                                        $modHeight = $maxHeight;
                                        $modWidth = $modWidth / $diff;
                                    }

                                    $tn = imagecreatetruecolor($modWidth, $modHeight);
                                    $image = imagecreatefromjpeg($file); 
                                    imagecopyresampled($tn, $image, 0, 0, 0, 0, $modWidth, $modHeight, $width, $height);

                                    imagejpeg($tn, $save, 100);

                                    //création de l'image miniature
                                    $minWidth = $modWidth / 4;
                                    $minHeight = $modHeight / 4;
                                    $save = $uploadFolder . "/min_" . $imageName;

                                    //echo $minWidth.' '.$minHeight;

                                    $tn = imagecreatetruecolor($minWidth, $minHeight);
                                    $image = imagecreatefromjpeg($file); 
                                    imagecopyresampled($tn, $image, 0, 0, 0, 0, $minWidth, $minHeight, $modWidth, $modHeight);

                                    imagejpeg($tn, $save, 100);

                                    $this->Session->setFlash('File saved successfully');
                                    $this->set('imageName',$imageName);

                                    $this->request->data = null;

                                } else {
                                    $this->Session->setFlash('There was a problem uploading file. Please try again.');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading file.');
                            }
                            break;
                        } else {
                            $this->Session->setFlash('Unacceptable file type');
                        }
                    } // end foreach types
                } // end foreach images
            } // end if photo
            
            $this->redirect(array('action' => 'album', $idAlbum));
            
        } else {
            $this->set('albums', $this->selectAlbum());
            $this->set(compact('idAlbum'));
        }
    }
    
    public function ajout_album() {
        if($this->request->is('post')) {
            $this->loadModel('Album');
            $this->request->data['Album']['IdUsager'] = $this->Auth->User('id');
            $this->request->data['Album']['DerniereModif'] = date('Y-m-d H:i:s');
            if($this->Album->save($this->request->data)) {
                $id = $this->Album->id;
            } else {
                echo 'fail';
            }
            
            $this->redirect(array('action' => 'album',$id));
        }
    }
    
    public function supprimer($id,$confirm = 0) {
        $photo = $this->Photo->findById($id);
        if($confirm == 1 && ($photo['Photo']['IdUsager'] == $this->Auth->User('id') || 
                                $this->Session->read('User.role') == 'admin')) {
            $this->Photo->id = $id;
            $this->Photo->saveField('DateSuppression', date('Y-m-d H:i:s'));
            //effacer miniature
            $file = new File('gallerie/min_'.$photo['Photo']['Filename']);
            $file->delete();
            //effacer photo principale
            $file = new File('gallerie/'.$photo['Photo']['Filename']);
            $file->delete();
            
            $this->redirect(array('action' => 'album', $photo['Photo']['IdAlbum']));
        }
        $this->set(compact('photo'));
    }
    
    function selectAlbum() {
        $this->loadModel('Album');
        $albums = $this->Album->find('list',array(
                        'fields' => array('id','NomAlbum'),
                        'conditions' => array('IdUsager' => $this->Auth->User('id')
            )));
        
        return $albums;
    }
}