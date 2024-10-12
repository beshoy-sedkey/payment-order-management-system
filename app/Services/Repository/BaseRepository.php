<?php
namespace App\Services\Repository;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements BaseContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @param array $relationsCount
     * @param string $searchText
     * @return Collection
     */
    public function list(array $columns = ['*'], array $relations = [], array $relationsCount = [], string $searchText = null): Collection
    {
        $list =  $this->model
            ->with($relations)
            ->withCount($relationsCount)
            ->orderByDesc('id');

        if ($searchText !== null){
            $list->search($searchText);
        }

        return $list->get($columns);
    }

    /**
     * Get all trashed models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function listTrashed(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->onlyTrashed()->with($relations)->orderByDesc('id')->get($columns);
    }

    /**
     * Get all models with trashed.
     * @param array $columns
     * @param array $relations
     * @return Collection
     *
     */
    public function listWithTrashed(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->withTrashed()->with($relations)->orderByDesc('id')->get($columns);
    }

    /**
     * Get paginate.
     *
     * @param array $columns
     * @param array $relations
     * @param int $count
     * @return LengthAwarePaginator
     */
    public function paginate(array $columns = ['*'], array $relations = [], int $count = 20): LengthAwarePaginator
    {
        return $this->model->with($relations)->orderByDesc('id')->select($columns)->paginate($count);
    }

    /**
     * Get paginate Trashed.
     *
     * @param array $columns
     * @param array $relations
     * @param int $count
     * @return LengthAwarePaginator
     */
    public function paginateTrashed(array $columns = ['*'], array $relations = [], int $count = 20): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with($relations)->orderByDesc('id')->select($columns)->paginate($count);
    }

    /**
     * Get paginate With Trashed.
     *
     * @param array $columns
     * @param array $relations
     * @param int $count
     * @return LengthAwarePaginator
     */
    public function paginateWithTrashed(array $columns = ['*'], array $relations = [], $count = 20): LengthAwarePaginator
    {
        return $this->model->withTrashed()->with($relations)->orderByDesc('id')->select($columns)->paginate($count);
    }

    /**
     * Get more than one model by id.
     *
     * @param array $arrayIds
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getByIds(
        array $arrayIds,
        array $columns = ['*'],
        array $relations = [],
    ): ?Collection {
        return $this->model->select($columns)->with($relations)->findOrFail($arrayIds);
    }

    /**
     * Get more than one model trashed by id.
     *
     * @param array $arrayIds
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getTrashedByIds(
        array $arrayIds,
        array $columns = ['*'],
        array $relations = [],
    ): ?Collection {
        return $this->model->onlyTrashed()->select($columns)->with($relations)->findOrFail($arrayIds);
    }

    /**
     * Get more than one model With trashed by id.
     *
     * @param array $arrayIds
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getWithTrashedByIds(
        array $arrayIds,
        array $columns = ['*'],
        array $relations = [],
    ): ?Collection {
        return $this->model->withTrashed()->select($columns)->with($relations)->findOrFail($arrayIds);
    }

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $with
     * @param array $withCount
     * @return Model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        return $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }

    /**
     * Find model trashed by id.
     *
     * @param int $modelId
     * @param array $with
     * @param array $withCount
     * @return Model
     */
    public function findTrashedById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        return $this->model->onlyTrashed()->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }

    /**
     * Find model with trashed by id.
     *
     * @param int $modelId
     * @param array $with
     * @param array $withCount
     * @return Model
     */
    public function findWithTrashedById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        return $this->model->withTrashed()->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }


    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        $payload['slug'] = $this->model->generatorSlug();
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    /**
     * insert a models.
     *
     * @param array $payload
     * @return bool
     */
    public function insert(array $payload): ?bool
    {
      return  $this->model->insert($payload);
    }

    /**
     * Update existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        unset($payload['id']);
        return $model->update($payload);
    }

    /**
     * Update Trashed existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function updateTrashed(int $modelId, array $payload): bool
    {
        $model = $this->findTrashedById($modelId);

        return $model->update($payload);
    }

    /**
     * Delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * Restore model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }
}
