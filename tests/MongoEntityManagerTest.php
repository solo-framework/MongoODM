<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 15.03.13
 * Time: 12:21
 * To change this template use File | Settings | File Templates.
 */

use Solo\Lib\Mongo\MongoDataSet;

class MongoEntityManagerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var UserManager
	 */
	private $um = null;

	/**
	 * @var ArticleManager
	 */
	private $am = null;

	public function __construct()
	{
		$this->um = new UserManager();
		$this->am = new ArticleManager();
	}

	public function testFind()
	{
		// all
		$dataSet = $this->um->find();
		$this->assertEquals(3, $dataSet->count());
		foreach ($dataSet->getValues() as $doc)
		{
			$this->assertTrue($doc instanceof User);
		}

		// simple
		$dataSet = $this->um->find(array("name" => "Alice"));
		$this->assertEquals(1, $dataSet->count());
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof User);
		$this->assertEquals("Alice", $u->name);

		// $and
		$dataSet = $this->um->find(array("name" => "Carl", "age" => array('$gte' => 30)));
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof User);
		$this->assertEquals("Carl", $u->name);
		$this->assertEquals(30, $u->age);

		// $or
		$dataSet = $this->um->find(array('$or' => array(array("name" => "Alice"), array("name" => "Bob"))));
		$this->assertEquals(2, $dataSet->count());
		$this->assertEquals("Alice", $dataSet->getNext()->name);
		$this->assertEquals("Bob", $dataSet->getNext()->name);

		// $in
		$dataSet = $this->um->find(array("name" => array('$in' => array("Alice", "Mike", "Jounh"))));
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof User);
		$this->assertEquals("Alice", $u->name);

		// $nin
		$dataSet = $this->um->find(array("name" => array('$nin' => array("Alice", "Mike", "Jounh"))));
		$this->assertEquals(2, $dataSet->count());
		$this->assertEquals("Bob", $dataSet->getNext()->name);
		$this->assertEquals("Carl", $dataSet->getNext()->name);

		// regexp
		$dataSet = $this->um->find(array("name" => new MongoRegex("/^B/")));
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof User);
		$this->assertEquals("Bob", $u->name);

		// $all
		$dataSet = $this->am->find(array("tags" => array('$all' => array("one", "two"))));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// a.b
		$dataSet = $this->am->find(array("grades.value" => 5));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// elementMatch
		$dataSet = $this->am->find(array("comments" => array('$elemMatch' => array("text" => "first comment"))));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// size
		$dataSet = $this->am->find(array("comments" => array('$size' => 2)));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// exists
		$dataSet = $this->am->find(array("title" => array('$exists' => true)));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// type
		$dataSet = $this->am->find(array("title" => array('$type' => 2)));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		//todo: evkur: Проверка, если не нашел
	}

	public function testFindById()
	{

	}

	public function testFindOne()
	{
		//todo: evkur: Проверка игнорирования полей
	}

	public function testFetchField()
	{

	}

	public function testFetchColumn()
	{

	}

	public function testFetchPartEntity()
	{

	}

	public function testFindAndModify()
	{

	}

	public function testRemoveById()
	{

	}

	public function testRemove()
	{

	}

	public function testRemoveAll()
	{

	}

	public function testSave()
	{

	}

	public function testUpdate()
	{

	}

	public function testUpdateById()
	{

	}

	public function testGetCollection()
	{
		$this->assertTrue($this->um->getCollection() instanceof MongoCollection);
	}

	public function testBuildObjectIdList()
	{

	}
}
