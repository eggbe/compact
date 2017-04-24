## Introduction
This is the lightweight and high-performance library provides the simple way 
to transfer any objects or arrays between two applications.


## Requirements
* PHP >= 7.0.0
* [Eggbe/Helpers](https://github.com/eggbe/helpers)
* [Eggbe/Prototype](https://github.com/eggbe/prototype)


## Install
Here's the simpler way to start using Eggbe/Compact via [composer](http://getcomposer.org):

```bash
composer require eggbe/compact
```

## Usage
Now we can use the library features anywhere in the code:

```php
$SerializedDataArray = Compactor::compact($OriginalDataArray, $FlagsCombination);
```

The binary flags combination define the library behavior in contentious cases. 
Currently only two flags are supported: ```Compactor::CO_STRICT``` and ```Compactor::CO_ALLOW_ARRAYABLE```. 
The ```Compactor::CO_STRICT``` flag is always set by default.    

In the strict mode the library requires that all objects implement the ```\Eggbe\Prototype\IPresentable``` interface 
defined in the [Eggbe/Prototype](https://github.com/eggbe/prototype) package. This interface provides an universal way to present an object as an array 
by the simple implementation of ``IPresentable::present()`` method. 

In other case when the ```Compactor::CO_ALLOW_ARRAYABLE``` flag is provided and an object don't implement the ```\Eggbe\Prototype\IPresentable``` interface 
then the library tries to convert this object into an array via ```toArray()``` method. If this method don't exists an exception will be thrown.

The following code is return data back from the serialized representation:

```php
$OriginalDataArray = Compactor::decompact($SerializedDataArray, $AliasMaker);
```

The library requires that all restorable objects implement the ```\Eggbe\Prototype\IRestorable``` interface 
defined in the [Eggbe/Prototype](https://github.com/eggbe/prototype) package. This interface provides an universal way to create 
an object and fill its with data by the simple implementation of ```IRestorable``` constructor. 

Sometimes it's important to make class overloading during the deserialization process. The second parameter of the ```Compactor::decompact()``` provids the simplest way to do it. 
Please, see the [AliasMaker](https://github.com/eggbe/utilities) component documentation for more information.     

## Authors
Made with love at [Eggbe](http://eggbe.com).

## Feedback 
We always welcome your feedback at [github@eggbe.com](mailto:github@eggbe.com).


## License
This package is released under the [MIT license](https://github.com/eggbe/compact/blob/master/LICENSE).
