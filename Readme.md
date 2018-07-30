# Voucher API [Documentation](https://documenter.getpostman.com/view/1419985/RWMLL6Gs)

### Using Docker to install.
 - Clone `git@github.com:razaqK/voucher.git`
 - Run `cd voucher`
 - Run `docker-compose up`. This command build the images and start the containers
 - On you browser go to [localhost:port](http://127.0.0.1:100)

### Run Test Manually
 - Run `docker exec -it voucher bash`
 - Run command `php vendor/bin/codecept run api` 
 
### API Documentation
Check the api documentation [here](https://documenter.getpostman.com/view/1419985/RWMLL6Gs)

### Development on your local machine

#### Set your Application Environment

```
SetEnv APPLICATION_ENV "local"
```

#### Set DB credentials

```
SetEnv DB_HOST "127.0.0.1"
SetEnv DB_USERNAME "root"
SetEnv DB_PASSWORD "root"
SetEnv DB_DBNAME "voucher"
```

### Staging Server

#### Set your Application Environment

```
SetEnv APPLICATION_ENV "staging"
```

### Production

#### Set your Application Environment

```
SetEnv APPLICATION_ENV "production"
```
