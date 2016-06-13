# Random-name-generator
project codename generator

Web application that generates random codenames as "adjective+name" format for your projects.

Used technologies and libraries:

* PHP for the backend service
* Pure css as the CSS grid system and control style
* The rest is just plain Javascript and CSS

##Notes for contribution
* To run, you need PHP and Apache, no MySQL server is required.
* The backend consists of a main class, which handles the application logic (NameManager.php), and an api-like script (CallManager.php).
* All AJAX calls should go through CallManager.php and filter out from there.
* Frontend logic is in the index.php file
 

MIT license
