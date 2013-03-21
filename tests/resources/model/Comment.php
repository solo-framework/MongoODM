<?php
/**
 * Сущность комментарий
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

use Solo\Lib\Mongo\Entity;

class Comment extends Entity
{
	/**
	 * Текс
	 *
	 * @var string
	 */
	public $text = null;

	public function __construct($text = null)
	{
		$this->text = $text;
	}
}
