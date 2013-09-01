openGallery
===========

This project's target is to apply new technologies and with this even greater possibilities for the popular www.hung-art.hu and www.wga.hu sites.

The project has been re-created to use a homebrew CMS, called blackFyre CMS, which is mainly based on Twitter Bootstrap and Smarty.

 Our hope is to create a new site which can continuously expand its database with the help of everyone.

Some technical data:
The site is hosted at http://opengallery.blackworks.org
The VPS runs php 5.5, mysql, and will be sporting google's mod-pagespeed to further enhance the user experience.

Everyone who want to try it out, the complete source (with db structure and no data) is available for the public. Technical instructions on the install process will be posted based on asked questions. However, the bulk of the install process is setting up the db and the connection to it.
Which can be done with renaming the core/appConfig.sample.php to core/appConfig.php and setting some of the variables.
The root/cache and root/view/templates_c folders have to owned by the servers webuser or chmodded to 777 on linux, on windows...