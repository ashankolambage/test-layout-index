<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ConcessionRepositoryInterface;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConcessionController extends Controller
{
    protected $concessionRepository;

    public function __construct(ConcessionRepositoryInterface $concessionRepository)
    {
        $this->concessionRepository = $concessionRepository;
    }

    public function index()
    {
        $concessions = $this->concessionRepository->getAll();
        return Inertia::render('Concessions/Index', [ 'concessions' => $concessions, ]);
    }

    public function create()
    {
        return Inertia::render('Concessions/Create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed!',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads/images/concessions');
            }
    
            $this->concessionRepository->create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'price' => $request->price,
            ]);
    
            return response()->json(['message' => 'Concession created successfully!'], 200);
        } catch (\Throwable $th) {
            Log::error('Error creating concession: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while creating the concession.'], 500);
        }
    }

    public function edit($id)
    {
        $concession = $this->concessionRepository->findById($id);

        return Inertia::render('Concessions/Edit', [
            'concession' => $concession,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'new_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed!',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $concession = $this->concessionRepository->findById($id);

            if ($request->hasFile('new_image')) {
                Storage::disk()->delete($concession->image);
                $imagePath = $request->file('new_image')->store('uploads/images/concessions');
            } else {
                $imagePath = $concession->image;
            }

            $this->concessionRepository->update($id, [
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'price' => $request->price,
            ]);

            return response()->json(['message' => 'Concession updated successfully!'], 200);
        } catch (\Throwable $th) {
            Log::error('Error updating concession: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while updating the concession.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->concessionRepository->delete($id);
            return response()->json(['message' => 'Concession deleted successfully!'], 200);
        } catch (\Throwable $th) {
            Log::error('Error deleting concession: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the concession.'], 500);
        }
    }
}
