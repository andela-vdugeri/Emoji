<?php

namespace spec\Verem\Emoji\Api;

use Prophecy\Argument;
use Verem\Emoji\Api\Emoji;
use PhpSpec\ObjectBehavior;

class EmojiSpec extends ObjectBehavior
{
	function let(Emoji $emoji)
	{
		$this->beConstructedWith('Girlie','9u74ED3', 'hello, there','people');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\Emoji');
    }

	public function it_sets_updated_at()
	{
		$this->setUpdatedAt('2015-10-10 22:14:22');
		$this->getUpdatedAt()->shouldReturn('2015-10-10 22:14:22');
		$this->getUpdatedAt()->shouldNotBeEqualTo(date('Y-m-d H:i:s'));
	}

	public function it_sets_created_at()
	{
		$this->setCreatedAt('2015-10-10 22:14:22');
		$this->getCreatedAt()->shouldBeEqualTo('2015-10-10 22:14:22');
	}


	public function it_sets_created_by()
	{
		$this->setCreatedBy(2);
		$this->getcreatedBy()->shouldEqual(2);
	}

	public function it_gets_name_of_emoji()
	{
		$this->getName()->shouldEqual('Girlie');
		$this->getName()->shouldBeString();
	}

	public function it_gets_category_of_emoji()
	{
		$this->getCategory()->shouldReturn('people');
	}

	public function it_gets_emoji_keywords()
	{
		$this->getKeywords()->shouldEqual('hello, there');
	}

	public function it_gets_emoji_character()
	{
		$this->getChar()->shouldReturn('9u74ED3');
	}
}
