<?php
/**
 * Базовый классс сущности Mongo
 *
 * PHP version 5
 *
 * @package MongoODM
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace Solo\Lib\Mongo;

abstract class Entity
{
	/**
	 * Идентификатор сущности
	 *
	 * @var \MongoId
	 */
	public $id = null;

	/**
	 * Тип массив сущностей
	 */
	const TYPE_ARRAY_ENTITIES = "TYPE_ARRAY_ENTITIES";

	/**
	 * Тип сущность
	 */
	const TYPE_ENTITY = "TYPE_ENTITY";

	/**
	 * Возвращает метаинформацию по связям полей с другими объектами модели
	 *
	 * @return array
	 */
	public static function getEntityRelations()
	{
		return array();
	}

	/**
	 * Возвращает имя коллекции, где хранятся сущности этого типа
	 *
	 * @return string
	 */
	public abstract function getCollectionName();

	/**
	 * Возвращает названия полей, исключенных по умолчанию
	 *
	 * @return array
	 */
	public static function getFieldsMeta()
	{
		return array();
	}
}

