<?php

namespace App\Http\Controllers\API\Eps;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\Eps\ChangeStateEpsRequest;
use App\Http\Requests\Eps\PartialUpdateEpsRequest;
use App\Http\Requests\Eps\StoreEpsRequest;
use App\Http\Requests\Eps\UpdateEpsRequest;
use App\Services\Eps\EpsService;
use App\Models\Eps\Eps;
use Illuminate\Http\Request;

class EpsController extends Controller
{
    protected $service;

    public function __construct(EpsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $response = $this->service->getAll();
        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function show($id)
    {
        $response = $this->service->getById($id);
        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function store(StoreEpsRequest $request)
    {
        $data = $request->validated();

        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

        public function update(UpdateEpsRequest $request, string $id)
    {
        $eps = Eps::find($id);
        if (!$eps) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function partialUpdate(PartialUpdateEpsRequest $request, string $id)
    {
        $eps = Eps::find($id);
        if (!$eps) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function changeStatus(ChangeStateEpsRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    public function destroy(string $id)
    {
        $eps = Eps::find($id);
        if (!$eps) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function history(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->history($id, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }


}
