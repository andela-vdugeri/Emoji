<?php

namespace spec\Verem\Emoji\Api;

use Mockery;
use Prophecy\Prophet;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Verem\Emoji\Api\Authenticate;

class AuthenticateSpec extends ObjectBehavior
{
	private $prophet;

	public function  let(Authenticate $authenticate)
	{
		$this->beConstructedWith('danverem', 'password');
		$this->prophet = new Prophet();
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\Authenticate');
    }

	public function it_should_return_true_on_is_valid()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\AuthenticateStub');
		$mock->shouldReceive('isValid')
			->with('danverem', 'password')
			->once()
			->andReturn(true);
	}

	public function it_should_return_error_message_on_is_valid()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\AuthenticateStub');
		$mock->shouldReceive('isValid')
			->with('verem', 'password')
			->once()
			->andReturn("{\"Error\":\"Invalid username or password\"}");
	}


	public function it_should_login_a_user()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\AuthenticateStub');
		$mock->shouldReceive('login')
			->once()
			->andReturn('{"expiry":"today:, "token":"token"');
	}


}
