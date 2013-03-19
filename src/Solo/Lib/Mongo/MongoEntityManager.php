<?php
/**
 * Абстрактный менеджер сущностей
 *
 * PHP version 5
 *
 * @package MongoODM
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace Solo\Lib\Mongo;

abstract class MongoEntityManager implements IMongoEntityManager
{
	/**
	 * Объект коллекции
	 *
	 * @var \MongoCollection
	 */
	protected $collection = null;

	/**
	 * Имя коллекции
	 *
	 * @var null|string
	 */
	protected $collectionName = null;

	/**
	 * Дефолтные опции для вставки
	 * The write will be acknowledged by the server
	 * @see http://www.php.net/manual/en/mongo.writeconcerns.php
	 *
	 * @var array
	 */
	protected $defaultWriteOptions = array("w" => 1);

	/**
	 * Конструтор
	 */
	public function __construct()
	{
		if ($this->collectionName == null)
		{
			$managerName = get_called_class();
			$this->collectionName = strtolower(str_replace("Manager", "", $managerName));
		}

		$this->collection = $this->getConnection()->getMongoDB()->selectCollection($this->collectionName);
	}

	/**
	 * Должен возвращать объект MongoDB
	 *
	 * @return MongoConnection
	 */
	abstract public function getConnection();

	/**
	 * Возвращает список сущностей по условию
	 *
	 * @param array $condition Условие выборки
	 *
	 * @return MongoDataSet
	 */
	public function find($condition = array())
	{
		$entityClass = ucfirst($this->collectionName);

		$cursor = $this->collection->find($condition, $entityClass::getFieldsMeta());
		$cursor = new MongoDataSet($cursor, $entityClass);
		return $cursor;
	}

	/**
	 * Возвращает сущность по идентификатору
	 *
	 * @param string $objectId Mongo ID
	 *
	 * @return MongoEntity|null
	 */
	public function findById($objectId)
	{
		$entityName = ucfirst($this->collectionName);
		$doc = $this->collection->findOne(array("_id" => new \MongoId($objectId)), $entityName::getFieldsMeta());
		if (!$doc)
			return null;
		$mapper = new MongoDocEntitytMapper($doc, ucfirst($this->collectionName));
		return $mapper->mapByFieldsMeta();
	}

	/**
	 * Возвращает сущность по заданному условию
	 *
	 * @param array $condition Условие выборки
	 *
	 * @return MongoEntity|null
	 */
	public function findOne($condition)
	{
		$entityName = ucfirst($this->collectionName);
		$doc = $this->collection->findOne($condition, $entityName::getFieldsMeta());
		if (!$doc)
			return null;

		$mapper = new MongoDocEntitytMapper($doc, ucfirst($this->collectionName));
		return $mapper->mapByFieldsMeta();
	}

	/**
	 * Возвращает поле документа по условию.
	 * Должен быть найден только один документ по
	 * заданному условию
	 *
	 * @param array $condition Условие выборки
	 * @param string $name Название поля
	 *
	 * @throws \MongoException
	 * @return mixed
	 */
	public function fetchField($condition, $name)
	{
		$returnId = false;
		if ("_id" == $name)
			$returnId = true;

		$cursor = $this->collection->find($condition, array($name => true, "_id" => $returnId));
		if ($cursor->count() > 1)
			throw new \MongoException("More than one record has been found");

		$doc = $cursor->getNext();
		if (!$doc)
			return null;

		return $doc[$name];
	}

	/**
	 * Возвращает значения указанного поля для найденных элементов коллекции
	 * ("столбец" значений)
	 *
	 * @param array $condition Условие поиска
	 * @param string $name Имя атрибута коллекции
	 *
	 * @return array
	 */
	public function fetchColumn($condition, $name)
	{
		$returnId = false;
		if ("_id" == $name)
			$returnId = true;

		$cursor = $this->collection->find($condition, array($name => true, "_id" => $returnId));
		$res = array();

		foreach ($cursor as $val)
			$res[] = $val[$name];

		return $res;
	}

	/**
	 * Возвращает сущность с заданным набором полей
	 *
	 * @param array $condition Условие выборки
	 * @param array $retFields Список названий полей, которые будут установлены
	 *
	 * @return array|null
	 */
	public function fetchPartEntity(array $condition, array $retFields)
	{
		$doc = $this->collection->findOne($condition, $retFields);
		if (!$doc)
			return null;
		$mapper = new MongoDocEntitytMapper($doc, ucfirst($this->collectionName));
		return $mapper->mapByFieldsMeta();
	}

	/**
	 * Модифицирует и возвращает документ
	 *
	 * @param array $condition Условие выборки
	 * @param array $update Параметры обновления
	 * @param array $options Опции
	 *
	 * @return MongoEntity|null
	 */
	public function findAndModify(array $condition, array $update, array $options = array())
	{
		$entityClass = ucfirst($this->collectionName);

		$doc = $this->collection->findAndModify($condition, $update, $entityClass::getFieldsMeta(), $options);
		if (!$doc)
			return null;
		$mapper = new MongoDocEntitytMapper($doc, ucfirst($this->collectionName));
		return $mapper->mapByFieldsMeta();
	}

	/**
	 * Удаление документа по идентификатору
	 *
	 * @param string $objectId Mongo ID
	 *
	 * @return void
	 */
	public function removeById($objectId)
	{
		$this->collection->remove(
			array("_id" => new \MongoId($objectId)),
			array_merge($this->defaultWriteOptions, array("justOne" => true))
		);
	}

	/**
	 * Удаление документов по условию
	 *
	 * @param array $condition Условие выборки
	 *
	 * @return void
	 */
	public function remove($condition)
	{
		$this->collection->remove($condition, $this->defaultWriteOptions);
	}

	/**
	 * Удаление всех документов в коллекции
	 *
	 * @return void
	 */
	public function removeAll()
	{
		$this->collection->remove(array());
	}

	/**
	 * Сохрание сущности
	 *
	 * @param MongoEntity $object Сущность
	 *
	 * @return MongoEntity
	 */
	public function save(MongoEntity $object)
	{
		$doc = (array)$object;

		if (!isset($doc["id"]))
			$id = new \MongoId();
		else
			$id = new \MongoId($doc["id"]);

		unset($doc["id"]);

		foreach ($object->getFieldsMeta() as $name => $val)
		{
			if ($val === false)
				unset($doc[$name]);
		}

		$res = $this->collection->update(array("_id" => $id), array('$set' => $doc), array("upsert" => true));

		if (is_null($object->id))
			$object->id = $res["upserted"];

		return $object;
	}

	/**
	 * Обновление документов по условию
	 *
	 * @param array $condition Условие выборки
	 * @param array $update Параметры обновления
	 * @param array $options Опции
	 *
	 * @return bool
	 * @throws \MongoException
	 */
	public function update(array $condition, array $update, array $options = array())
	{
		return $this->collection->update($condition, $update, $options);
	}

	/**
	 * Обновление документа по идентификатору
	 *
	 * @param string $objectId Mongo ID
	 * @param array $update Параметры обновления
	 *
	 * @return void
	 */
	public function updateById($objectId, $update)
	{
		$this->collection->update(
			array("_id" => new \MongoId($objectId)),
			$update,
			array_merge($this->defaultWriteOptions, array("multiple" => false))
		);
	}

	/**
	 * Возвращает экземпляр коллекции mongo
	 *
	 * @return \MongoCollection|null
	 */
	public function getCollection()
	{
		return $this->collection;
	}

	/**
	 * Создает по списку строковых идентификаторов список MongoId
	 *
	 * @param array $ids Список идентификатров
	 *
	 * @return array
	 */
	public static function buildObjectIdList(array $ids)
	{
		$func = function($val) {
			return new \MongoId($val);
		};

		return array_map($func, $ids);
	}
}
