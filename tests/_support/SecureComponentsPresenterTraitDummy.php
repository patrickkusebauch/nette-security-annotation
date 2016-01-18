<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 17.1.16
 * Time: 14:19
 */

namespace Kusebauch\NetteSecurityAnnotation\Tests;

use Kusebauch\NetteSecurityAnnotation\PresenterTrait;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\Container;

class SecureComponentsPresenterTraitDummy extends Presenter
{
    use PresenterTrait;

    public function actionDefault()
    {
        $component = $this['actionTest'];
    }

    public function actionUnknown()
    {
        $component = $this['actionTest'];
    }

    public function actionPrivilege()
    {
        $component = $this['privilegeTest'];
    }

    /**
     * @Action("default")
     *
     * @return Container
     */
    public function createComponentActionTest()
    {
        return new Container();
    }

    /**
     * @Secured
     *
     * @Resource("component")
     * @Privilege("create")
     *
     * @return Container
     */
    public function createComponentPrivilegeTest()
    {
        return new Container();
    }


    public function sendTemplate()
    {
        return;
    }

}