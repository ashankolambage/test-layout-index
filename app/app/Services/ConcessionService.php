<?php

namespace App\Services;

use App\Repositories\Interfaces\ConcessionRepositoryInterface;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConcessionService
{
    protected ConcessionRepositoryInterface $concessionRepository;
    use ResponseHelper;

    public function __construct(ConcessionRepositoryInterface $concessionRepository)
    {
        $this->concessionRepository = $concessionRepository;
    }

    public function getAllConcessions()
    {
        return $this->concessionRepository->getAll();
    }

    public function getConcessionById($id)
    {
        try {
            $concession = $this->concessionRepository->findById($id);
            return $concession;
        } catch (\Throwable $th) {
            Log::error('Concession not found: ' . $th->getMessage());
            return $this->response('error', 'Concession not found!', [], 500);
        }
    }

    public function storeConcession($request)
    {
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads/images/concessions');
            }

            $this->concessionRepository->create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'price' => $request->price,
            ]);
    
            return $this->response('success', 'Concession created successfully!', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error creating concession: ' . $th->getMessage());
            return $this->response('error', 'An error occurred while creating the concession.', $th->getMessage(), 500);
        }
    }

    public function updateConcession($request, $id)
    {
        try {
            $concession = $this->concessionRepository->findById($id);

            if (!$concession) {
                return $this->response('error', 'Concession not found.', [], 404);
            }

            if ($request->hasFile('image')) {
                Storage::disk()->delete($concession->image);
                $imagePath = $request->file('image')->store('uploads/images/concessions');
            } else {
                $imagePath = $concession->image;
            }

            $this->concessionRepository->update($id, [
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'price' => $request->price,
            ]);

            return $this->response('success', 'Concession updated successfully!', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error updating concession: ' . $th->getMessage());
            return $this->response('error', 'An error occurred while updating the concession.', $th->getMessage(), 500);
        }
    }

    public function deleteConcession($id)
    {
        try {
            $this->concessionRepository->delete($id);
            return $this->response('success', 'Concession deleted successfully!', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error deleting concession: ' . $th->getMessage());
            return $this->response('error', 'An error occurred while deleting the concession.', $th->getMessage(), 500);
        }
    }
}
