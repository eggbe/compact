## Introduction
This is the lightweight and high-performance library provides the simple way 
to transfer any objects or arrays between two applications.        


## Requirements
* PHP >= 7.0.0
* [Eggbe/Helpers](https://github.com/eggbe/helpers)


## Install
Here's the simpler way to start using Eggbe/Compact via [composer](http://getcomposer.org):

```bash
composer require eggbe/compact
```

## Usage
Now we can use the library features anywhere in the code:

```php
$SerializedData = Compactor::compact($SomeDataArray, $FlagsCombination);
```

The flags combination define the library behavior in contentious cases. 
Currently only two flags are supported: ```Compactor::CO_STRICT``` and ```Compactor::CO_ALLOW_ARRAYABLE```.   

In the strict mode the library requires that all objects implements the ```\Eggbe\Prototype\IPresentable``` interface. 
This interface provides an universal way to present an object as an array and defined in the [Eggbe/Prototype](https://github.com/eggbe/prototype) package.

Otherwise if ```CO_ALLOW_ARRAYABLE``` flag is provided  and an object don't implement the ```\Eggbe\Prototype\IPresentable``` interface 
the library tries to convert an object into an array via toArray method. If the object implementation don't include toArray method 
an exception will be thrown.


## Authors
Made with love at [Eggbe](http://eggbe.com).

## Feedback 
We always welcome your feedback at [github@eggbe.com](mailto:github@eggbe.com).


## License
This package is released under the [MIT license](https://github.com/eggbe/client-bridge/blob/master/LICENSE).
