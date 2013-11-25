<?php

namespace Phactory\Sql\DbUtil;

use Phactory\Sql\Phactory;

class PgsqlUtil extends AbstractDbUtil
{
    protected $_quoteChar = '"';

	public function getPrimaryKey($table) {
        $query = "
            SELECT
                pg_attribute.attname,
                format_type(pg_attribute.atttypid, pg_attribute.atttypmod)
            FROM pg_index, pg_class, pg_attribute
            WHERE
                pg_class.oid = '$table'::regclass AND
                indrelid = pg_class.oid AND
                pg_attribute.attrelid = pg_class.oid AND
                pg_attribute.attnum = any(pg_index.indkey)
                AND indisprimary
        ";
        $stmt = $this->_pdo->query($query);
        $result = $stmt->fetch();
        return $result['attname'];
	}

    public function getColumns($table) {
        $query = "
            SELECT column_name
            FROM information_schema.columns
            WHERE table_name = '$table'
        ";
        $stmt = $this->_pdo->query($query);
        $columns = array();
        while($row = $stmt->fetch()) {
            $columns[] = $row['column_name'];
        }
        return $columns;
    }

    /**
     * Creates literal for pg array type. I.e.: {'a', 'b', 'c'} or {1,2,3}
     * @param array $arr
     * @return string
     */
    public static function createArrayLiteral(array $arr)
    {
        $out = '{';
        foreach ($arr as $el) {
            if (is_numeric($el)) {
                $out .= $el;
            } elseif (is_array($el)) {
                $out .= self::createArrayLiteral($el);
            } else {
                $out .= "'{$el}'";
            }
            $out .= ',';
        }
        $out = rtrim($out, ',');
        $out .= '}';
        return $out;
    }
}
