<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 15.03.13
 * Time: 12:21
 * To change this template use File | Settings | File Templates.
 */

use App\Entity\Article;
use App\Entity\ODMUser;
use App\Manager\ArticleManager;
use App\Manager\OdmUserManager;

class MongoEntityManagerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var OdmUserManager
	 */
	private $um = null;

	/**
	 * @var ArticleManager
	 */
	private $am = null;

	public function __construct()
	{
		$this->um = new OdmUserManager();
		$this->am = new ArticleManager();
	}

	public function setUp()
	{
		$this->um->getConnection()->getMongoDB()->execute(file_get_contents(__DIR__. "/resources/db.js"));
	}

	public function tearDown()
	{
		$this->um->getConnection()->getMongoDB()->drop();
	}

	public function testFind()
	{
		// all
		$dataSet = $this->um->find();
		$this->assertEquals(3, $dataSet->count());
		foreach ($dataSet->getValues() as $doc)
		{
			$this->assertTrue($doc instanceof ODMUser);
		}

		// simple
		$dataSet = $this->um->find(array("name" => "Alice"));
		$this->assertEquals(1, $dataSet->count());
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof ODMUser);
		$this->assertEquals("Alice", $u->name);

		// $and
		$dataSet = $this->um->find(array("name" => "Carl", "age" => array('$gte' => 30)));
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof ODMUser);
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
		$this->assertTrue($u instanceof ODMUser);
		$this->assertEquals("Alice", $u->name);

		// $nin
		$dataSet = $this->um->find(array("name" => array('$nin' => array("Alice", "Mike", "Jounh"))));
		$this->assertEquals(2, $dataSet->count());
		$this->assertEquals("Bob", $dataSet->getNext()->name);
		$this->assertEquals("Carl", $dataSet->getNext()->name);

		// regexp
		$dataSet = $this->um->find(array("name" => new MongoRegex("/^B/")));
		$u = $dataSet->getNext();
		$this->assertTrue($u instanceof ODMUser);
		$this->assertEquals("Bob", $u->name);

		// $all
		$dataSet = $this->am->find(array("tags" => array('$all' => array("one", "two"))));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// a.b
		$dataSet = $this->am->find(array("grades.value" => 5));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// $elementMatch
		$dataSet = $this->am->find(array("comments" => array('$elemMatch' => array("text" => "first comment"))));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// $size
		$dataSet = $this->am->find(array("comments" => array('$size' => 2)));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// $exists
		$dataSet = $this->am->find(array("title" => array('$exists' => true)));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// $type
		$dataSet = $this->am->find(array("title" => array('$type' => 2)));
		$a = $dataSet->getNext();
		$this->assertTrue($a instanceof Article);

		// empty
		$dataSet = $this->um->find(array("name" => "Jounh"));
		$this->assertEquals(0, $dataSet->count());

		$dataSet = $this->um->find(array("name" => null));
		$this->assertEquals(0, $dataSet->count());
	}

	public function testFindById()
	{
		// as string
		$u = $this->um->findById("51486d47c674d9fbd71ac4b4");
		$this->assertEquals("Alice", $u->name);
		$this->assertEquals(new MongoId("51486d47c674d9fbd71ac4b4"), $u->id);

		// as mongoid
		$u = $this->um->findById(new MongoId("51486d47c674d9fbd71ac4b4"));
		$this->assertEquals("Alice", $u->name);
		$this->assertEquals(new MongoId("51486d47c674d9fbd71ac4b4"), $u->id);

		// empty
		$u = $this->um->findById(new MongoId("51486d47c674d9fbd71acXXX"));
		$this->assertNull($u);

		$u = $this->um->findById(null);
		$this->assertNull($u);

		$u = $this->um->findById("");
		$this->assertNull($u);
	}

	public function testFindOne()
	{
		// simple
		/** @var $u ODMUser */
		$u = $this->um->findOne(array("name" => "Alice"));
		$this->assertTrue($u instanceof ODMUser);
		$this->assertEquals("Alice", $u->name);

		$this->assertNotEmpty($u->createAt);
		$this->assertNotEmpty($u->id);
		$this->assertNotEmpty($u->age);

		//ignored fields
		$this->assertEmpty($u->friends);

		// $and
		$u = $this->um->findOne(array("name" => "Carl", "age" => array('$gte' => 30)));
		$this->assertTrue($u instanceof ODMUser);
		$this->assertEquals("Carl", $u->name);
		$this->assertEquals(30, $u->age);

		// empty
		$u = $this->um->findOne(array("name" => "Jounh"));
		$this->assertNull($u);
	}

	public function testFetchField()
	{
		$name = $this->um->fetchField(array("name" => "Alice"), "name");
		$this->assertEquals("Alice", $name);

		$name = $this->um->fetchField(array("name" => "Jounh"), "name");
		$this->assertNull($name);

		$id = $this->um->fetchField(array("_id" => new MongoId("51486d47c674d9fbd71ac4b4")), "_id");
		$this->assertEquals(new MongoId("51486d47c674d9fbd71ac4b4"), $id);

		try
		{
			$this->um->fetchField(array("name" => "Alice"), "");
			$this->fail();
		}
		catch (MongoException $e)
		{
			$this->assertTrue(true);
		}
	}

	public function testFetchColumn()
	{
		$names = $this->um->fetchColumn(array(), "name");
		$this->assertEquals(array("Alice", "Bob", "Carl"), $names);

		$names = $this->um->fetchColumn(array("age" => array('$gt' => 50)), "name");
		$this->assertEquals(array(), $names);

		try
		{
			$this->um->fetchColumn(array(), "");
			$this->fail();
		}
		catch (MongoException $e)
		{
			$this->assertTrue(true);
		}
	}

	public function testFetchPartEntity()
	{
		/** @var $u ODMUser */
		$u = $this->um->fetchPartEntity(array("name" => "Alice"), array("name" => true));

		$this->assertEquals("Alice", $u->name);
		$this->assertEquals(new MongoId("51486d47c674d9fbd71ac4b4"), $u->id);
		$this->assertNull($u->createAt);
		$this->assertNull($u->age);
		$this->assertEmpty($u->friends);

		$u = $this->um->fetchPartEntity(array("name" => "Alice"), array("friends" => true));
		$this->assertEquals(new MongoId("51486d47c674d9fbd71ac4b4"), $u->id);
		$this->assertEquals(array(
             new MongoId("51486d47c674d9fbd71ac4b5"),
             new MongoId("51486d47c674d9fbd71ac4b6")
		), $u->friends);

		$this->assertNull($u->name);
		$this->assertNull($u->createAt);
		$this->assertNull($u->age);

		$u = $this->um->fetchPartEntity(array("name" => "Alice"), array());

		$this->assertEquals(new MongoId("51486d47c674d9fbd71ac4b4"), $u->id);
		$this->assertEquals("Alice", $u->name);
		$this->assertTrue($u->createAt instanceof MongoDate);
		$this->assertEquals(20, $u->age);
		$this->assertEquals(array(
		     new MongoId("51486d47c674d9fbd71ac4b5"),
		     new MongoId("51486d47c674d9fbd71ac4b6")
		), $u->friends);

		try
		{
			$this->um->fetchPartEntity(array("name" => "Alice"), array("friends" => true, "name" => false));
			$this->fail();
		}
		catch (MongoCursorException $e)
		{
			$this->assertTrue(true);
		}
	}

	public function testFindAndModify()
	{
		$u = $this->um->findAndModify(
			array("name" => "Alice"),
			array('$set' => array("name" => "Mary"), '$inc' => array("age" => 1)),
			array("new" => true)
		);
		$this->assertEquals("Mary", $u->name);
		$this->assertEquals(21, $u->age);
	}

	public function testRemoveById()
	{
		$this->assertNotNull($this->um->findById("51486d47c674d9fbd71ac4b4"));
		$this->um->removeById("51486d47c674d9fbd71ac4b4");
		$this->assertNull($this->um->findById("51486d47c674d9fbd71ac4b4"));

		$this->assertNotNull($this->um->findById(new MongoId("51486d47c674d9fbd71ac4b5")));
		$this->um->removeById(new MongoId("51486d47c674d9fbd71ac4b5"));
		$this->assertNull($this->um->findById(new MongoId("51486d47c674d9fbd71ac4b5")));
	}

	public function testRemove()
	{
		$this->um->remove(array("age" => array('$gt' => 17)));
		$this->assertEquals(1, $this->um->find()->count());
		$this->assertNotNull($this->um->findById(new MongoId("51486d47c674d9fbd71ac4b5")));
	}

	public function testRemoveAll()
	{
		$this->um->removeAll();
		$this->assertEquals(0, $this->um->find()->count());
	}

	public function testSave()
	{
		//insert
		$u = new ODMUser();
		$u->name = "Jounh";
		$u->age = 50;
		$u->createAt = new MongoDate();
		$u->friends = array(1, 2, 3);
		$this->assertNull($u->id);

		$u = $this->um->save($u);
		$this->assertTrue($u->id instanceof MongoId);

		$u = $this->um->findById($u->id);
		$this->assertEquals("Jounh", $u->name);
		$this->assertEquals(50, $u->age);

		//ignored fields not saved
		$this->assertEquals(array(), $u->friends);

		//update
		$u = $this->um->findById(new MongoId("51486d47c674d9fbd71ac4b4"));
		$u->name = "Mary";

		$uNew = $this->um->save($u);
		$this->assertEquals($u->id, $uNew->id);
		$this->assertEquals("Mary", $u->name);
	}

	public function testUpdate()
	{
		$this->um->update(
			array("name" => "Alice"),
			array('$set' => array("name" => "Mary"), '$inc' => array("age" => 1))
		);

		$u = $this->um->findById(new MongoId("51486d47c674d9fbd71ac4b4"));

		$this->assertEquals("Mary", $u->name);
		$this->assertEquals(21, $u->age);
	}

	public function testUpdateById()
	{
		$this->um->updateById(
			new MongoId("51486d47c674d9fbd71ac4b4"),
			array('$set' => array("name" => "Mary"), '$inc' => array("age" => 1))
		);

		$u = $this->um->findById(new MongoId("51486d47c674d9fbd71ac4b4"));

		$this->assertEquals("Mary", $u->name);
		$this->assertEquals(21, $u->age);
	}

	public function testGetCollection()
	{
		$this->assertTrue($this->um->getCollection() instanceof MongoCollection);
	}

	public function testBuildObjectIdList()
	{
		$ids = OdmUserManager::buildObjectIdList(array("51486d47c674d9fbd71ac4b5", "51486d47c674d9fbd71ac4b6"));
		$this->assertEquals(array(
             new MongoId("51486d47c674d9fbd71ac4b5"),
             new MongoId("51486d47c674d9fbd71ac4b6")
        ), $ids);

		$this->assertEquals(array(), OdmUserManager::buildObjectIdList(array()));
	}
}
