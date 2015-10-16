<?php

namespace spec\Verem\Emoji\Api\DAO;

use Mockery;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class EmojiManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\DAO\EmojiManager');
    }

	public function it_finds_emoji()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\DAO\EmojiManager');
		$mock->shouldReceive('find')
			->with(1)
			->andReturn([]);
	}

	public function it_finds_emoji_by_column_name()
	{
		$this->where('category', '=', 'people')
			->shouldHaveCount(8);

		$this->where('category', '=', 'people')
			->shouldHaveKeyWithValue('keywords', 'olorun, God, forbid');
	}


}
