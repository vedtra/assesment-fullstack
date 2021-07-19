[![forthebadge](https://forthebadge.com/images/badges/60-percent-of-the-time-works-every-time.svg)](https://forthebadge.com)
## Build steps
> composer install
> 
> php artisan migrate
> 
> npm install
> 
> php artisan serve
> 
> npm run watch

configuration pusher

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=1236372
PUSHER_APP_KEY=23a7c58edefa163ada55
PUSHER_APP_SECRET=2f726044a758a248fe05
PUSHER_APP_CLUSTER=ap1
```
## Endpoints

    POST '/api/user/login'
    POST '/api/user/register'
    GET '/api/info/user'
    GET '/api/user/logout'
    GET '/api/messages/{id}'
    POST '/api/messages/send'
    GET '/api/read/{id}'
    GET '/api/contacts'
