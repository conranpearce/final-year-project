// Return the time the device is scheduled for
function findTime(schedule) {
    schedule = schedule.split('smin":');
    scheduleMins = schedule[1];
    scheduleMins = scheduleMins.split(',');
    minutes = scheduleMins[0];

    var hours = (minutes / 60);
    var roundHours = Math.floor(hours);
    var minutes = (hours - roundHours) * 60;
    var roundMinutes = Math.round(minutes);
    
    if (roundHours < 10) {
        roundHours = '0' + roundHours;
    }
    if (roundMinutes < 10) {
        roundMinutes = '0' + roundMinutes;
    }
    return roundHours + ":" + roundMinutes + "";
}

// Return the day scheduled
function findDay(schedule) {
    if (schedule.includes('"wday":[1,0,0,0,0,0,0]')) { 
        return "Sunday";
    } else if (schedule.includes('"wday":[0,1,0,0,0,0,0]')) { 
        return "Monday";
    } else if (schedule.includes('"wday":[0,0,1,0,0,0,0]')) { 
        return "Tuesday";
    } else if (schedule.includes('"wday":[0,0,0,1,0,0,0]')) { 
        return "Wednesday";
    } else if (schedule.includes('"wday":[0,0,0,0,1,0,0]')) { 
        return "Thursday";
    } else if (schedule.includes('"wday":[0,0,0,0,0,1,0]')) { 
        return "Friday";
    } else if (schedule.includes('"wday":[0,0,0,0,0,0,1]')) { 
        return "Saturday";
    } 
}
