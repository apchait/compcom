function myDate(){
	d = new Date();
	day = d.getDate();
	if(day < 10){
		day = "0" + String(day);
	}
	month = d.getMonth();
	if(month < 10){
		month = "0" + String(month);
	}
	return String(d.getFullYear()) + String(month) + String(day);
}