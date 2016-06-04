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

$cakeDescription = __d('bshadmin', 'bSHadmin - Plateforme administrative de Baseball St-Hyacinthe');
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

		echo $this->Html->css('style');
                echo $this->Html->css('buttons');
                echo $this->Html->css('forms');
                echo $this->Html->css('grids');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css">
</head>
<body>
    <div id="container">
        <div>
	    <table width="100%" border="0" cellspacing="0">
		  <tr>
		    <td id="role">
			  <span><?=$this->Html->link('Site principal', array('controller' => 'accueil', 'action' => 'index')); ?> | <a href="mailto:tomlafleur25@gmail.com">Contacter webmaster</a></span>
			</td>
		    <? if($this->Session->read('User.role') != NULL) { ?>
                        <td id="connect">
			  <span><?=$this->Session->read('User.nomComplet');?> 
                            
                            &nbsp; Role: <?=$this->Session->read('User.nomRole'); ?>
                            <? if($this->Session->read('User.role2') != '') echo ' | '.$this->Html->link($this->Session->read('User.nomRole2'), array('controller' => 'users', 'action' => 'changerRole',$this->Session->read('User.role2'))); ?> | 
                            <?=$this->Html->link('Mon  profil', array('controller' => 'users', 'action' => 'profil'));?> | 
			    <?=$this->Html->link('Se déconnecter', array('controller' => 'users', 'action' => 'logout'));?><span>
			</td>
                    <? } ?>
		  </tr>
		</table>
      </div>
        <div id="header">
            <?=$this->Html->image('headers_admin.jpg',array('id' => 'banniere')); ?>
        </div>

        <nav>
            <? if($this->Session->read('User.role') != NULL && $this->Session->read('User.role') != 'parent') {
                echo $this->element('menu_'.$this->Session->read('User.role'));
                echo $this->element('side_calendar',array('var' => 'test'),
                array('cache' => array('key' => 'first_use', 'config' => 'view_long')));
            } elseif($this->Session->read('User.role') == 'parent') {
                echo $this->element('menu_parent');
            } else {
                echo $this->element('menu_connect');
            } ?>
            
        </nav>
        
        <section>
            <?=$this->Session->flash('bad');?>
            <?=$this->Session->flash('good');?>
            <?php echo $this->fetch('content'); ?>
        </section>
        
        <footer>
            <?=$this->Html->image('logo_sh.png', array('align' => 'center')); ?>
            <span>Copyright © 2015-<?=date('Y'); ?> Baseball Saint-Hyacinthe</span>
            <?=$this->Html->image('logo_sh.png', array('align' => 'center')); ?>
        </footer>
        
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>
