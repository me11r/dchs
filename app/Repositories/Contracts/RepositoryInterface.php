<?php

namespace App\Repositories\Contracts;


interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']);

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $columns = ['*']);

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param array $relations
     * @return mixed
     */
    public function with(array $relations = []);

    /**
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']);

    /**
     * @param string $attribute
     * @param string $conclusion
     * @param $value
     * @return mixed
     */
    public function where(string $attribute, string $conclusion, $value);

    /**
     * @param string $attribute
     * @param array $values
     * @return mixed
     */
    public function whereBetween(string $attribute, array $values);

    /**
     * @param string $field
     * @param string $type
     * @return mixed
     */
    public function orderBy(string $field, string $type = 'ASC');
}