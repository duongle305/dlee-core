<?php


namespace DLee\Platform\Core\Repositories\Interfaces;


interface RepositoryInterface
{
    /**
     * @param array $conditions
     * @param array $relations
     * @param array $select
     * @return mixed
     */
    public function first(array $conditions = [], array $relations = [], array $select = ['*']);

    /**
     * @param $id
     * @param array $relations
     * @param array $select
     * @return mixed
     */
    public function find($id, array $relations = [], array $select = ['*']);

    /**
     * @param $id
     * @param array $relations
     * @param array $select
     * @return mixed
     */
    public function findOrFail($id, array $relations = [], array $select = ['*']);

    /**
     * @param array $conditions
     * @param array $relations
     * @param array $select
     * @param array $orderBy
     * @return mixed
     */
    public function all(array $conditions, array $relations = [], $select = ['*'], $orderBy = []);


    /**
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes);


    /**
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
