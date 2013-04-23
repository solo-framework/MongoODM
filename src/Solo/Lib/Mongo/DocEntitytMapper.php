<?php
/**
 * Маппинг документов на сущности
 *
 * PHP version 5
 *
 * @package MongoODM
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace Solo\Lib\Mongo;

class DocEntitytMapper
{
	/**
	 * Mongo документ
	 *
	 * @var array|null
	 */
	private $doc = null;

	/**
	 * Имя класса, куда мапить документ
	 *
	 * @var null
	 */
	private $classname = null;

	/**
	 * Конструктор
	 *
	 * @param array $doc Документ
	 * @param string $classname Имя класса
	 */
	public function __construct(array $doc, $classname)
	{
		$this->doc = $doc;
		$this->classname = $classname;
	}

	/**
	 * Выполнить мапинг
	 *
	 * @return Entity
	 */
	public function mapByFieldsMeta()
	{
		$id = $this->doc["_id"];
		unset($this->doc["_id"]);

		$obj = self::arrayToObjectRecurively($this->doc, array("type" => Entity::TYPE_ENTITY, "class" => $this->classname));
		$obj->id = $id;

		return $obj;
	}

	/**
	 * Рекурсивно преобразовыет массив в сущность
	 *
	 * @param mixed $data Данные
	 * @param array $options Опции
	 *
	 * @return array|null
	 */
	private static function arrayToObjectRecurively($data, $options)
	{
		if ($options["type"] == Entity::TYPE_ENTITY)
		{
			$object = new $options["class"];
			$fieldMeta = call_user_func(array($options["class"], "getEntityRelations"));

			foreach ($data as $name => $value)
			{
				if (property_exists($object, $name))
				{
					if (is_array($value) && isset($fieldMeta[$name]))
					{
						$object->$name = self::arrayToObjectRecurively($value, $fieldMeta[$name]);
					}
					else
					{
						$object->$name = $value;
					}
				}
			}
			return $object;
		}
		else if ($options["type"] == Entity::TYPE_ARRAY_ENTITIES)
		{
			$list = array();
			foreach ($data as $key => $value)
			{
				$list[$key] = self::arrayToObjectRecurively(
					$value,
					array("type" => Entity::TYPE_ENTITY, "class" => $options["class"])
				);
			}
			return $list;
		}

		return null;
	}
}
