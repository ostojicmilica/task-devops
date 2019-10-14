<?php

namespace App\Repositories\Eloquent;

use App\Models\ExchangeRate;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class CountryExchangeRepository
{

    protected $entity;

    public function __construct(ExchangeRate $entity)
    {
        $this->entity = $entity;
    }

    public function all()
    {
        return $this->entity::all();
    }

    /**
     * @param array $properties
     * @return mixed
     */
    public function create(array $properties)
    {
        return $this->entity->create($properties);
    }

    /**
     * @param $id
     * @param array $properties
     * @return mixed
     */
    public function update($id, array $properties)
    {
        return $this->find($id)->update($properties);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $model = $this->entity->find($id);
        if (!$model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel()),
                $id
            );
        }
        return $model;
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhere($column, $value, $paginate = null)
    {
        $query = $this->entity->where($column, $value);
        return $this->processPagination($query, $paginate);
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhereFirst($column, $value)
    {
        $model = $this->entity->where($column, $value);
        if (!$model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }
        return $model->first();
    }

}