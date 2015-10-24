# Siesta
Stored procedure based ORM for php
Installation with composer`

```
composer require gm314/siesta
```

# Documentation:
[https://gperler.github.io](https://gperler.github.io)


# Console Commands
create config file

```
vendor/bin/siesta init
```

generate entities

```
vendor/bin/siesta generate
```

reverse engineer

```
vendor/bin/siesta reverse
```


# Testing

configure your database settings in tests/siesta.test.config.json

```
vendor/bin/codecept run
```