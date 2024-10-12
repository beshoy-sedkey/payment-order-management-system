<?php
namespace App\Services\Repository;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseContract
{
    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @param array $relationsCount
     * @param null $searchText
     * @return Collection
     */
    public function list(array $columns = ['*'], array $relations = [], array $relationsCount = [], string $searchText = null): Collection;

    /**
     * Get all trashed models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     *
     */
    public function listTrashed(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get all models with trashed.
     * @param array $columns
     * @param array $relations
     * @return Collection
     *
     */
    public function listWithTrashed(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get paginate.
     *
     * @param array $columns
     * @param array $relations
     * @param int $count
     * @return Collection
     */
    public function paginate(array $columns = ['*'], array $relations = [], int $count = 20): LengthAwarePaginator;

    /**
     * Get paginate.
     *
     * @param array $columns
     * @param array $relations
     * @param int $count
     * @return Collection
     */
    public function paginateTrashed(array $columns = ['*'], array $relations = [], int $count = 20): LengthAwarePaginator;

  /**
     * Get paginate with trashed.
     *
     * @param array $columns
     * @param array $relations
     * @param int $count
     * @return Collection
     */
    public function paginateWithTrashed(array $columns = ['*'], array $relations = [], int $count = 20): LengthAwarePaginator;

    /**
     * Get more than one model by id.
     *
     * @param array $arrayIds
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Collection
     */
    public function getByIds(
        array $arrayIds,
        array $columns = ['*'],
        array $relations = [],
    ): ?Collection;

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
    ): ?Collection;

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
    ): ?Collection;

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findTrashedById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findWithTrashedById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;


    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */

    public function insert(array $payload): ?bool;

    /**
     * Update existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool;

    /**
     * Update Trashed existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function updateTrashed(int $modelId, array $payload): bool;

    /**
     * Delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool;

    /**
     * Restore model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool;

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool;
}
