##Pi Setup and Installation

Before we begin, we are going to assume that the Pi is running Raspbian or Raspbian Lite already, the SD partition has been expanded, and it is connected to the network (wired or wirelessly), and that you are SSH-ing into the system.  If you are using an operating or linux distro other than Raspbian, then hopefully you will be able to use the appropriate commands instead of the commands below.

We are going to start first by updating our Pi by running `sudo apt-get update`.  Depending on your connection speed and the amount of updates, it could take several minutes for the command to finish.

After it is finished updating, we can start the installation of the system.  We need to install Apache, PHP, MySQL, Git, Nano, and Composer.  

Install Apache: `sudo apt-get install apache2 -y`
Install PHP: `sudo apt-get install php5 libapache2-mod-php5 php5-curl php5-mysql -y`
Install MySQL: `sudo apt-get install mysql-server -y`
Install Git: `sudo apt-get install git -y`
Install Nano: `sudo apt-get install nano -y`

Install Composer

    php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer

At this point, test the system by visiting http://your.pi.ip.here/ and you should see an Apache welcome screen.  If you are seeing that screen, then great success!  At this point, our "backbone" is up and running without any modifications.

###Installing the application

We next need to clone the application repository in the correct directory.  So move into the /var/www directory (`cd /var/www`) and delete the contents of the directory (`rm -rf .`) With an empty directory, we can now clone the repo to the existing directory: `git clone https://github.com/TimWithers/PiSprinklers.git .`

After the repository has been cloned, you will need to run `composer install` to install all the packages the program needs to work properly.  This can take a few minutes as well to download everything.

The final step to configuring the application is to update application permissions.  `chmod 777 -R /var/www/var/*` This gives the program access to cache, logs, and session data and the ability to create new files in those directories as needed.  Cache will help speed up the application and ensure the program is running correctly.

###Updating Apache Configurations

You will need to edit your apache configuration file to use the directory appropriately.  First lets enable some Apache Modules so the program works as it should: `nano /etc/apache2/apache2.conf` and around line 142, paste the following: 

    IncludeOptional mods-available/rewrite.load
    IncludeOptional mods-available/deflate.load
    IncludeOptional mods-available/deflate.conf

Hit Ctrl+X and Y to exit and save the file.  These modules allow for gzip to work to speed up the site, as well as the ability to rewrite the route, which is needed to make the urls look nice and neat.

Then we need to configure the virtual host for the system: `nano /etc/apache2/sites-enabled/000-default.conf` and overwrite it with the following:
    <VirtualHost *:80>
        # The ServerName directive sets the request scheme, hostname and port that
        # the server uses to identify itself. This is used when creating
        # redirection URLs. In the context of virtual hosts, the ServerName
        # specifies what hostname must appear in the request's Host: header to
        # match this virtual host. For the default virtual host (this file) this
        # value is not decisive as it is used as a last resort host regardless.
        # However, you must set it for any further virtual host explicitly.
        #ServerName www.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/web
        <Directory /var/www/web>
                AllowOverride None
                Order Allow,Deny
                Allow from All

                <IfModule mod_rewrite.c>
                        Options -MultiViews
                        RewriteEngine On
                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteRule ^(.*)$ app.php [QSA,L]
                </IfModule>
        </Directory>

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf
    </VirtualHost>

Then hit `Ctrl+X` to exit, and say `Y` to save the file.  Then reload and restart apache: 

    /etc/init.d/apache2 reload 
    /etc/init.d/apache2 restart

###Creating the database

At this point, if you try to visit the page, you are going to get an error page because we haven't finished setting up everything we need to get the database up and running.  If you are still in the /var/www directory, run ` php bin/console doctrine:database:create` and it will create the "sprinkler" database.  Then run `php bin/console doctrine:schema:update --force` to add the tables to the database.

###Weather API Key

At this point, everything should be more or less working.  You will have an issue with the weather at this point as the system requires an API key for it to work.  You can visit https://developer.forecast.io/ and register as a developer.  After you register, you will get an API key.  Add this key to the services.yml file (`nano /var/www/src/SprinklerBundle/Resources/parameters.yml`) where it says "YOUR KEY HERE" and then update the address to your home address.  The program will use your address, find your longitude and latitude, and then find the weather for your location from forecast.io

###Clearing the cache one last time

Finally, you will most likely need to clear Symfony's cache, so from /var/www, run the command `php bin\console cache:clear --env=prod` and it will clear the production cache so that the system will use the latest changes.

###Creating the cron job

