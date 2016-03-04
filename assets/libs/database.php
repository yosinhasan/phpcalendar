<?php
/**
 * @author Yosin Hasan
 * User: developer
 * Date: 05.09.15
 * Time: 13:13
 * @version: 0.0.1
 */
class Database extends DatabasePDO 
{
	public static $DB = null;
	public static function getDB()
	{
		if (self::$DB != null) {
			return self::$DB;
		} else {
			self::$DB = new database(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME);
		}
		return self::$DB;
	}
}
