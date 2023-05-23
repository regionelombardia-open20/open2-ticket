# Amos Ticket #

Ticket management.

### Installation
You need to require this package and enable the module in your configuration.

add to composer requirements in composer.json
```
"open2/amos-ticket": "dev-master",
```

or run command bash:
```bash
composer require "open2/amos-ticket:dev-master"
```

Enable the News modules in modules-amos.php, add :
```php
 'ticket' => [
	'class' => 'open2\amos\ticket\AmosTicket',
 ],

```

add news migrations to console modules (console/config/migrations-amos.php):
```
'@vendor/open2/amos-ticket/src/migrations'
```

Add ticket to Comments:

```
  'comments' => [
    'class' => 'open20\amos\comments\AmosComments',
    'modelsEnabled' => [
        .
        .
        'open2\amos\ticket\models\Ticket', //line to add
        .
        .
 	],
    'enableMailsNotification' => false,
    'enableUserSendMailCheckbox' => false
  ],
```



