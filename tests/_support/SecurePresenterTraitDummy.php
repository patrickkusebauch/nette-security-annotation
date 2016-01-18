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

class SecurePresenterTraitDummy extends Presenter
{
    use PresenterTrait;

    /**
     * @Secured
     *
     * @Resource("action")
     * @Privilege("show")
     */
    public function actionDefault()
    {

    }

    /**
     * @Secured
     *
     * @Resource("action")
     * @Privilege("show", "other")
     */
    public function actionMultiple()
    {

    }

    /**
     * @Secured
     *
     * @Resource("signal")
     * @Privilege("show")
     */
    public function handleSignal()
    {

    }

    /**
     * @Secured
     *
     * @Resource("signal")
     * @Privilege("show", "other")
     */
    public function handleMulti()
    {

    }

    /**
     * @Secured
     *
     * @Privilege("show")
     */
    public function actionInvalid()
    {

    }

    public function sendTemplate()
    {
        return;
    }

}