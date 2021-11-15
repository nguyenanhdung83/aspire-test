# Aspire Test API


## Brief documentation

### Assumed logic

- authenticated users allows to apply a loan 
- All the loans will be assumed:
    - to have a `weekly` repayment frequency.
    - the loan have default status is `approved`
- user must be able to submit the weekly loan repayments:
  - which won’t need to check if the dates are correct
  - just set the weekly amount to be repaid.
  - user can only repaid owner loan
- after repaid all loan's amount, the loan repayment_status auto change to `yes`. 
  I used Event/Listener to update, this should be queued for better but this will make you more cost for setup environment

### Directory structure
Basically, I apply the recommended code base structure of Laravel framework and add the below directories:
- Event
- Exceptions
- Http
    - Request: All validate will execute in here
    - Resources: Reformat object/collection before return to client
- Listener
- Repositories:
  - Including interfaces which fetches or create Model objects
  - Should use Collection approach to fetch multiple Models
- Rules: Use when validate Requests
- Services
  - Including logic which uses Application
  - Not allowed to call the classes in Infrastructure directly
  - May use Interfaces which are defined inside Application

## Tech stack
- Laravel 8 (use `laravel/sanctum` because Sanctum is a simple package to issue API tokens to users)
- PHP >= 7.3
- Mysql >= 5.7

## Install
- Clone source code: 
```
git clone git@github.com:nguyenanhdung83/aspire-api.git alex-test
cd ./alex-test
cp .env.text .env
```

- update .env
```
- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_DATABASE=aspire_test
- DB_USERNAME=root
- DB_PASSWORD=root
```
- init project
```
composer install
php artisan migrate
php artisan db:seed

chmod 777 -R ./storage
chmod 777 -R ./bootstrap/cache
```
add aspire.test to file /etc/hosts
```
127.0.0.1 aspire.test
```
add file config aspire.test.conf for nginx
```
server {

    listen 80;
    listen [::]:80;

    server_name aspire.test;
    root /var/www/alex-test/public;
    index index.php index.html index.htm;

    location / {
         try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_pass php-upstream;
        fastcgi_index index.php;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        #fixes timeouts
        fastcgi_read_timeout 600;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location /.well-known/acme-challenge/ {
        root /var/www/letsencrypt/;
        log_not_found off;
    }

    error_log /var/log/nginx/clinic_error.log;
    access_log /var/log/nginx/clinic_access.log;
}
```
## Test
### Postman API Documentation
attack on source code folder 
```
./aspire-test.postman_collection.json
```
or postman server
```
https://www.getpostman.com/collections/f2b442970efd062c5256

```
### Default users account (after run seeder)
```
user1@gmail.com/123123
user2@gmail.com/123123
```
### How to use
- Use API login with one of above accounts then get token
- Add this token to request header 

### Unit test
```
php artisan test
```
