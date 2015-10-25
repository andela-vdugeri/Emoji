<?php

namespace spec\Verem\Emoji\Api\DAO;

use Mockery;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Verem\Emoji\Api\DAO\UserManager;
use Verem\Emoji\Api\User;

class UserManagerSpec extends ObjectBehavior
{
	private  $user;

	public function let()
	{
		$this->user = new User('Dan','Verem','Verem Dugeri');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Verem\Emoji\Api\DAO\UserManager');
    }

	function it_finds_a_user_with_specified_id(UserManager $user)
	{
		$mock = Mockery::mock('Verem\Emoji\Api\User');
		$mock->shouldReceive('find')
			->with(1)
			->once()
			->andReturnValues(['username' => 'danverem', 'password', 'password']);
	}

	function it_finds_a_user_by_column()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\User');
		$mock->shouldReceive('where')
			->with('username', '=', 'danverem')
			->once()
			->andReturnValues(['username' => 'danverem', 'password' => 'password']);
	}

	public function it_finds_all_users()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\User');
		$mock->shouldReceive('all')
			->once()
			->andReturnValues(['username' => 'danverem', 'password' =>'password']);
	}

	public function it_deletes_a_user_from_database()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\UserManager');
		$mock->shouldReceive('delete')
		  ->with(1)
		  ->once()
		  ->andReturn(true);

		$mock->shouldReceive('find')
		  ->with(1)
		  ->once()
		  ->andThrow('Verem\Emoji\Api\Exceptions\RecordNotFoundException');
	}


	public function it_saves_a_user_to_database()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\User');
		$mock->shouldReceive('save')
			->with($this->user)
			->once()
			->andReturn(true);
	}

	public function it_converts_array_to_json()
	{
		$array = [
		  	'username' => 'johndoe',
			'password' => 'password',
			'names'	   => 'John Doe'
		];

		$this->toJson($array)
			->shouldReturn("{\"username\":\"johndoe\",\"password\":\"password\",\"names\":\"John Doe\"}");
	}

	public function it_updates_token()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\UserManager');
		$mock->shouldReceive('updateToken')
			->with('2015-10-15 12:12:12','token', 'danverem')
			->once()
			->andReturn("{'status': 200,'message':'token expiry set'}");
	}

	public function it_invalidates_session()
	{
		$mock = Mockery::mock('Verem\Emoji\Api\UserManager');
		$mock->shouldReceive('invalidateSession')
			->with('b18715e6a129581391db81d4288babe1')
			->once()
			->andReturn("{'status: 200, 'message':'session invalidated'}");
	}

}
