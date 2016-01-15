<?php

namespace Kusebauch\NetteSecurityAnnotation;

use Nette\Application\ForbiddenRequestException;
use Nette\InvalidStateException;
use Nette\Reflection\ClassType;
use Nette\Reflection\Method;

trait PresenterTrait
{
    public function checkRequirements($element)
    {
        if ($element instanceof ClassType) {return;}; //not checking class access, only method access
        $user = $this->user;
        // Allowing for both method level and class level annotations
        $class = ($element instanceof Method) ? $element->getDeclaringClass() : $element;
        $secured = $element->getAnnotation('Secured') || $class->getAnnotation('Secured');

        if ($secured) {
            if (!$user->isLoggedIn()) {
                throw new ForbiddenRequestException("User has to be logged in to access secured presenter.");
            } else {
                //check existence of resource
                if (!($element->hasAnnotation('Resource') || $class->hasAnnotation('Resource'))) {
                    throw new InvalidStateException('Secured method is missing a resource.');
                }
                $resource = $element->hasAnnotation('Resource') ? $element->getAnnotation('Resource') : $class->getAnnotation('Resource');
                $privileges = array_merge((array) $class->getAnnotation('Privilege'), (array) $element->getAnnotation('Privilege'));
                $allowed = FALSE;
                foreach ($privileges as $privilege) {
                    if ($user->isAllowed($resource, $privilege)) $allowed = TRUE;
                }
                if (!$allowed) {
                    throw new ForbiddenRequestException("User is not allowed to access resource '$resource'");
                }
            }

        }
    }

    public function createComponent($name)
    {
        $ucname = ucfirst($name);
        $method = 'createComponent' . $ucname;

        $presenterReflection = $this->getReflection();
        if ($presenterReflection->hasMethod($method)) {
            $reflection = $presenterReflection->getMethod($method);
            $this->checkRequirements($reflection);

            $annotations = (array) $reflection->getAnnotation('Action');
            if (!empty($annotations) && !in_array($this->getAction(), $annotations)) {
                throw new ForbiddenRequestException("Creation of component '$name' is forbidden for action '$this->action'.");
            }
        }
    }
}
