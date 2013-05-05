function registercheck(){
	var form = document.forms[1];
	var inputs = form.elements;
	var errors = [];
	
	console.log(inputs);
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'text'|| inputs[i].type == "tel"||inputs[i].type == "email"||inputs[i].type == "password") {
			if (inputs[i].value == ""){
				errors.push(inputs[i].name);
			}	
		}
		if (inputs[i].type == "email"){
			console.log(inputs[i]);	
		}
		if (inputs[i].type == "password"){
			console.log(inputs[i]);	
		}
	
	}
	if (errors.length > 0) {
		console.log(errors);
		return false;
	}
	return true;
}





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


// suche alle Elemente, die zu einer css Klasse gehoeren 
// return: Array mit allen Elementen der Klasse ClassName
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
