<table id="calendrier" border="0" cellpadding="1" cellspacing="1">
    <thead>
      <tr>
        <th id="impair"><?=($mois > 1) ? $this->Html->link('<',array('action' => 'changerMois', ($mois-1))) : '';?></th>
        <th id="impair" colspan="5"><?php echo $nomMois ?></th>
        <th id="impair"><?=($mois < 12) ? $this->Html->link('>',array('action' => 'changerMois', ($mois+1))) : '';?></th>
      </tr>
      <tr>
        <th id="colhead">L</th>
        <th id="colhead">M</th>
        <th id="colhead">M</th>
        <th id="colhead">J</th>
        <th id="colhead">V</th>
        <th id="colhead">S</th>
        <th id="colhead">D</th>
      </tr>
    </thead>
    <tbody>
      <tr>
<?php 
if($this->Session->read('User.role') == 'terrain' || 
    $this->Session->read('User.role') == 'entraineur') {
    $controller = 'evenements';
} else {
    $controller = 'parties';
}
$i = 0;
foreach($jours as $jour) { 
    if($i++ % 7 == 0) echo '</tr><tr>';
    $color = ($jour['D']['NoMois'] == $mois) ? 'pair' : 'impair';
    $color = ($jour['D']['NoMois'] == $mois && $jour['D']['date'] == date('Y-m-d')) ? 'today' : $color; 
?>
          <td id="<?=$color;?>">
            <? if($jour['Reserve'] > 0) echo '<b>'; ?>
            <?=$this->Html->link($jour['D']['JourMois'],
                                array('controller' => $controller, 'action' => 'jour', $jour['D']['date']),
                                array('target' => '_top')
            );?>
            <? if($this->Session->read('User.role') != 'admin' && $jour['Reserve'] > 0) echo '</b>'; ?>
          </td>
<? } ?>
	</tr>
      </tbody>
    </table></br>