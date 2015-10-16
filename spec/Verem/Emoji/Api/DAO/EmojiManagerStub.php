<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/16/15
 * Time: 11:19 AM
 */

namespace spec\Verem\Emoji\Api\DAO;


use Verem\Emoji\Api\DAO\EmojiManager;

class EmojiManagerStub extends EmojiManager
{

	public function find($id)
	{
		return [
		  'emojiname' => 'dancer',
		  'emojichar' => '34e54d\343ed\6edf75\ux98',
		  'keywords'	=> 'dance, girl, excited',
		  'category' 	=> 'people',
		  'created_at'=> '2015-10-15 12:22:45',
		  'updated_at'=> '2015-10-15 12:22:45'
		];
	}
	public function all()
	{
		return [
			'emojiname' => 'smiley',
			'emojichar' => '34e54\3435\6edf45\ux98',
			'keywords'	=> 'smiles',
			'category' 	=> 'emoticon',
			'created_at'=> '2015-10-16 12:22:45',
			'updated_at'=> '2015-10-16 12:22:45'
		];
	}

	public function delete($id)
	{
		return true;
	}

	public function update($id)
	{
		return true;
	}

}