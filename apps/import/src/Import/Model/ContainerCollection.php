<?php

namespace Import\Model;

class ContainerCollection implements \ArrayAccess, \Iterator, \Countable
{
    private $_data = array();
    private $_strict;
    protected $_pointer = 0;
    const SEPARATOR = ':';
    protected $_allowedTypes = array(
        'int',
        'integer',
        'float',
        'string',
        'bool',
        'boolean',
        'null',
        'array',
        'object',
    );
    public function __construct(array $data = array(), $strict = true)
    {
        $this->_strict = (bool)$strict;
        foreach ($data as $key => $value)
        {
            $this->offsetSet($key, $value);
        }
    }
// ArrayAccess
    public function offsetSet($offset, $value)
    {
        if ($this->_strict)
        {
            $this->_validate($offset, $value);
        } else
        {
// zmienna $value jest przekazywana przez referencję
            $this->_filter($offset, $value);
        }
        $this->_data[$offset] = $value;
    }
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset))
        {
            return $this->_data[$offset];
        } else
        {
            trigger_error("$offset offset doesn't exist!");
        }
    }
    public function offsetExists($offset)
    {
        return (bool)isset($this->_data[$offset]);
    }
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }
// Iterator
    public function current()
    {
        return $this->_data[$this->key()];
    }
    public function key()
    {
        $keys = array_keys($this->_data);
        return $keys[$this->_pointer];
    }
    public function rewind()
    {
        $this->_pointer = 0;
    }
    public function valid()
    {
        return (bool)($this->count() > $this->_pointer);
    }
    public function next()
    {
        ++$this->_pointer;
    }
// Countable
    public function count()
    {
        return count($this->_data);
    }
// validate data
    protected function _validate(&$offset, &$value)
    {
        $elements = explode(self::SEPARATOR, $offset);
        if (count($elements) != 2)
        {
            throw new \Exception('Wartość $offset musi zawierać dokładnie jeden separator!');
        }
        $type = $elements[0];
        $offset = $elements[1];
        if (!in_array($offset, $this->_allowedTypes))
        {
            throw new \Exception("Typ $offset jest nieznany!");
        }
        $orgType = strtolower(gettype($value));
        if ($type == 'int')
            $type = 'integer';
        if ($type == 'bool')
            $type = 'boolean';
        if ($orgType !== $type)
        {
            throw new \Exception("Nieprawidłowy typ zmiennej! Zadeklarowano $type, a dostarczono $orgType!");
        }
    }
    protected function _filter(&$offset, &$value)
    {
        $elements = explode(self::SEPARATOR, $offset);
        if (count($elements) != 2)
        {
            throw new \Exception('Wartość $offset musi zawierać dokładnie jeden separator!');
        }
        $type = $elements[0];
        $offset = $elements[1];
        if (!in_array($offset, $this->_allowedTypes))
        {
            throw new \Exception("Typ $offset jest nieznany!");
        }
        switch ($type)
        {
            case 'int':
            case 'integer':
                settype($value, 'integer');
                break;
            case 'float':
                settype($value, 'float');
                break;
            case 'string':
                settype($value, 'string');
                break;
            case 'bool':
            case 'boolean':
                settype($value, 'boolean');
                break;
            case 'null':
                $value = null;
                break;
            case 'array':
                settype($value, 'array');
                break;
            case 'object':
                settype($value, 'object');
                break;
            default:
                throw new \Exception('Nieznany typ zmiennej!');
        }
    }
}