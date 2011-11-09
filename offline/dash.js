$(document).ready(function(){
	console.log("Dash!");
	
	// Check if we are online or not
	var online = 1;
	$("#onlineOrNot").hide();
	function setUpOnlineOrNot(){
		// Add a listener to tell us whether we are online or not
		window.applicationCache.addEventListener("error", function(e) {
			console.log("offline!");
			$("#networkStatus").html("OFFLINE");
			online = 0;
			$("#onlineOrNot").show();
		});
	}
	setUpOnlineOrNot();
	
	
});