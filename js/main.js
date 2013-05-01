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
			element.style.display = "block";
			link.innerHTML = 'HIDE';
		}
}




