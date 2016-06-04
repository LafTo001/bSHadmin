<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('Baseball Saint-Hyacinthe', 'Baseball Saint-Hyacinthe');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>

	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>
		<?php //echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('style_accueil');
                echo $this->Html->css('buttons');
                echo $this->Html->css('forms_accueil');
                echo $this->Html->css('grids');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
    <div id="container">
        <div>
	    <table width="100%" border="0" cellspacing="0">
		  <tr>
		    <td id="role">
			  <span><?=$this->Html->link('Accueil', 
                              array('controller' => 'accueil', 'action' => 'index'), array('escape' => false)); ?></span>
			</td>
                        <td id="connect">
		    <? if($this->Session->read('User.role') != NULL) { ?>
			  <span><?=$this->Session->read('User.nomComplet');?> 
                            
                            &nbsp; <?=$this->Html->link('Section privée', array('controller' => 'pages', 'action' => 'home')); ?> | 
			    <?=$this->Html->link('Se déconnecter', array('controller' => 'users', 'action' => 'logout'));?><span>
			</td>
                    <? }  else { ?>
                        <span><?=$this->Html->link('Connexion', array('controller' => 'users', 'action' => 'login'));?> |
			    <?=$this->Html->link('Inscription', array('controller' => 'parents', 'action' => 'inscriptionEnLigne'));?><span>
                    <? } ?>
                        </td>
		  </tr>
		</table>
      </div>
        <div id="header">
            <?=$this->Html->image('headers_accueil2.jpg',array('id' => 'banniere')); ?>
        </div>

        <nav id="cssmenu">
            <ul>
                <li><?=$this->Html->link('INSCRIPTIONS',array('controller' => 'accueil', 'action' => 'inscriptions')); ?></li>
                <li><?=$this->Html->link('ÉVÉNEMENTS',array('controller' => 'accueil', 'action' => 'evenements')); ?></li>
                <li><?=$this->Html->link('NOS ÉQUIPES',array('controller' => 'equipes', 'action' => 'index')); ?></li>
                <li><?=$this->Html->link('LIGUE LOCALE',array('controller' => 'accueil', 'action' => 'ligue')); ?></li>
                <li><?=$this->Html->link('RALLYE CAP',array('controller' => 'rallyecap', 'action' => 'index')); ?></li>
                <li><?=$this->Html->link('NOS TERRAINS',array('controller' => 'terrain', 'action' => 'liste')); ?></li>
                <li><?=$this->Html->link('PHOTOS',array('controller' => 'photos', 'action' => 'liste')); ?></li>
                <li><?=$this->Html->link('CONTACTS',array('controller' => 'accueil', 'action' => 'contacts')); ?></li>
            </ul>  
        </nav>
        
        <section>
            <?=$this->Session->flash('bad');?>
            <?=$this->Session->flash('good');?>
            <?php echo $this->fetch('content'); ?>
        </section>
        
        <footer>
            <span>Copyright © 2015 Baseball Saint-Hyacinthe</span>
        </footer>
        
    </div>
    <?php  echo $this->element('sql_dump'); ?>
</body>
</html>
