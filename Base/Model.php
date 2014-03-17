<?php

/**
 * Description of Base_Model
 *
 * @author fbarrocal
 */
class Tyuiopas_Base_Model {

    protected $_data = array();

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __get($property) {
        if (strpos($property, '_') !== false) {
            $parts = explode('_', strtolower($property));
            $camel_property = '';
            foreach ($parts as $part) {
                $camel_property .= ucfirst(trim($part));
            }
        } else {
            $camel_property = ucfirst($property);
        }

        $getter = 'get' . $camel_property;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else if (isset($this->_data[$property])) {
            return $this->_data[$property];
        } else {
            return null;
        }
    }

    public function __set($property, $value) {
        if (strpos($property, '_') !== false) {
            $parts = explode('_', strtolower($property));
            $camel_property = '';
            foreach ($parts as $part) {
                $camel_property .= ucfirst(trim($part));
            }
        } else {
            $camel_property = ucfirst($property);
        }
        $setter = 'set' . $camel_property;
        if (method_exists($this, $setter)) {
            $this->$setter();
        } else {
            $this->_data[$property] = $value;
        }
        return $this; // cascading set operations
    }

    public function __isset($property) {
        $value = $this->$property;
        return isset($value);
    }

    public function __unset($property) {
        unset($this->_data[$property]);
    }

    public function setOptions(array $options) {
        foreach ($options as $key => $value) {
            $this->_data[$key] = $value;
        }
        return $this;
    }

    public function getAttrib($_attrib) {
        if (isset($this->attribs)) {
            $attrib_array = explode(',', $this->attribs);
            if (isset($attrib_array[$_attrib])) {
                return $attrib_array[$_attrib];
            }
        }
        return null;
    }

    public function getAttribs() {
        if (isset($this->attribs)) {
            return explode(',', $this->attribs);
        } else {
            return null;
        }
    }

    public function toArray() {
        return $this->_data;
    }

}

?>
