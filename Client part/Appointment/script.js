window.onload = function(){
    var d = new Date();
    var month_name = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    var month = d.getMonth();   //0-11
    var year = d.getFullYear(); //2014
    var first_date = month_name[month] + " " + 1 + " " + year;
    //September 1 2014
    var tmp = new Date(first_date).toDateString();
    //Mon Sep 01 2014 ...
    var first_day = tmp.substring(0, 3);    //Mon
    var day_name = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    var day_no = day_name.indexOf(first_day);   //1
    var days = new Date(year, month+1, 0).getDate();    //30
    //Tue Sep 30 2014 ...
    var calendar = get_calendar(day_no, days);
    document.getElementById("calendar-month-year").innerHTML = month_name[month]+" "+year;
    document.getElementById("calendar-dates").appendChild(calendar);
}

function get_calendar(day_no, days){
    var table = document.createElement('table');
    var tr = document.createElement('tr');
    var daysAvailable = document.getElementsByTagName("option");
    var daysAvailableValue = [];
    for(var index = 0 ; index < daysAvailable.length ; index++) {
        daysAvailableValue.push(daysAvailable[index].value);
        console.log('Ziua cu nr: '+ daysAvailable[index].value);
    }
    //row for the day letters
    for(var c=0; c<=6; c++){
        var td = document.createElement('td');
        td.innerHTML = "DLMMJVS"[c];
        tr.appendChild(td);
    }
    table.appendChild(tr);
    
    //create 2nd row
    tr = document.createElement('tr');
    var c;
    for(c=0; c<=6; c++){
        if(c == day_no){
            break;
        }
        var td = document.createElement('td');
        td.innerHTML = "";
        tr.appendChild(td);
    }
    
    var count = 1;
    for(; c<=6; c++){
        var td = document.createElement('td');
        
        td.innerHTML = count;
        if(c == 0 || c==6){
            td.setAttribute('class', "busy");
        }
        count++;
        tr.appendChild(td);
        
    }
    table.appendChild(tr);
    
    //rest of the date rows
    for(var r=3; r<=7; r++){
        tr = document.createElement('tr');
        for(var c=0; c<=6; c++){
            if(count > days){
                table.appendChild(tr);
                return table;
            }
            var td = document.createElement('td');
            td.innerHTML = count;
            if(c == 0 || c==6){
                td.setAttribute('class', "busy");
            }

            for(var index = 0 ; index < daysAvailable.length ; index++) {
                var d = new Date();
                console.log('Luna valabila este : ' + parseInt(daysAvailableValue[index].substring(0, 2))+' luna este: '+d.getMonth() +1 );
                console.log('Ziua valabila este : ' + parseInt(daysAvailableValue[index].substring(3, 5))+'iar ziua curenta este: '+count);
                if(parseInt(daysAvailableValue[index].substring(3, 5)) === count && parseInt(daysAvailableValue[index].substring(0, 2)) === d.getMonth() +1 ){
                    td.setAttribute('class', "available");            
                }
            }
            count++;
            tr.appendChild(td);
        }
        table.appendChild(tr);
    }
    return table;
}