# Siesta
Stored procedure based ORM for php
Installation with composer

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

# Documentation
For full documentation, please visit [https://gperler.github.io](https://gperler.github.io)


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
