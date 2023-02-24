# Smart callback

This package provides some functionality which can be helpful for dynamically injecting arguments into any callable objects at the moment of the call.

> **NOTE:** Callable objects which cannot be modified it is the most useful case.

## Installation
```bash
composer install creatortsv/smart-callback
```

## Usage

```php
use Creatortsv\SmartCallback\Argument\ArgumentManager;use Creatortsv\SmartCallback\NamedSmartCallback;use Creatortsv\SmartCallback\SmartCallback;

$argumentManager = new ArgumentManager(
    new MySpecificArgumentResolver(),
    new MyDefaultArgumentResolver(),
);

$smart = new SmartCallback('str_replace', $argumentManager);
$smart(subject: 'I am the one');
```