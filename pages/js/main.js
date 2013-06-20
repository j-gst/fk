/**
 * Zentrale Java-Script Datei
 * @author Gerrit Storm
 */

/**
 * Anzeigen und verbergen der Kommentare
 * @param areaId
 * @param linkId
 * @author Gerrit Storm
 */
function showHideComments(areaId , linkId) {

		var element = document.getElementById(areaId);
		var link = document.getElementById(linkId);
		if(element.style.display == "block"){
			element.style.display = "none";
			link.innerHTML = 'SHOW';
		
		}
		else{
		    hideAllComment();
			element.style.display = "block";
			link.innerHTML = 'HIDE';
		}
}

/**
 * Anzeigen und verbergen des Kommentar Eigabefeldes
 * @param areaId
 * @param linkId
 * @author Gerrit Storm
 */
function showHideCommentInput(areaId , linkId) {
		var element = document.getElementById(areaId);
		var link = document.getElementById(linkId);
		if(element.style.display == "block"){
			element.style.display = "none";
		
		}
		else{
		    hideAllComment();
			element.style.display = "block";
		}
}

/**
 * Alle Kommentar und Kommentareingabe Felder verbergen
 * @author Gerrit Storm
 */
function hideAllComment(){
	var elements_commentarea;
	var elements_commentinput;
	elements_commentarea = getElementsByClass("commentarea");
	elements_commentinput = getElementsByClass("commentinput");	
	for(var i=0;i<elements_commentarea.length;i++) {
		elements_commentarea[i].style.display = "none";
		if(document.getElementById("showcomments_"+i)){
			document.getElementById("showcomments_"+i).innerHTML = 'SHOW';
		}
	}
	for(var i=0;i<elements_commentinput.length;i++) {
		elements_commentinput[i].style.display = "none";
	}
}

/**
 * suche alle Elemente, die zu einer css Klasse gehoeren 
 * @param ClassName Name der CSS Klasse
 * @return: Array mit allen Elementen der Klasse ClassName
 * @author Gerrit Storm
 */
function getElementsByClass(ClassName) {    
  var currentElement;
  var allElements;
  var matchingElements;

  // alle Elemente einlesen
  allElements = document.getElementsByTagName("*");
  matchingElements = [];

  for(var i=0;i<allElements.length;i++) {
  
    currentElement = allElements[i].className.split(" ");
    for(var j=0;j<currentElement.length;j++) {
      if(currentElement[j]==ClassName) { 
        matchingElements.push(allElements[i]); 
        break; 
      }
    }//for
  }//for
  return matchingElements;
}

/**
 * Popup Datei als Hilfe anzeigen
 * @author Gerrit Storm
 */
//Das window.onload Ereignis wird beim Laden der Seite immer ausgeführt
//Diesem wird hier eine anonyme Funktion zugewiesen. Das wird in JS sehr oft und viel gemacht
//auch gehen würde: window.onload = startfunktion;
//und dann: startfunktion(){  //do something }
//letztlich wird also diese funktion bei jedem Laden der Seite Aufgerufen
window.onload = function () {

	 // hier wird für das Element mit der ID mypopup der Eventhandler onclick registriert
	 // das ist das gleiche als wenn im HTML onclick="openHelpWindow();" stünde
	 // die Klammern der Funktion openHelpWindow() werden hier weggelassen sonst geht das nicht richtig - klingt komisch ist aber so
  document.getElementById('mypopup').onclick = openHelpWindow; 

	 
}; // hier muss das ; hin, das die Zuweisung abschließt - gewöhnungsbedürftig 


//die Funktion, die bei dem oncklick aufgerufen wird
function openHelpWindow(){

//popup öffnen
var popup = window.open('hilfe.html', "Hilfe", "width=600,height=600,left=300,top=200");

// focus setzen das Fenster soll im Vordergrund sein
popup.focus();


// return false ist nötig für einige Browser (die öffnen den Popup sonst im Hauptfenster noch mal)
return false;

} // ENDE openHelpWindow()
