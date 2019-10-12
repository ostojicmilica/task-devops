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

}