<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 15.03.13
 * Time: 12:20
 * To change this template use File | Settings | File Templates.
 */

use Solo\Lib\Mongo\MongoDataSet;

class MongoDataSetTest extends PHPUnit_Framework_TestCase
{
	private $mongoClient = null;

	/** @var MongoDataSet */
	private $dataSet = null;

	public function __construct()
	{
		$this->mongoClient = new MongoClient($GLOBALS["mongo.server"]);
		$this->mongoClient->selectDB($GLOBALS["mongo.dbname"])->execute(file_get_contents(__DIR__. "/resources/db.js"));
	}

	public function __destruct()
	{
		$this->mongoClient->selectDB($GLOBALS["mongo.dbname"])->drop();
	}

	public function setUp()
	{
		$cursor = new MongoCursor($this->mongoClient, $GLOBALS["mongo.dbname"].".user");
		$this->dataSet = new MongoDataSet($cursor, "User");
	}

	public function testGetValues()
	{
		/** @var $doc User */
		foreach ($this->dataSet->getValues() as $doc)
		{
			$this->assertTrue($doc instanceof User);
			$this->assertTrue($doc->id instanceof MongoId);
			$this->assertTrue(is_string($doc->name));
			$this->assertTrue($doc->createAt instanceof MongoDate);
		}
	}

	public function testCurrent()
	{
		$currDoc = $this->dataSet->current();
		$this->assertNull($currDoc);

		$this->dataSet->next();
		$currDoc = $this->dataSet->current();

		$this->assertTrue($currDoc instanceof User);
		$this->assertTrue($currDoc->id instanceof MongoId);
		$this->assertTrue($currDoc->createAt instanceof MongoDate);
		$this->assertEquals("Alice", $currDoc->name);
	}

	public function testIterator()
	{
		$this->assertEquals(3, $this->dataSet->count());
		$this->assertTrue($this->dataSet->hasNext());

		$this->dataSet->next();
		$this->dataSet->next();
		$this->dataSet->next();

		$this->assertFalse($this->dataSet->hasNext());

		$this->dataSet->rewind();
		$this->assertTrue($this->dataSet->hasNext());
		$this->assertTrue($this->dataSet->valid());

		$this->assertEquals("51486d47c674d9fbd71ac4b4", $this->dataSet->key());
	}

	public function testSelection()
	{
		$docs = $this->dataSet
			->sort(array("name" => MongoCollection::DESCENDING))
			->skip(1)
			->limit(1)
			->getValues();

		$this->assertCount(1, $docs);
		$this->assertEquals("Bob", $docs[0]->name);
	}

}
