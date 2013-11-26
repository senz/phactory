<?php
/**
 * @author Konstantin G Romanov
 */

namespace Phactory\Sql\Helper;

use Phactory\Sql\Phactory;

class PDOHelper {
    public static function checkStatementResult($r, \PDOStatement $stmt, $sql, Phactory $phactory)
    {
        if($r === false){
            $error = $stmt->errorInfo();
            $phactory->getLogger()->error(
                'SQL statement failed: {sql} ERROR MESSAGE: {msg} ERROR CODE: {code}',
                array('sql' => $sql, 'msg' => $error[2], 'code' => $error[1])
            );
            throw new \Exception('Statement error: ' . print_r($error, true));
        }
    }
}
