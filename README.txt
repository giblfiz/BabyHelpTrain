WHHAAAA!!!
I don't want to use a framework!!
I don't want to do unit testing!!!

I'm totally going to put this off as long as I can. The joys of personal projects. 


Calendar API docs:
https://developers.google.com/calendar/v3/reference/calendarList/get

Twillio demo examples:
https://www.twilio.com/docs/sms/tutorials/how-to-create-sms-conversations-php


=====Running the scrappy Crappy Version of things:=====
Run composer to populate the vendor folder
I'm going to be using sqlite3 as the database for a while instead of mysql


php -S localhost:8000
to get a little local webserver up

ngrok localhost 8000
In another terminal to make a bridge that can be reached outside

Log into twilio console, and set the sms url to
the ngrok URL/BabyHelpTrainDemoCatcher.php