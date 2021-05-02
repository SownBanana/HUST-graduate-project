<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    //model muốn tương tác
    protected $model;

    //khởi tạo
    public function __construct()
    {
        $this->setModel();
    }

    //lấy model tương ứng
    abstract public function getModel();

    protected function _getModel()
    {
        return $this->model;
    }
    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function with($relations)
    {
        $result = $this->model->with($relations);

        return $result;
    }
    public function where($params)
    {
        $result = $this->model->where($params);

        return $result;
    }
    public function whereIn($field, $params)
    {
        $result = $this->model->whereIn($field, $params);

        return $result;
    }
    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }
    public function findOrFail($id)
    {
        $result = $this->model->findOrFail($id);

        return $result;
    }

    public function firstOrCreate($attributes = [])
    {
        return $this->model->firstOrCreate($attributes);
    }
    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }
    public function createMany($attributes = [])
    {
        return $this->model->createMany($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }
}
