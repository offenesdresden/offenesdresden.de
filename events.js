function pad(s, l, p) {
    s = "" + s;
    while(s.length < l)
        s = p + s;
    return s;
}

function addevent(){
	window.open("addevent.html", "Event hinzufügen", "status=0, width=300, height=420");
}

function checkDate(){
	var startdate = document.getElementById("startdate").value.split(".");
	var enddate = document.getElementById("enddate").value.split(".");
	var starttime = document.getElementById("starttime").value.split(":");
	var endtime = document.getElementById("endtime").value.split(":");
	if(startdate[0] > 31 || enddate[0] > 31 || startdate[1] > 12 || enddate[1] > 12 || starttime[0] > 24 || endtime[0] > 24 || starttime[1] > 60 || endtime[1] > 60){
		return false;
	}else{
		return true;
	}
}

function validate(){
	var form = document.getElementById("eventform");
	if(checkDate()){
		form.removeAttribute("onSubmit");
		form.setAttribute("action", "addevent.php");
	}else{
		//alert("Falsche Eingabe!");
	}
}


function setDate(){
	var date = new Date();
	var day = date.getDate();
	var month = date.getMonth() + 1;
	var year = date.getFullYear();
	var hour = date.getHours();
	var endhour = date.getHours() + 1;
	var minute = date.getMinutes();
	document.getElementById("startdate").value=day+"."+month+"."+year;
	document.getElementById("starttime").value=hour+":"+minute;
	document.getElementById("enddate").value=day+"."+month+"."+year;
	document.getElementById("endtime").value=endhour+":"+minute;
}

$.ajax({ url: "events.json" }).done(function(events) {
    var now = Date.now();
    var article = $("<article class='events'><h2>Nächste Treffen</h2></article>");
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
        var li = $("<li><p class='title'></p><p><span class='date'></span> <a>Ort</a></p></li>");
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
    article.append(ul);
    article.append($("<p>Die Termine sind auch <a href='events.json'>maschinenlesbar erhältlich</a>.</p>"));
    article.append($('<a href onClick="addevent()">Termin einreichen</a>.'));
    $('#main').prepend(article);
}).fail(function() {
    console.error("ajax", arguments);
});
