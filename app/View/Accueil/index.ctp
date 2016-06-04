<!-- /acceuil/index.ctp -->
 
<script type="text/javascript">
    var pausecontent = new Array();

    <? foreach($listePauseContents as $pausecontent) { 
        echo $pausecontent;
    } ?>
        
    /*pausecontent[0]= "<div id='resultats'><span><b>18/08 19h30 &nbsp; Atome A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Cards</td><td id='pointage'>6</td></tr><tr><td>St-Hyacinthe Condors</td><td id='pointage'>11</td></tr></table></div><div id='resultats'><span><b>17/08 19h00 &nbsp; Bantam A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Condors</td><td id='pointage'>10</td></tr><tr><td>Boucherville Seigneurs</td><td id='pointage'>11</td></tr></table></div><div id='resultats'><span><b>17/08 19h00 &nbsp; Pee-wee A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Cards</td><td id='pointage'>4</td></tr><tr><td>St-Hyacinthe Condors</td><td id='pointage'>5</td></tr></table></div><div id='resultats'><span><b>17/08 19h00 &nbsp; Pee-wee A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Cards</td><td id='pointage'>4</td></tr><tr><td>St-Hyacinthe Condors</td><td id='pointage'>5</td></tr></table></div>";
    pausecontent[1]= "<div id='resultats'><span><b>17/08 19h00 &nbsp; Moustique A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Condors</td><td id='pointage'>17</td></tr><tr><td>Chambly Royaux</td><td id='pointage'>9</td></tr></table></div><div id='resultats'><span><b>17/08 19h00 &nbsp; Atome B</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Chouettes B</td><td id='pointage'>14</td></tr><tr><td>St-Hyacinthe Chouettes R</td><td id='pointage'>15</td></tr></table></div><div id='resultats'><span><b>16/08 13h00 &nbsp; Bantam A</b></span><br/><table><tr><td id='equipe'>St-Jean</td><td id='pointage'>2</td></tr><tr><td>St-Hyacinthe Condors</td><td id='pointage'>10</td></tr></table></div>";
    pausecontent[2]= "<div id='resultats'><span><b>16/08 10h00 &nbsp; Atome A</b></span><br/><table><tr><td id='equipe'>St-Hubert Ambassadeurs</td><td id='pointage'>1</td></tr><tr><td>St-Hyacinthe Cards</td><td id='pointage'>0</td></tr></table></div><div id='resultats'><span><b>16/08 10h00 &nbsp; Atome B</b></span><br/><table><tr><td id='equipe'>St-Amable Vipères</td><td id='pointage'>10</td></tr><tr><td>St-Hyacinthe Chouettes R</td><td id='pointage'>14</td></tr></table></div><div id='resultats'><span><b>15/08 19h00 &nbsp; Pee-wee A</b></span><br/><table><tr><td id='equipe'>St-Hubert Ambassadeurs</td><td id='pointage'>11</td></tr><tr><td>St-Hyacinthe Cards</td><td id='pointage'>7</td></tr></table></div>";
    pausecontent[3]= "<div id='resultats'><span><b>15/08 16h00 &nbsp; Moustique A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Condors</td><td id='pointage'>6</td></tr><tr><td>Sorel-Tracy Mariniers</td><td id='pointage'>3</td></tr></table></div><div id='resultats'><span><b>15/08 13h00 &nbsp; Atome B</b></span><br/><table><tr><td id='equipe'>Sorel-Tracy Mariniers 1</td><td id='pointage'>13</td></tr><tr><td>St-Hyacinthe Condors</td><td id='pointage'>10</td></tr></table></div><div id='resultats'><span><b>15/08 13h00 &nbsp; Atome A</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Condors</td><td id='pointage'>16</td></tr><tr><td>Roussillon Expos 1</td><td id='pointage'>4</td></tr></table></div>";
    pausecontent[4]= "<div id='resultats'><span><b>15/08 13h00 &nbsp; Bantam B</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Condors</td><td id='pointage'>4</td></tr><tr><td>Brossard</td><td id='pointage'>11</td></tr></table></div><div id='resultats'><span><b>15/08 10h00 &nbsp; Moustique B</b></span><br/><table><tr><td id='equipe'>St-Hyacinthe Condors</td><td id='pointage'>8</td></tr><tr><td>Mont-St-Hilaire Express </td><td id='pointage'>0</td></tr></table></div><div id='resultats'><span><b>14/08 19h00 &nbsp; Atome A</b></span><br/><table><tr><td id='equipe'>Sorel-Tracy Mariniers</td><td id='pointage'>4</td></tr><tr><td>St-Hyacinthe Condors</td><td id='pointage'>5</td></tr></table></div>";
*/

</script>

<script type="text/javascript">
/***********************************************
* Pausing up-down scroller- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function pausescroller(content, divId, divClass, delay){
this.content=content //message array content
this.tickerid=divId //ID of ticker div to display information
this.delay=delay //Delay between msg change, in miliseconds.
this.mouseoverBol=0 //Boolean to indicate whether mouse is currently over scroller (and pause it if it is)
this.hiddendivpointer=1 //index of message array for hidden div
document.write('<div id="'+divId+'" class="'+divClass+'" style="position: relative; overflow: hidden"><div class="innerDiv" style="position: absolute; width: 100%" id="'+divId+'1">'+content[0]+'</div><div class="innerDiv" style="position: absolute; width: 100%; visibility: hidden" id="'+divId+'2">'+content[1]+'</div></div>')
var scrollerinstance=this
if (window.addEventListener) //run onload in DOM2 browsers
window.addEventListener("load", function(){scrollerinstance.initialize()}, false)
else if (window.attachEvent) //run onload in IE5.5+
window.attachEvent("onload", function(){scrollerinstance.initialize()})
else if (document.getElementById) //if legacy DOM browsers, just start scroller after 0.5 sec
setTimeout(function(){scrollerinstance.initialize()}, 500)
}

// -------------------------------------------------------------------
// initialize()- Initialize scroller method.
// -Get div objects, set initial positions, start up down animation
// -------------------------------------------------------------------

pausescroller.prototype.initialize=function(){
this.tickerdiv=document.getElementById(this.tickerid)
this.visiblediv=document.getElementById(this.tickerid+"1")
this.hiddendiv=document.getElementById(this.tickerid+"2")
this.visibledivtop=parseInt(pausescroller.getCSSpadding(this.tickerdiv))
//set width of inner DIVs to outer DIV's width minus padding (padding assumed to be top padding x 2)
this.visiblediv.style.width=this.hiddendiv.style.width=this.tickerdiv.offsetWidth-(this.visibledivtop*2)+"px"
this.getinline(this.visiblediv, this.hiddendiv)
this.hiddendiv.style.visibility="visible"
var scrollerinstance=this
document.getElementById(this.tickerid).onmouseover=function(){scrollerinstance.mouseoverBol=1}
document.getElementById(this.tickerid).onmouseout=function(){scrollerinstance.mouseoverBol=0}
if (window.attachEvent) //Clean up loose references in IE
window.attachEvent("onunload", function(){scrollerinstance.tickerdiv.onmouseover=scrollerinstance.tickerdiv.onmouseout=null})
setTimeout(function(){scrollerinstance.animateup()}, this.delay)
}


// -------------------------------------------------------------------
// animateup()- Move the two inner divs of the scroller up and in sync
// -------------------------------------------------------------------

pausescroller.prototype.animateup=function(){
var scrollerinstance=this
if (parseInt(this.hiddendiv.style.top)>(this.visibledivtop+5)){
this.visiblediv.style.top=parseInt(this.visiblediv.style.top)-5+"px"
this.hiddendiv.style.top=parseInt(this.hiddendiv.style.top)-5+"px"
setTimeout(function(){scrollerinstance.animateup()}, 50)
}
else{
this.getinline(this.hiddendiv, this.visiblediv)
this.swapdivs()
setTimeout(function(){scrollerinstance.setmessage()}, this.delay)
}
}

// -------------------------------------------------------------------
// swapdivs()- Swap between which is the visible and which is the hidden div
// -------------------------------------------------------------------

pausescroller.prototype.swapdivs=function(){
var tempcontainer=this.visiblediv
this.visiblediv=this.hiddendiv
this.hiddendiv=tempcontainer
}

pausescroller.prototype.getinline=function(div1, div2){
div1.style.top=this.visibledivtop+"px"
div2.style.top=Math.max(div1.parentNode.offsetHeight, div1.offsetHeight)+"px"
}

// -------------------------------------------------------------------
// setmessage()- Populate the hidden div with the next message before it's visible
// -------------------------------------------------------------------

pausescroller.prototype.setmessage=function(){
var scrollerinstance=this
if (this.mouseoverBol==1) //if mouse is currently over scoller, do nothing (pause it)
setTimeout(function(){scrollerinstance.setmessage()}, 100)
else{
var i=this.hiddendivpointer
var ceiling=this.content.length
this.hiddendivpointer=(i+1>ceiling-1)? 0 : i+1
this.hiddendiv.innerHTML=this.content[this.hiddendivpointer]
this.animateup()
}
}

pausescroller.getCSSpadding=function(tickerobj){ //get CSS padding value, if any
if (tickerobj.currentStyle)
return tickerobj.currentStyle["paddingTop"]
else if (window.getComputedStyle) //if DOM2
return window.getComputedStyle(tickerobj, "").getPropertyValue("padding-top")
else
return 0
}

</script>

<aside>
    <?
    echo $this->Html->image('lbavr.png',array(
                                        'url' => 'http://www.lbavr.com', 
                                        'target' => '_blank', 
                                        'id' => 'liensimage'
    ));
    echo $this->Html->image('bq.png',array(
                                        'url' => 'http://www.baseballquebec.com', 
                                        'target' => '_blank', 
                                        'id' => 'liensimage'
    )); ?>
  
    <h4>Merci à nos précieux partenaires</h4>
    
    <ul>
      <li><?=$this->Html->image('bertrandmathieu.png',array(
                                        'url' => 'http://www.bertrandmathieu.com', 
                                        'target' => '_blank',
                                        'id' => 'partenaire'
            )); ?>
      </li><br/>
      <li><?=$this->Html->image('cluboptimiste.png',array(
                                        'url' => 'http://www.cluboptimistedouville.com/', 
                                        'target' => '_blank',
                                        'id' => 'partenaire'
            )); ?>
      </li><br/>
      <li><?=$this->Html->image('lecourrier.png',array(
                                        'url' => 'http://www.lecourrier.qc.ca/accueil', 
                                        'target' => '_blank',
                                        'id' => 'partenaire'
            )); ?>
      </li><br/>
      <li><?=$this->Html->image('villesth.png',array(
                                        'url' => 'http://www.ville.st-hyacinthe.qc.ca/loisirs-et-culture/accueil-loisirs-et-culture.php', 
                                        'target' => '_blank',
                                        'id' => 'partenaire'
            )); ?>
      </li><br/>
    <br/><br/>
    
        <li><?=$this->Html->image('facebook.jpg',array(
                                            'url' => 'https://www.facebook.com/BaseballSaintHyacinthe', 
                                            'target' => '_blank',
                                            'id' => 'partenaire'
            )); ?>
        </li><br/>
    
    </ul>
    
    <iframe marginheight="0" marginwidth="0" name="wxButtonFrame" id="wxButtonFrame" height="266" 
      src="http://btn.meteomedia.ca/weatherbuttons/template6.php?placeCode=CAQC0603&category0=Cities&containerWidth=120&btnNo=&backgroundColor=blue&multipleCity=0&citySearch=1&celsiusF=C" 
      align="top" frameborder="0" width="120" scrolling="no" allowTransparency="true"></iframe><br/><br/>
</aside>

<div style="height:60px;">
    <div id="dernResultHead">
        <p>DERNIERS RÉSULTATS</p>
    </div>
    <div id="derniersresultats">
        <script type="text/javascript">
          new pausescroller(pausecontent, "pscroller1", "someclass", 9000);
          document.write("<br />");
        </script>
    </div>
</div> 

<?php foreach($articles as $comm) { ?>
  
    <article>
      <span id="creation">Ajouté le <?=$comm['VueCommunique']['DateCreation']; ?> 
          par <?=$comm['VueCommunique']['Auteur']; ?></span><br/>
    <? if($comm['VueCommunique']['LienImage'] != '') {
        echo $this->Html->image($comm['VueCommunique']['LienImage'],array( 
                                    'width' => $comm['VueCommunique']['DimImage'],
                                    'align' => 'left'
        )); ?>
      <span align="left"><?php //echo $article->creditImage() ?></span>
    <? } ?>
      <h5><?=$comm['VueCommunique']['Titre']; ?></h5>
      <p><?=str_replace("\r\n\r\n", '</p><p>', $comm['VueCommunique']['Texte']); ?></p>
    </article>
<? } ?>

