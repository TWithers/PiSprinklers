Pi Sprinklers
==============

This is a small personal project to run a sprinkler system on a Raspberry Pi (or any server).
It uses the PHP Symfony framework for the console and web GUI, MySQL for data storage, and a cron job to check the database every minute.

##Getting Started

If you want to use the program for your setup and are familiar with the Raspberry Pi shell/terminal and the relay setup, this should take about 20-30 minutes to set up.
In a nutshell, your basic steps will be to install Raspbian Lite or another OS, connect the Pi GPIO to the relay, and then connect the relay to the sprinkler valves and power.
After that is complete, you will need to install Apache, PHP, MySQL, Composer, and Git.  Clone the repository, install the application with composer, update the Apache virtual host file, and add a crontab entry.
This is very very simplified, but that is all there is needed to get a setup going.

##How Sprinklers Work

Sprinklers pretty easy to figure out.  They are literally only ever in one of two states: on or off.  To turn a sprinkler on, you need to send power to the solenoid controlling it.
In most modern systems, it is usually 24V of AC power.  As soon as the power hits it, the solenoid opens, water goes through the pipes and into the sprinklers.
When the power is cut, the solenoid closes and water stops flowing.  So the solenoid has 2 wires in most cases, a common and a hot.
Usually the common wires are daisy chained across multiple solenoids, so in your sprinkler box, you will see 1 wire for each solenoid, and 1 common wire.

##Initial Pi Config
I used Raspbian Lite.  I didn't need the GUI for this, but I am pretty familiar with what I was doing.  The first thing I did after a fresh install was to expand the partition, and then update the operating system.
Afterwards, I configured the WiFi adapter that I was going to be using, and then set up a static IP for the system.
Finally, I did a few little tweaks to change the timezone and made sure the gpio commands worked.

##The Build Out

I used the case of my old sprinkler system to build this new system.  I removed the sprinkler controller from the wall, unscrewed everything, and took our the electronics component.
My sprinkler system had a 120V -> 24V transformer that is used to power the solenoids and cause the sprinklers to turn on/off.  I reused this in my project, but you can buy these if yours is not usable.

In my case, my sprinkler controller was connected directly to the breaker box, so it wasn't plugged in to a GFCI outlet, which meant I had no way to power my Pi.
I bought an enclosed GFCI outlet and wired one up in a few minutes and hung it on my wall outside.  Now I had power for the Pi and the transformer.

I bought a 4 channel relay off of eBay for just a few dollars, along with a few gpio connector cables.  I could have done an 8 channel relay, but my yard is tiny and I don't every see installing more than 3 zones/solenoids.

I mounted the Pi and the relay inside the now-empty sprinkler box using hot glue and ran the wires to the system.
I found the GPIO layout for my Pi easy enough on the internet, and ran the 5V cable to the ACC on the relay, pins 17, 27, 22, 5 to the relay in ports 1, 2, 3, and 4 respectively, and then the GND (ground) to GND.

At this point I powered up the Pi and tested the GPIO.  They lit up a soft/dim red when the power came on.
I used the command `gpio readall` and saw that the mode for those pins was set to "IN".
By using the command `gpio -g mode 17 out`, I heard a click and the light changed to a bright red.
By using the command `gpio -g write 17 0`, I heard another click, and knew things were working like they should.
I did notice on my Pi, that the system starts in "IN" mode with a value of "1".  So for the system to turn on, I wanted to use "OUT" mode with a value of "0".
I chose this because I didn't want the sprinklers to accidentally come on for a split second when the modes switched or when the system rebooted or powered off.

To finish the wiring, I connected the 1st wire from the 24V transformer to the middle of first relay, and plugged the transformer in to power it up.
Then I had to figure out which side the sprinkler cable was going to go on.  I pulled out a volt meter, turned it to AC, and then touched the black to the transformer wire I hadn't connected yet.
I then put the red tip in the right port on the first relay and looked at the reading.  I saw 0 volts.  I tried the left port and saw 24.  Since the GPIO for pin 17 was set to 0, this was the port I needed to use.

I turned off the transformer and built 3 small jumpers and connected the 24V port in the 1st relay to the middle port of all the other relays.
Then I connected the solenoid wire to the left port of the 1st relay and the second solenoid wire to the left port of the 2nd relay.
The common wire was connected to the second transformer wire.

The next thing I did was to plug the transformer back in, and the sprinklers came on instantly.  I logged back into the Pi and ran `gpio -g write 17 1` and the sprinklers turned off.

That's all it takes to get it set up!

##Pi Application Installation
Please follow the directions on the INSTALL.md file.  These are the steps I took to install everything and get it up and running.  It may be a little different on your system, but should be fairly straight forward.
In the future, I am thinking about making a repository or installation script to install and configure everything in one go.

#The Application
Once the application is running and you can visit it on your browser, you just need to create a zone and a timer for the zone.

###Zones
Zones are the individual solenoids.  Often times one solenoid controls the drip system in the front, another controls sprinklers in the back, and those are called "zones" in Pi Sprinklers.

Zones just need a name, a relay, and type.  Give the zone a descriptive name so you can remember what it is (ex: "Front Sprinklers").
The relay is the pin number the relay is connected to, and the type is to display an image in the system.

###Timers
Timers are little schedules to run the sprinkler solenoids.  You just need to specify the start time and duration, and then select the days.  You can have multiple timers per day, as many as you would like.

##Issues
Right now there is no block in place to limit timers start and end times.  Because of the voltage and water requirements, only one sprinkler solenoid should be open at a time.  When one closes, the next can open.
Timers should not overlap.  You can have one timer end and the other timer begin at the exact same time as the system shuts solenoids before opening the next one.

##Coming Soon

Settings for zones and weather.  You will be able to customize zones and add time or shut off zones if the weather is too hot or rainy.

Manual on/off.  You will be able to turn on a zone for specific amount of time manually.