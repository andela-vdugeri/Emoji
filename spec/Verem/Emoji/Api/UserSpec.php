<?php

namespace spec\Verem\Emoji\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Verem\Emoji\Api\User;

class UserSpec extends ObjectBehavior
{
    public function let(User $user)
    {
        $this->beConstructedWith('danverem', 'password', 'Verem Dugeri');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\User');
    }

    public function it_gets_username_correctly()
    {
        $this->getUsername()->shouldReturn('danverem');
        $this->getUsername()->shouldNotBe('danvery');
    }

    public function it_gets_names_correctly()
    {
        $this->getNames()->shouldReturn('Verem Dugeri');
        $this->getNames()->shouldNotBe('Chidozie Ijeomah');
    }

    public function it_gets_password_correctly()
    {
        $this->getPassword()->shouldBe('password');
        $this->getPassword()->shouldNotReturn('pass');
    }

    public function it_sets_token()
    {
        $this->setToken('3e4df54544de4');
        $this->getToken()->shouldReturn('3e4df54544de4');
        $this->getToken()->shouldNotEqual('3e4df54544de4e');
        $this->getToken()->shouldNotReturn(bin2hex(openssl_random_pseudo_bytes(16)));
    }
}
