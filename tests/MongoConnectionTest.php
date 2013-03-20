<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eugene
 * Date: 15.03.13
 * Time: 12:15
 * To change this template use File | Settings | File Templates.
 */

use Solo\Lib\Mongo\MongoConnection;

class MongoConnectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * MongoConnection
	 *
	 * @var null
	 */
	private $mongocon = null;


	public function testConnection()
	{
		$this->mongocon = new MongoConnection(
			$GLOBALS["mongo.server"], $GLOBALS["mongo.dbname"], array()
		);

		$res = $this->mongocon->getMongoDB()->lastError();

		$this->assertTrue($this->mongocon->getMongoDB() instanceof MongoDB);
		$this->assertNull($res["err"]);
		$this->assertNotNull($res["connectionId"]);
	}

	/**
	 * @expectedException MongoConnectionException
	 */
	public function testInvalidConnection()
	{
		$this->mongocon = new MongoConnection(
			"foo", "bar", array()
		);
	}
}
