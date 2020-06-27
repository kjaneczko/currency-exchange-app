#Currency Exchange App

### How to install

1. edit .env file and add mysql database
2. go to project folder and run following commands in console
```bash
$ php artisan migrate
$ php artisan db:seed
$ php artisan serve
``` 
Go to http://127.0.0.1:8000 and play! :D

If your URL is different, e.x. http://127.0.0.1:8001 then change URL in file /resources/js/Index.js in line 12 
