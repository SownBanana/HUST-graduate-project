<?php
namespace App\Repositories\Traits;

trait Paginatable
{
    abstract protected function _getModel();

    public function paginate($perPage = null, $columns = array('*'))
    {
        return $this->_getModel()->paginate($perPage, $columns);
    }

    //for test
    public function testModelFromTraits()
    {
        return $this->_getModel();
    }
}
