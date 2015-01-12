function pad(s, l, p) {
    s = "" + s;
    while(s.length < l)
        s = p + s;
    return s;
}

function addevent(){ /* todo:maybe preferable add a layer to current pages content than opening a window */
	window.open("addevent.html", "Event hinzufügen", "status=0, width=300, height=570");
}

function isoDate(day, month, year, hour, minute){
	if(day > 31 || month > 12 || hour > 23 || minute > 59){
		return false;
	}
	month = leadingZero(month);
	day = leadingZero(day);
	hour = leadingZero(hour);
	minute = leadingZero(minute);
	var iso = year+"-"+month+"-"+day+"T"+hour+":"+minute+":00";
	return iso;
}

function checkDate(){
	var startdate = document.getElementById("startdate").value.split(".");
	var enddate = document.getElementById("enddate").value.split(".");
	var starttime = document.getElementById("starttime").value.split(":");
	var endtime = document.getElementById("endtime").value.split(":");
	var current = new Date();
	start = Date.parse(isoDate(startdate[0], startdate[1], startdate[2], starttime[0], starttime[1]));
	end = Date.parse(isoDate(enddate[0], enddate[1], enddate[2], endtime[0], endtime[1]));
	if(start < current || end < start || isNaN(start) || isNaN(end)){
		return false;
	}else{
		return true;
	}
}

function checkFilled(){
	var userinput = [];
	userinput[0] = document.getElementById("title").value;
	userinput[1] = document.getElementById("location").value;
	userinput[2] = document.getElementById("link").value;
	userinput[3] = document.getElementById("mail").value;
	for(var i = 0; i < 4; i++){
		if(userinput[i].length == 0){
			return false;
		}
	}
	return true;
}

function validate(){
	var form = document.getElementById("eventform");
	var check = checkFilled();
	if(checkDate() && checkFilled()){
		form.removeAttribute("onSubmit");
		form.setAttribute("action", "http://jkliemann.de/offenesdresden.de/addevent.php");
	}else{
		alert("Falsche Eingabe!");
	}
}

function leadingZero(date){
	date = parseInt(date);
	if(date < 10){
		return "0" + date;
	}else{
		return date;
	}
}

function setDate(){
	var day = [];
	var month = [];
	var year = [];
	var hour = [];
	var date = new Date();
	day[0] = date.getDate();
	day[1] = day[0];
	month[0] = date.getMonth() + 1;
	month[1] = month[0];
	year[0] = date.getFullYear();
	year[1] = year[0];
	hour[0] = date.getHours() + 1;
	hour[1] = hour[0] + 1;
	var minute = "00";
	if(hour[1] > 23){
		var i = 1;
		if(hour[0] > 23){
			i = 0;
		}
		for(i; i < 2; i++){
			hour[i] = hour[i] % 24;
			day[i] = day[i] + 1;
		}
	}
	if(day[1] > 31){
		var i = 1;
		if(day[0] > 31){
			i = 0;
		}
		for(i; i < 2; i++){
			day[i] = day[i] % 31;
			month[i] = month[i] + 1;
		}
	}
	if(month[1] > 12){
		var i = 1;
		if(month[0] > 12){
			i = 0;
		}
		for(i; i < 2; i++){
			month[i] = month[i] % 12;
			year[i] = year[i] + 1;
		}
	}
	for(var i = 0; i < 2; i++){
		day[i] = leadingZero(day[i]);
		month[i] = leadingZero(month[i]);
		hour[i] = leadingZero(hour[i]);
		minute[i] = leadingZero(minute[i]);
	}
	document.getElementById("startdate").value=day[0]+"."+month[0]+"."+year[0];
	document.getElementById("starttime").value=hour[0]+":"+minute;
	document.getElementById("enddate").value=day[1]+"."+month[1]+"."+year[1];
	document.getElementById("endtime").value=hour[1]+":"+minute;
}

function setLocation(){
	document.getElementById("location").value="GHCQ (http://www.c3d2.de/space.html)";
}

function setDefault(){
	setLocation();
	setDate();
}

$.ajax({ url: "events.json" }).done(function(events) {
    var now = Date.now();
    var article = $('<article class="events"><h2>Nächste Treffen</h2></article>');
    var ul = $("<ul></ul>");
    events.forEach(function(event) {
        event.time = new Date(event.date).getTime();
    });
    events = events.filter(function(event) {
        return event.time >= now;
    }).sort(function(e1, e2) {
        return e1.time - e2.time;
    });
    events.forEach(function(event) {
        var li = $('<li><p class="title"></p><p><span class="date"></span> <a>Ort</a></p></li>');
        li.find('.title').text(event.title);
        var d = new Date(event.date);
        li.find('.date').text(
            d.getDate() + "." +
                (d.getMonth() + 1) + "." +
                d.getFullYear() + " " +
                pad(d.getHours(), 2, "0") + ":" +
                pad(d.getMinutes(), 2, "0")
        );
        li.find('a').attr('href', event.location);
        ul.append(li);
    });
    ul.append($('<li><a href onClick="addevent()">Termin einreichen</a>.</li>'));
    article.append(ul);
    article.append($('<p>Die Termine sind auch <a href="events.json">maschinenlesbar erhältlich</a>.</p>'));
    $('#main').prepend(article);
}).fail(function() {
    console.error("ajax", arguments);
});
