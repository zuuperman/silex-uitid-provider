<?php

namespace CultuurNet\UiTIDProvider\User;

use CultuurNet\Auth\TokenCredentials;
use CultuurNet\Auth\User as MinimalUserInfo;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class UserSessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MinimalUserInfo
     */
    protected $minimalUserInfo;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var UserSessionService
     */
    protected $service;

    public function setUp()
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->service = new UserSessionService($this->session);

        $id = 1;
        $credentials = new TokenCredentials('token', 'secret');
        $this->minimalUserInfo = new MinimalUserInfo($id, $credentials);
    }

    /**
     * @test
     */
    public function it_can_store_and_retrieve_the_minimal_user_info()
    {
        $this->service->setMinimalUserInfo($this->minimalUserInfo);
        $this->assertEquals($this->minimalUserInfo, $this->service->getMinimalUserInfo());
    }

    /**
     * @test
     */
    public function it_returns_null_when_no_minimal_user_info_is_set()
    {
        $this->assertNull($this->service->getMinimalUserInfo());
    }

    /**
     * @test
     */
    public function it_removes_the_minimal_user_info_when_logging_out()
    {
        $this->service->setMinimalUserInfo($this->minimalUserInfo);
        $this->service->logout();

        $this->assertNull($this->service->getMinimalUserInfo());
    }
}
