<?php


class SecureComponentsPresenterTraitTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    /**
     * @var \IntegrationTester
     */
    protected $tester;

    protected function _before()
    {
        $this->specifyConfig()->shallowClone();
    }

    protected function _after()
    {
        Mockery::close();
    }

    public function testComponentsTiedToAction()
    {
        $this->specify("Component can be created for allowed action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecureComponentsPresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user,
                new \Nette\Bridges\ApplicationLatte\TemplateFactory(
                    new \Kusebauch\NetteSecurityAnnotation\Tests\LatteFactory()
                ));
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "default", []));
        });

        $this->specify("Component cannot be created for not allowed action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecureComponentsPresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user,
                new \Nette\Bridges\ApplicationLatte\TemplateFactory(
                    new \Kusebauch\NetteSecurityAnnotation\Tests\LatteFactory()
                ));
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "unknown", [
                'action' => 'unknown'
            ]));
        }, ['throws' => Nette\Application\ForbiddenRequestException::class]);
    }

    public function testComponentsSecured()
    {
        $this->specify("Access granted to secured presenter action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecureComponentsPresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['component', 'create'])->andReturn(TRUE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user,
                new \Nette\Bridges\ApplicationLatte\TemplateFactory(
                    new \Kusebauch\NetteSecurityAnnotation\Tests\LatteFactory()
                ));
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "privilege", ['action' => 'privilege']));
        });

        $this->specify("Access not granted to secured presenter action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecureComponentsPresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['component', 'create'])->andReturn(FALSE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user,
                new \Nette\Bridges\ApplicationLatte\TemplateFactory(
                    new \Kusebauch\NetteSecurityAnnotation\Tests\LatteFactory()
                ));
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "privilege", ['action' => 'privilege']));
        }, ['throws' => Nette\Application\ForbiddenRequestException::class]);
    }
}