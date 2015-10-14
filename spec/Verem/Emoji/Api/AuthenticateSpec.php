<?php

namespace spec\Verem\Emoji\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Verem\Emoji\Api\Authenticate;

class AuthenticateSpec extends ObjectBehavior
{
	public function  let(Authenticate $authenticate)
	{
		$this->beConstructedWith('danverem', 'password');
	}
    function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\Authenticate');
    }

	public function it_should_return_true_on_is_valid()
	{
		$this->isValid('danverem', 'password')->shouldReturn(true);
	}

	public function it_should_return_error_message_on_is_valid()
	{
		$this->isValid('verem', 'password')->shouldReturn("{\"Error\":\"Invalid username or password\"}");
	}

//	public function it_should_login_a_user($auth)
//	{
//		$auth->beADoubleOf('Verem\Emoji\Api\Authenticate');
//		$auth->login()->shouldBeCalled();
//		$auth->login()->willReturn("{\"Expiry\":\"2015-10-10 22:16:22\",\"token\":\"45df5676234e\"}");
//		$this->login()->shouldReturn("{\"Expiry\":\"2015-10-10 22:16:22\",\"token\":\"45df5676234e\"}");
//	}

}
