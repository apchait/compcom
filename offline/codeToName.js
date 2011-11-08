function codeToName(code,fun){
	$.getJSON('list.json',function(data){
		id = data['codeToId'][code];
		name = data['idToAll'][id]["pr_name"];
		fun(name);
	});
}