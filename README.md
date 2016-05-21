[![Build Status](https://travis-ci.org/patrickkusebauch/nette-security-annotation.svg?branch=master)](https://travis-ci.org/patrickkusebauch/nette-security-annotation) [![codecov.io](https://codecov.io/github/patrickkusebauch/nette-security-annotation/coverage.svg?branch=master)](https://codecov.io/github/patrickkusebauch/nette-security-annotation?branch=master)[![Latest Stable Version](https://poser.pugx.org/kusebauch/nette-security-annotation/v/stable)](https://packagist.org/packages/kusebauch/nette-security-annotation)[![License](https://poser.pugx.org/kusebauch/nette-security-annotation/license)](https://packagist.org/packages/kusebauch/nette-security-annotation)

# nette-security-annotation
Security by annotations in Nette.

Allows securing presenters by using annotations on presenter "action" and "handle" methods.
Also can tie presenter components to specific actions and secure the creation of component by the same manner.

## Installation
The easiest way to install is via [composer](https://getcomposer.org/). 
Just run: `composer require kusebauch/nette-security-annotation`

Optionally you can just download the source and include the files as needed.

## Basic Usage
To enable the security just add the `@Secured` annotation to a method. (`action*`, `handle*`, `render*` or `createComponent*`)
Optionally you can add the annotation to the class and it will cascade to all methods in the class.

### Resource
Every method can have exactly one resource associated with it as of right now.
The resource is defined by the `@Resource` annotation with string value.
Resource defined at the class level is **OVERRIDDEN** by resource defined at method level.

### Privilege
Every method can have unlimited amount of privileges associated with it.
They are defined by the `@Privilege` annotation. The values are either string or array of strings.
Privilege defined at class level are **MERGED** with the privileges defined at method level.
Privileges are not strict - You need to have at least one privilege to access the method, not all of them.

### Action
Specific annotation for `createComponent*` methods. Can tie this this component factory to a specific presenter action.
It is defined by the `@Action` annotation and the rules are the same as are for the Privilege annotation.

### Violation behavior
If a violation of privileges happens in an `action*`, `handle*` or `render*` methods, `ForbiddenRequestException` is thrown.
THe same exception is thrown if the user is not logged in. If a method has `@Secured` annotation, but does not have a `@Resource` annotation
associated with it, `InvalidStateException` is thrown. Also if a you try to access a component in incorrect method, `ForbiddenRequestException` is thrown.

## Example
For examples of usage, see "tests/_support".

## Advanced usage
There are some considerations for advanced usage based on this library.

### Overriding presenter methods
This library overrides the default behavior for `createComponent` and `checkRequirements` presenter methods.
For this reason, special care has to be taken if overriding these methods in your own code, especially, if you use "trait" version of this library.

For `createComponent` method, the library calls the `checkRequirements` method and checks the `@Action` annotation.

For `checkRequirements` method, the library parses the annotations and calls the "$presenter->getUser()->isAllowed($resource, $privilege)" 
for every `@Resource` and `@Privilege` pair, tills it find the first pair, for witch it is allowed (if any).

### Overriding default behavior
You might want a different behavior if a violation of permissions happens. In this case you can just "Override the presenter methods" (more above).
In this case, you might want a code like this:
 
```php
<?php
 public function checkRequirements($element) {
     try {
         parent::checkRequirements($element)
     } catch(ForbiddenRequestException $e) {
         //You own exception processing
     }
 }
 ```