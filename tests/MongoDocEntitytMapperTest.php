<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 15.03.13
 * Time: 12:20
 * To change this template use File | Settings | File Templates.
 */

use Solo\Lib\Mongo\MongoDocEntitytMapper;
use Solo\Lib\Mongo\MongoEntity;

class MongoDocEntitytMapperTest extends PHPUnit_Framework_TestCase
{
	public function testMapByFieldsMeta()
	{
		$docEntMapper = new MongoDocEntitytMapper($this->getArticleData(), "Article");
		$article = $docEntMapper->mapByFieldsMeta();

		$this->assertTrue($article->id instanceof MongoId);
		$this->assertFalse(isset($article->_id));
	}

	public function testArrayToObjectRecurively()
	{
		$docEntMapper = new MongoDocEntitytMapper($this->getArticleData(), "Article");

		/** @var $article Article */
		$article = $this->invokeMethod(
			$docEntMapper,
			"arrayToObjectRecurively",
			array($this->getArticleData(), array("type" => MongoEntity::TYPE_ENTITY, "class" => "Article"))
		);

		$this->assertTrue($article instanceof Article);
		$this->assertEquals("My cool article", $article->title);
		$this->assertEquals("Cool", $article->content);
		$this->assertEquals(array("one", "two"), $article->tags);
		$this->assertEquals(array("value" => 5, "novelty" => 10), $article->grades);
		$this->assertTrue($article->createTime instanceof MongoDate);
		$this->assertEquals(array("url" => "http://example.com", "desc" => "Cool photo"), $article->photo);

		$this->assertTrue($article->author instanceof Author);
		$this->assertEquals("Piter", $article->author->name);

		$this->assertTrue($article->author->address instanceof Address);
		$this->assertEquals("USA", $article->author->address->country);
		$this->assertEquals("NY", $article->author->address->city);

		$this->assertEquals(array(
             new Phone(Phone::TYPE_HOME, "911"),
             new Phone(Phone::TYPE_WORK, "12345")
		), $article->author->address->phones);

		$this->assertEquals(array(
             new Comment("first comment"),
             new Comment("second comment")
        ), $article->comments);

		$article = $this->invokeMethod(
			$docEntMapper,
			"arrayToObjectRecurively",
			array($this->getArticleData(), array("type" => "NOTHING", "class" => "Article"))
		);

		$this->assertNull($article);

	}

	private function getArticleData()
	{
		// Artcle object
		$article = array(
			"_id" => new MongoId(),
			"title" => "My cool article",
			"content" => "Cool",
			"tags" => array("one", "two"),
			"grades" => array("value" => 5, "novelty" => 10),
			"createTime" => new MongoDate(),

			// Phone object (not mapped)
			"photo" => array(
				"url" => "http://example.com",
				"desc" => "Cool photo"
			),

			// Author object
			"author" => array(
				"name" => "Piter",

				// Address object
				"address" => array(
					"country" => "USA",
					"city" => "NY",

					// Phone objects collection
					"phones" => array(
						array(
							"type" => Phone::TYPE_HOME,
							"value" => "911"
						),
						array(
							"type" => Phone::TYPE_WORK,
							"value" => "12345"
						)
					)
				)
			),

			// Comments objects collection
			"comments" => array(
				array("text" => "first comment"),
				array("text" => "second comment")
			)
		);

		return $article;
	}

	private function invokeMethod(&$object, $methodName, array $parameters = array())
	{
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}
}
