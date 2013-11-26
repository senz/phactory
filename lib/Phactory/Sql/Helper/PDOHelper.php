<?php
/**
 * @author Konstantin G Romanov
 */

namespace Phactory\Sql\Helper;


use Phactory\Logger;

class PDOHelper {
    public static function checkStatementResult($r, \PDOStatement $stmt, $sql)
    {
        if($r === false){
            $error= $stmt->errorInfo();
            Logger::error('SQL statement failed: '.$sql.' ERROR MESSAGE: '.$error[2].' ERROR CODE: '.$error[1]);
            throw new \Exception('Statement error: ' . print_r($stmt->errorInfo(), true));
        }
    }
}
