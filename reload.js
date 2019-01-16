window.onload = function hej() {
				if(!window.location.hash) {
				window.location = window.location + '#reload';
				window.location.reload();
				}
				}
				
function help_window() {
	var div = document.getElementById("help_box");
	var blur = document.getElementById("container");
	 if (div.style.display =="block"){
		div.style.display = "none";
		blur.style.backgroundColor = "rgba(0,0,0,0)";
		blur.style.filter = "blur(0px)" ;
	}else{
		div.style.display = "block";
		blur.style.backgroundColor = "rgba(0,0,0, 0)";
		blur.style.filter = "blur(6px)";

	}
}
