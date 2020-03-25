<?php


namespace DLee\Platform\Core\Repositories\Abstracts;

use DLee\Platform\Core\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class RepositoryAbstract implements RepositoryInterface
{
    /**
     * @var \Eloquent | Model
     * */
    protected $model;

    /**
     * @var \Eloquent | Model
     * */
    protected $originModel;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->originModel = $model;
    }

    /**
     * @return \Eloquent|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \Eloquent|Model $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    public function getTable()
    {
        $this->model->getTable();
    }

    protected function resetModel()
    {
        $this->model = $this->originModel;
    }

    /**
     * @param $result
     * @return mixed
     */
    protected function applyAfterExecuteQuery($result)
    {
        $this->resetModel();
        return $result;
    }


    /**
     * @param array $orderBy
     * @return string
     */
    protected function resolveOrderBy($orderBy = [])
    {
        return collect($orderBy)->mapWithKeys(function ($value, $key) {
            return "{$key} {$value}";
        })->join(", ");
    }

    /**
     * @param array $relations
     * @return RepositoryAbstract
     */
    protected function loadRelations(array $relations = [])
    {
        if (!empty($relations))
            $this->model = $this->model->with($relations);
        return $this;
    }

    protected function applyConditions(array $conditions = [])
    {
        if (!empty($conditions))
            $this->model = $this->model->where($conditions);
        return $this;
    }

    /**
     * @param $orderBy
     * @return RepositoryAbstract
     */
    protected function applyOrders($orderBy)
    {
        if (!empty($orderBy))
            $this->model = $this->model->orderByRaw($this->resolveOrderBy($orderBy));
        return $this;
    }

    /**
     * @param array $conditions
     * @param array $relations
     * @param array $select
     * @return mixed
     */
    public function first(array $conditions = [], $relations = [], array $select = ['*'])
    {
        $this->loadRelations($relations)
            ->applyConditions($conditions);
        return $this->applyAfterExecuteQuery($this->model->select($select)->first());
    }

    /**
     * @param $id
     * @param array $relations
     * @param array $select
     * @return mixed
     */
    public function find($id, array $relations = [], array $select = ['*'])
    {
        $this->loadRelations($relations);
        return $this->applyAfterExecuteQuery($this->model->find($id, $select));
    }

    /**
     * @param $id
     * @param array $select
     * @param array $relations
     * @return mixed
     */
    public function findOrFail($id, array $relations = [], array $select = ['*'])
    {
        try {
            $this->loadRelations($relations);
            return $this->applyAfterExecuteQuery($this->model->findOrFail($id, $select));
        } catch (ModelNotFoundException $e) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model), $id);
        }
    }

    /**
     * @param array $conditions
     * @param array $relations
     * @param array $select
     * @param array $orderBy
     * @return mixed
     */
    public function all($conditions = [], $relations = [], $select = ['*'], $orderBy = [])
    {
        $this->loadRelations($relations)
            ->applyConditions($conditions)
            ->applyOrders($orderBy);
        return $this->applyAfterExecuteQuery($this->model->get($select));

    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes)
    {
        $class = get_class($this->model);
        return $class::create($attributes);
    }


    /**
     * @param $id
     * @param $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        try {
            return $this->findOrFail($id)
                ->update($attributes);
        } catch (ModelNotFoundException $e) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model), $id);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        try {
            return $this->findOrFail($id)
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model), $id);
        }
    }
}
