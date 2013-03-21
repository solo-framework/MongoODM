<?php
/**
 * Замена для стандартного курсора драйвера.
 * Реализует получение списков сущностей заданного типа.
 *
 * PHP version 5
 *
 * @package MongoODM
 * @author  Andrey Filippov <afi@i-loto.ru>
 */

namespace Solo\Lib\Mongo;

/**
 * @method DataSet limit(int $num)
 * @method DataSet sort(array $fields)
 * @method DataSet skip( int $num )
 * @method int count()
 */
class DataSet implements \Iterator
{
	/**
	 * Ссылка на курсор
	 *
	 * @var \MongoCursor
	 */
	protected $cursor = null;

	/**
	 * Имя класса сущности
	 *
	 * @var string
	 */
	protected $className = null;

	/**
	 * @param \MongoCursor $cursor
	 * @param string $className
	 */
	public function __construct($cursor, $className)
	{
		$this->cursor = $cursor;
		$this->className = $className;
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return DataSet|mixed
	 */
	public function __call($name, $arguments)
	{
		$res = call_user_func_array(array($this->cursor, $name), $arguments);
		if ($res instanceof \MongoCursor)
			return $this;
		else
			return $res;
	}

	/**
	 * Возвращает список всех сущностей, на
	 * которые ссылается курсор
	 *
	 * @return Entity[]
	 */
	public function getValues()
	{
		return iterator_to_array($this, false);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 */
	public function current()
	{
		$doc = $this->cursor->current();

		if (is_null($doc))
			return null;

		$mapper = new DocEntitytMapper($doc, $this->className);
		return $mapper->mapByFieldsMeta();
	}

	/**
	 * Return the next object to which this cursor points, and advance the cursor
	 * @link http://www.php.net/manual/en/mongocursor.getnext.php
	 * @throws \MongoConnectionException
	 * @throws \MongoCursorTimeoutException
	 * @return array Returns the next object
	 */
	public function getNext()
	{
		$doc = $this->cursor->getNext();

		if (is_null($doc))
			return null;

		$mapper = new DocEntitytMapper($doc, $this->className);
		return $mapper->mapByFieldsMeta();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 *
	 * @return void Any returned value is ignored.
	 */
	public function next()
	{
		$this->cursor->next();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 *
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key()
	{
		return $this->cursor->key();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 *
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid()
	{
		return $this->cursor->valid();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 *
	 * @return void Any returned value is ignored.
	 */
	public function rewind()
	{
		$this->cursor->rewind();
	}


	/**
	 * Checks if there are any more elements in this cursor
	 * @link http://www.php.net/manual/en/mongocursor.hasnext.php
	 * @throws MongoConnectionException
	 * @throws MongoCursorTimeoutException
	 *
	 * @return bool Returns true if there is another element
	 */
	public function hasNext()
	{
		return $this->cursor->hasNext();
	}
}
?>
