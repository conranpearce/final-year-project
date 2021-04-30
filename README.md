# Digital Systems Project

This application manages and schedules TP-Link smart devices, helping an individual lower their local carbon impact.

The [National Grid's API](http://carbonintensity.org.uk/) has been used to provide carbon intensity related data.

TP-Link smart plugs and bulbs are accessed through HTTP requests to be turned on/off and scheduled.

MAMP is the packaged used for local development, using PHP, MySQL, JavaScript, HTML and CSS to program the application.

Cloud service files can be found [here](https://github.com/conranpearce/digital-systems-project-cloud).

[GitHub Kanban board](https://github.com/conranpearce/digital-systems-project) to manage the project.

## Demo ##

* [Application demo](https://drive.google.com/file/d/1yjPFV2dF341Ojv1Ch1alqOWi5_xS5C1R/view?usp=sharing)
* [Smart plug demo](https://drive.google.com/file/d/13UhojMrFfwPlUEe-wSslqOv5mgUDwKR1/view?usp=sharing)
* [Smart bulb demo](https://drive.google.com/file/d/1XuNURAbGDmWZbUF1CMW5b1iwBpjdRt74/view?usp=sharing)

## Requirements ##

* [MAMP](https://www.mamp.info/en/windows/)
* [Moesif Origin & CORS Changer](https://chrome.google.com/webstore/detail/moesif-origin-cors-change/digfbfaphojjndkpccljibejjbppifbc) - used to block CORS erros using Google Chrome (turn on)

## Build ##

* Clone this repo.
* Move the project to the relevant folder for development. For Mac it's in MAMP/htdocs.
* Open up phpMyAdmin
* Execute the SQL query `CREATE DATABASE digitalsystemsproject;` to create the database:
* Move to the database `digitalsystemsproject` in phpMyAdmin
* Execute the SQL query below, in the `digitalsystemsproject` database, to create the tables:

`CREATE TABLE users (
    usersId int(11) AUTO_INCREMENT,
    usersName varchar(128)  NOT NULL,
    usersEmail varchar(128)  NOT NULL,
    usersUid varchar(128)  NOT NULL,
    usersPwd varchar(128)  NOT NULL,
    tpLinkUser varchar(128)  NOT NULL,
    tpLinkPwd varchar(128)  NOT NULL,
    PRIMARY KEY (usersId)
); `

`CREATE TABLE carbon_intensity_saved (
    carbonIntensityId int(11) AUTO_INCREMENT,
    usersId int(11) NOT NULL, 
    co2Used int(11) NOT NULL,
    co2Saved int(11) NOT NULL,
    PRIMARY KEY (carbonIntensityId),
    FOREIGN KEY (usersId) REFERENCES users(usersId)
);`
* Alter the database variables `$serverName $dBUsername $dBPassword $dBName` to your credentials in `dbh.inc.php`.
* Set up MAMP ports to `8888` in preferences.
* Access the application through `http://localhost:8888/digital-systems-project/`.
