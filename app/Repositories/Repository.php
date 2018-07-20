<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    /** @var Model */
    protected $model;


    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @return mixed
     */
    abstract function model();

    /**
     * @return mixed
     */
    public function makeModel()
    {
        $model = app()->make($this->model());
        return $this->model = new $model;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        return $this->model->where('id', '=', $id)->first()->update($data);
    }

    /**
     * @param int $id
     * @return int|mixed
     */
    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Builder|mixed|static
     */
    public function with(array $relations = [])
    {
        return $this->model->with($relations);
    }

    /**
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, array $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @param string $attribute
     * @param string $conclusion
     * @param $value
     * @return mixed
     */
    public function where(string $attribute, string $conclusion, $value)
    {
        return $this->model->where($attribute, $conclusion, $value);
    }

    /**
     * @param string $attribute
     * @param array $values
     * @return mixed
     */
    public function whereBetween(string $attribute, array $values)
    {
        return $this->model->whereBetween($attribute, $values);
    }

    /**
     * @param string $field
     * @param string $type
     * @return mixed
     */
    public function orderBy(string $field, string $type = 'ASC')
    {
        return $this->model->orderBy($field, $type);
    }

}