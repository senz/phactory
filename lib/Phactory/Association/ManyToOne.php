<?

class Phactory_Association_ManyToOne {
    protected $_to_table;
    protected $_from_column;
    protected $_to_column;

    public function __construct($to_table, $from_column, $to_column = null) {
        $this->setToColumn($to_column);
        $this->setFromColumn($from_column);
        $this->setTable($to_table);
    }

    public function getTable() {
        return $this->_to_table;
    }

    public function setTable($table) {
        $this->_to_table = $table;
        $this->_guessToColumn();
    }

    public function getFromColumn() {
        return $this->_from_column;
    }

    public function setFromColumn($column) {
        $this->_from_column = $column;
    }

    public function getToColumn() {
        return $this->_to_column;
    }

    public function setToColumn($column) {
        $this->_to_column = $column;
    }

    protected function _guessToColumn() {
        if(null === $this->_to_column) {
            $db_util = Phactory_DbUtilFactory::getDbUtil();
            $this->_to_column = $db_util->getPrimaryKey($this->_to_table);
            if(!$this->_to_column) {
                throw new Exception("Unable to determine primary key for table '{$this->_to_table}' and none specified");
            }
        }
    }
}
