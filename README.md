# SSO

## Install service

```bash
cd docker && docker-compose up -d
```

## Install SSO

```bash
composer update
```

## Create user mongodb

connect to docker mongodb
```
sudo docker exec -it [hash code] bash
```

```
mongo -u root
```

```
use sso
```

```
db.createUser(
{
 user: "usersso",
 pwd:  "97v4MUt85G1",
 roles:
    [
        {
            role:"readWrite",
            db:"sso"
        },
    ]
});
```

## Create database tables
```
php artisan migrate
```

## Run local server 
```
php -S 127.0.0.1:8000 public/index.php
```
