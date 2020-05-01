# Yii Map application

### Installation
1. Setup your DB and modify `config/db.php` entering your username and password. Also updating the database name.

2. Setup an account with https://mapbox.com create and copy over your new token to `web/js/application.js` on line 17. 

3. Setup an account with https://positionstack.com create and copy your new token to `web/js/application.js` on line 18.

4. In the root folder of the application, run `composer install` to install all dependencies needed.

5. While still in the root folder, run `php yii migrate` to run all migrations and seed status and countries table.

6. If all goes well in setup, fire up the server with the command `php yii serve`. This should provide you with a link to view the application.