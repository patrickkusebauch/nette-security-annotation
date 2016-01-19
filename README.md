[![Build Status](https://travis-ci.org/patrickkusebauch/nette-security-annotation.svg?branch=master)](https://travis-ci.org/patrickkusebauch/nette-security-annotation) [![codecov.io](https://codecov.io/github/patrickkusebauch/nette-security-annotation/coverage.svg?branch=master)](https://codecov.io/github/patrickkusebauch/nette-security-annotation?branch=master)

# nette-security-annotation
Security by annotations in Nette.

Allows securing presenters by using annotations on presenter "action" and "handle" methods.
Also can tie components to specific actions and secure the creation of component by the same manner.

##Usage
To enable the security just add the "@Secured" annotation to a method. 
Optionally you can add it to the class and it will cascade to all method in the class.

###Resource
Every method can have exactly one resource associated with it as of right now. 
The resourse is defined by the "@Resource" annotation with string value.
Resource defined at the class level is overridden by resource defined at method level.

###Privilege
Every method can have unlimited amount of privileges associated with it.
They are defined by the "@Privilege" annotation. The values are either string or array of strings.
Privilege defined at class cevel are added to the privileges defined at method level.
Privileges are not strict: You need to have at least one privilege to access the method, not all of them.

###Action
Specific annotation for "createComponent*" methods. Can tie this this component factory to a specific presenter action.
It is defined by the "@Action" annotation and the rules are the same as are for the Privilege annotation.

##Example
Coming soon.
