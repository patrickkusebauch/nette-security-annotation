<?php


class SecurePresenterTraitTest extends \Codeception\TestCase\Test
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

    public function testActionDefault()
    {
        $this->specify("Access granted to secured presenter action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['action', 'show'])->andReturn(TRUE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "default", []));
        });

        $this->specify("Access not granted to secured presenter action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['action', 'show'])->andReturn(FALSE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "default", []));
        }, ['throws' => Nette\Application\ForbiddenRequestException::class]);
    }

    public function testActionMultiple()
    {
        $this->specify("Access granted to secured presenter action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['action', 'show'])->andReturn(FALSE);
            $user->shouldReceive("isAllowed")->withArgs(['action', 'other'])->andReturn(TRUE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "multiple", ["action" => "multiple"]));
        });

        $this->specify("Access not granted to secured presenter action", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['action', 'show'])->andReturn(FALSE);
            $user->shouldReceive("isAllowed")->withArgs(['action', 'other'])->andReturn(FALSE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "multiple", ["action" => "multiple"]));
        }, ['throws' => Nette\Application\ForbiddenRequestException::class]);
    }

    public function testHandleDefault()
    {
        $this->specify("Access granted to secured presenter signal", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['signal', 'show'])->andReturn(TRUE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "unknown", [
                "do" => "signal",
                "action" => "unknown"
            ]));
        });

        $this->specify("Access not granted to secured presenter signal", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['signal', 'show'])->andReturn(FALSE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "unknown", [
                "do" => "signal",
                "action" => "unknown"
            ]));
        }, ['throws' => Nette\Application\ForbiddenRequestException::class]);
    }

    public function testHandleMulti()
    {
        $this->specify("Access granted to secured presenter signal", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['signal', 'show'])->andReturn(FALSE);
            $user->shouldReceive("isAllowed")->withArgs(['signal', 'other'])->andReturn(TRUE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "unknown", [
                "do" => "multi",
                "action" => "unknown"
            ]));
        });

        $this->specify("Access not granted to secured presenter signal", function() {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $user->shouldReceive("isAllowed")->withArgs(['signal', 'show'])->andReturn(FALSE);
            $user->shouldReceive("isAllowed")->withArgs(['signal', 'other'])->andReturn(FALSE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "unknown", [
                "do" => "multi",
                "action" => "unknown"
            ]));
        }, ['throws' => Nette\Application\ForbiddenRequestException::class]);
    }

    public function testActionInvalid()
    {
        $this->specify("Secured actions has to have a resource", function () {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(TRUE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "invalid", ["action" => "invalid"]));
        }, ['throws' => Nette\InvalidStateException::class]);
    }

    public function testActionNotLoggedIn()
    {
        $this->specify("Secured actions are accessible only by logged in users", function () {
            $presenter = new \Kusebauch\NetteSecurityAnnotation\Tests\SecurePresenterTraitDummy();
            $request = new \Nette\Http\Request(new \Nette\Http\UrlScript);
            $response = new \Nette\Http\Response();
            $session = new \Nette\Http\Session($request, $response);
            $user = Mockery::mock(new \Nette\Security\User(new \Nette\Http\UserStorage($session)));
            $user->shouldReceive("isLoggedIn")->andReturn(FALSE);
            $presenter->injectPrimary(NULL, NULL, NULL, $request, $response, $session, $user);
            $presenter->run(new \Nette\Application\Request("SecurePresenterDummy", "invalid", ["action" => "invalid"]));
        }, ['throws' => \Nette\Application\ForbiddenRequestException::class]);
    }


}