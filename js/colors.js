function bgChange(){
	if(!document.getElementsByTagName) return false;
		var tables = document.getElementsByTagName("table");
		for(var i=0; i<tables.length; i++){
		var odd = false;
			trs = tables[i].getElementsByTagName("tr");
			for(var j=0; j<trs.length; j++){
				if(odd==true){
				trs[j].style.background = "#ffffff"; 
				odd = false;
				}else{
				trs[j].style.background = "#dddddd"; 
				odd = true;
			}
		}
	}
}

window.onload = bgChange;