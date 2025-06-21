<?php

namespace LoafPanel\Http\Controllers\Api\Application\Nodes;

use LoafPanel\Models\Node;
use Illuminate\Http\JsonResponse;
use LoafPanel\Models\Allocation;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use LoafPanel\Services\Allocations\AssignmentService;
use LoafPanel\Services\Allocations\AllocationDeletionService;
use LoafPanel\Transformers\Api\Application\AllocationTransformer;
use LoafPanel\Http\Controllers\Api\Application\ApplicationApiController;
use LoafPanel\Http\Requests\Api\Application\Allocations\GetAllocationsRequest;
use LoafPanel\Http\Requests\Api\Application\Allocations\StoreAllocationRequest;
use LoafPanel\Http\Requests\Api\Application\Allocations\DeleteAllocationRequest;

class AllocationController extends ApplicationApiController
{
    /**
     * AllocationController constructor.
     */
    public function __construct(
        private AssignmentService $assignmentService,
        private AllocationDeletionService $deletionService,
    ) {
        parent::__construct();
    }

    /**
     * Return all the allocations that exist for a given node.
     */
    public function index(GetAllocationsRequest $request, Node $node): array
    {
        $allocations = QueryBuilder::for($node->allocations())
            ->allowedFilters([
                AllowedFilter::exact('ip'),
                AllowedFilter::exact('port'),
                'ip_alias',
                AllowedFilter::callback('server_id', function (Builder $builder, $value) {
                    if (empty($value) || is_bool($value) || !ctype_digit((string) $value)) {
                        return $builder->whereNull('server_id');
                    }

                    return $builder->where('server_id', $value);
                }),
            ])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($allocations)
            ->transformWith($this->getTransformer(AllocationTransformer::class))
            ->toArray();
    }

    /**
     * Store new allocations for a given node.
     *
     * @throws \LoafPanel\Exceptions\DisplayException
     * @throws \LoafPanel\Exceptions\Service\Allocation\CidrOutOfRangeException
     * @throws \LoafPanel\Exceptions\Service\Allocation\InvalidPortMappingException
     * @throws \LoafPanel\Exceptions\Service\Allocation\PortOutOfRangeException
     * @throws \LoafPanel\Exceptions\Service\Allocation\TooManyPortsInRangeException
     */
    public function store(StoreAllocationRequest $request, Node $node): JsonResponse
    {
        $this->assignmentService->handle($node, $request->validated());

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete a specific allocation from the Panel.
     *
     * @throws \LoafPanel\Exceptions\Service\Allocation\ServerUsingAllocationException
     */
    public function delete(DeleteAllocationRequest $request, Node $node, Allocation $allocation): JsonResponse
    {
        $this->deletionService->handle($allocation);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
