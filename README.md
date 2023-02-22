# array-to-object

Converting class instances to associative arrays is easy:

```php
json_decode(json_encode($obj), true);
```

But reverting is difficult.  
Use this library in such a case.

## Quickstart

Login to the PHP container.

```shell
docker compose run --rm -u $(id -u):$(id -g) -it php ash
```

Composer install.

```shell
composer install
```

Execute an example.

```shell
php example/index.php
```
