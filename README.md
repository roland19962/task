1. nginx and php 8.2 
2. cp .env.example .env 
   change APP_URL to your url
3. change permission
   sudo chown -R $USER:www-data .
   sudo find . -type f -exec chmod 664 {} \;
   sudo find . -type d -exec chmod 775 {} \;
   sudo chgrp -R www-data storage bootstrap/cache
   sudo chmod -R ug+rwx storage bootstrap/cache
4. composer install
5. use information for starting from
   https://www.postman.com/appunisellerio/workspace/homework-task/request/34374403-af93aa42-bbac-4d87-a543-6d980e61d541?action=share&creator=34374403&ctx=documentation&active-environment=34374403-a359f891-5712-4126-97bf-c24407ce7f4e
6. running test
   php artisan test 
