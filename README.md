# Smart callback

This package provides some functionality which can be helpful for dynamically injecting arguments into any callable objects at the moment of the call.

> **NOTE:** Callable objects which cannot be modified it is the most useful case.

## Installation
```bash
composer install creatortsv/smart-callback
```

## Usage

```php
use Creatortsv\SmartCallback\SmartCallback;
use Creatortsv\SmartCallback\NamedCallback;

$smart = new SmartCallback('str_replace');
$named = new NamedCallback($smart, 'I am the smart function');

$named(subject: 'I am the one');
```