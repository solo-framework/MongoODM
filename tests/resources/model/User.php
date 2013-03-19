<?php
/**
 * Сущность автор статьи
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

use Solo\Lib\Mongo\MongoEntity;

class User extends MongoEntity
{
	/**
	 * Идентификатор
	 *
	 * @var string
	 */
	public $id = null;

	/**
	 * Имя
	 *
	 * @var string
	 */
	public $name = null;

	/**
	 * Возраст
	 *
	 * @var int
	 */
	public $age = null;

	/**
	 * Время создания
	 *
	 * @var \DateTime
	 */
	public $createAt = null;

}
