<?php

/**
 * Description of Mapper
 *
 * @author fbarrocal
 */
class Tyuiopas_Base_Mapper {

    private static $_instances = array();
    protected $_dbTable = null;
    protected $_model = null;

    protected function __construct() {
        //parent::__construct();
    }

    public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }

    public function find($id) {
        $dbTable = new $this->_dbTable();
        $resultSet = $dbTable->find($id);
        if (count($resultSet) != 0) {
            $row = $resultSet->current();
            $model = new $this->_model($row->toArray());
            return $model;
        } else {
            return null;
        }
    }

    public function fetchAll($criteria = null) {
        $dbTable = new $this->_dbTable();
        $resultSet = $dbTable->fetchAll($criteria);

        $model_array = array();
        foreach ($resultSet as $row) {
            $result = new $this->_model($row->toArray());
            $model_array[] = $result;
        }
        return $model_array;
    }

    public function fetchFirst($criteria = null) {
        $dbTable = new $this->_dbTable();
        $resultSet = $dbTable->fetchAll($criteria);

        if (count($resultSet) != 0) {
            $row = $resultSet->current();
            $model = new $this->_model($row->toArray());
            return $model;
        } else {
            return null;
        }
    }

    public function save(Tyuiopas_Base_Model $_model) {
        $modelProperties = $_model->toArray();
        (isset($_model->id)) ? $id = $_model->id : $id = null;

        $dbTable = new $this->_dbTable();
        if ($id === null) {
            unset($modelProperties['id']);
            $modelProperties['created'] = date('Y-m-d H:i:s');
            $id = $dbTable->insert($modelProperties);
        } else {
            $dbTable->update($modelProperties, array('id = ?' => $_model->id));
            $modelProperties['modified'] = date('Y-m-d H:i:s');
            $id = $_model->id;
            $dbTable->update($modelProperties, 'id = ' . $id);
        }
        return $id;
    }

    public function delete($id) {
        $dbTable = new $this->_dbTable();
        $data = $dbTable->find($id);
        if (count($data) != 0) {
            $row = $data->current();
            $row->delete();
            return true;
        } else {
            return false;
        }
    }

}

?>
