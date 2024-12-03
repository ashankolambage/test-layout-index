<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConcessionRequest;
use App\Http\Requests\UpdateConcessionRequest;
use App\Services\ConcessionService;
use Inertia\Inertia;

class ConcessionController extends Controller
{
    protected ConcessionService $concessionService;

    public function __construct(ConcessionService $concessionService)
    {
        $this->concessionService = $concessionService;
    }

    public function index()
    {
        $concessions = $this->concessionService->getAllConcessions();
        return Inertia::render('Concessions/Index', [ 'concessions' => $concessions, ]);
    }

    public function show($id)
    {
        $concession = $this->concessionService->getConcessionById($id);

        return Inertia::render('Concessions/Show', [
            'concession' => $concession,
        ]);
    }

    public function create()
    {
        return Inertia::render('Concessions/Create');
    }

    public function store(ConcessionRequest $request)
    {
        return $this->concessionService->storeConcession($request);
    }

    public function edit($id)
    {
        $concession = $this->concessionService->getConcessionById($id);

        return Inertia::render('Concessions/Edit', [
            'concession' => $concession,
        ]);
    }

    public function update(UpdateConcessionRequest $request, $id)
    {
        return $this->concessionService->updateConcession($request, $id);
    }

    public function destroy($id)
    {
        return $this->concessionService->deleteConcession($id);
    }
}
