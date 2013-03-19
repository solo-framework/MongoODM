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

abstract class MongoEntity
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
	 * Возвращает метаинформацию для полей
	 *
	 * @return array
	 */
	public static function getEntityRelations()
	{
		return array();
	}

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

