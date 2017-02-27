# Siesta stored procedure based ORM for PHP 7

[![License](https://poser.pugx.org/gm314/siesta/license)](https://packagist.org/packages/gm314/siesta)
[![Latest Stable Version](https://poser.pugx.org/gm314/siesta/v/stable)](https://packagist.org/packages/gm314/siesta)
[![Total Downloads](https://poser.pugx.org/gm314/siesta/downloads)](https://packagist.org/packages/gm314/siesta)
[![Latest Unstable Version](https://poser.pugx.org/gm314/siesta/v/unstable)](https://packagist.org/packages/gm314/siesta)

Stored procedure based ORM for PHP 7.

# Documentation
For full documentation, please visit [https://gperler.github.io](https://gperler.github.io)

# Installation with composer

```
composer require gm314/siesta
```

# Example
In this example we create an Artist and Label entity, and configure the relationship between them:

```php
$artist = new Artist();
$artist->setName("Jamie Woon");

$label = new Label();
$label->setName("PMR");
$label->setCity("London");
$label->addToArtistList($artist);

// save with cascade. will store both label and artist
$label->save(true);
```

# Console Commands
create config file

```
vendor/bin/siesta init
```

generate entities

```
vendor/bin/siesta gen
```

reverse engineer

```
vendor/bin/siesta reverse
```

# Testing

configure your database settings in tests/siesta.test.mysql.config.json

```
vendor/bin/codecept run
```
