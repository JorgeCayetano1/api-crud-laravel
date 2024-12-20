<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResourse;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }


    public function index()
    {
        return $this->productRepositoryInterface->index();
    }

    public function create() {
        //
    }

    public function store(Request $request)
    {
        $description = [
            'name' => $request->name,
            'description' => $request->description
        ];

        DB::beginTransaction();
        try {
            $product = $this->productRepositoryInterface->store($description);

            DB::commit();
            return ApiResponseClass::sendResponse(new ProductResourse($product), 'Product created successfully', 201);
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e);
        }
    }

    public function show($id)
    {
        $product = $this->productRepositoryInterface->getById($id);
        if ($product) {
            return ApiResponseClass::sendResponse(new ProductResourse($product), 'Product retrieved successfully');
        }
        return ApiResponseClass::sendResponse(null, 'Product not found', 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $description = [
            'name' => $request->name,
            'description' => $request->description
        ];

        DB::beginTransaction();
        try {
            $this->productRepositoryInterface->update($description, $id);
            DB::commit();
            return ApiResponseClass::sendResponse('Product updated successfully', '', 201);
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e);
        }
    }

    public function destroy($id)
    {
        $this->productRepositoryInterface->delete($id);
        return ApiResponseClass::sendResponse('Product deleted successfully', '', 204);
    }
}
