<?php

namespace spec\Verem\Emoji\Api\DAO;

use Mockery;
use Prophecy\Prophet;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class EmojiManagerSpec extends ObjectBehavior
{
	private $prophet;
	public function let()
	{
		$this->prophet = new Prophet();
	}
    function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\DAO\EmojiManager');
    }

	public function it_finds_emoji()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\DAO\EmojiManagerStub');
		$mock->shouldReceive('find')
			->with(1)
			->andReturn([
			  'emojiname' => 'dancer',
			  'emojichar' => '34e54d\343ed\6edf75\ux98',
			  'keywords'	=> 'dance, girl, excited',
			  'category' 	=> 'people',
			  'created_at'=> '2015-10-15 12:22:45',
			  'updated_at'=> '2015-10-15 12:22:45'
			]);
	}

	public function it_finds_emoji_by_column_name()
	{
		$this->where('category', '=', 'people')
			->shouldHaveCount(8);

		$this->where('category', '=', 'people')
			->shouldHaveKeyWithValue('keywords', 'olorun, God, forbid');
	}

	public function it_finds_all_emojis()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\DAO\EmojiManager');
		$mock->shouldReceive('all')
			->once()
			->andReturnValues([
			  'emojiname' => 'smiley',
			  'emojichar' => '34e54\3435\6edf45\ux98',
			  'keywords'	=> 'smiles',
			  'category' 	=> 'emoticon',
			  'created_at'=> '2015-10-16 12:22:45',
			  'updated_at'=> '2015-10-16 12:22:45'
			]);
	}

	public function it_deletes_an_emoji()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\DAO\EmojiManagerStub');
		$mock->shouldReceive('delete')
			->with(1)
			->andReturn(true);
	}

	public function it_updates_an_emoji()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\DAO\EmojiManagerStub');
		$mock->shouldReceive('update')
			->with(1)
			->andReturn(true);
	}

	public function it_saves_an_emoji()
	{

	}


}
