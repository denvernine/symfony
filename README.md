# DDD + Symfony

## install

```bash
$ curl -sSL https://getcomposer.org/installer | php
$ php composer.phar clear-cache && \
  php composer.phar update && \
  php composer.phar install && \
  php composer.phar dump-autoload --no-dev --classmap-authoritative
```

## test

```bash
$ php composer.phar test
```

## lint

```bash
$ php composer.phar lint
```
