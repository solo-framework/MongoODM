<?php
/**
 * Интерфейс менеджера
 *
 * PHP version 5
 *
 * @package MongoODM
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace Solo\Lib\Mongo;

interface IMongoEntityManager
{
	/**
	 * Возвращает список сущностей по условию
	 *
	 * @param array|null $condition Условие выборки
	 *
	 * @return MongoEntity[]
	 */
	public function find($condition = null);

	/**
	 * Возвращает сущность по идентификатору
	 *
	 * @param string $objectId Mongo ID
	 *
	 * @return MongoEntity|null
	 */
	public function findById($objectId);

	/**
	 * Возвращает сущность по заданному условию
	 *
	 * @param array $condition Условие выборки
	 *
	 * @return MongoEntity|null
	 */
	public function findOne($condition);

	/**
	 * Удаление документа по идентификатору
	 *
	 * @param string $objectId Mongo ID
	 *
	 * @return void
	 */
	public function removeById($objectId);

	/**
	 * Удаление документов по условию
	 *
	 * @param array $condition Условие выборки
	 *
	 * @return void
	 */
	public function remove($condition);

	/**
	 * Удаление всех документов в коллекции
	 *
	 * @return void
	 */
	public function removeAll();

	/**
	 * Сохрание сущности
	 *
	 * @param MongoEntity $object Сущность
	 *
	 * @return MongoEntity
	 */
	public function save(MongoEntity $object);
}
