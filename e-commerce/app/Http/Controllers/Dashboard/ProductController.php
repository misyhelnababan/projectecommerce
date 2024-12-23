<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Requests\Dashboard\Product\StoreRequest;
use App\Models\Category;
Use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends DashboardController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setTitle('Products');

        $this->addBreadcrumb('Dashboard',route('dashboard.index'));
        $this->addBreadcrumb('Products');
        $this->data['products']=Product::with('category')->get();
        $this->data['categories']=Category::all();
        return view('dashboard.product.index',$this->data);
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
{
    try {
        // Validasi data menggunakan StoreRequest
        $validated = $request->validated();

        // Simpan data ke database
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null, // Default ke null jika tidak ada deskripsi
        ]);

        // Kembalikan respons sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product,
        ])->setStatusCode(Response::HTTP_CREATED);
    } catch (\Throwable $th) {
        // Tangani kesalahan
        return response()->json([
            'status' => 'failed',
            'message' => 'Failed to create product',
            'error' => $th->getMessage(), // Opsional, bisa dihapus jika tidak ingin detail error ditampilkan
        ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    public function edit(Product $product)
    {
        return response()->json($product);
    }
    
    public function update(StoreRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();
            $product->update($validated);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy(Product $product)
    {
     try {
        $product->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'Product Deleted Successfully',
        ])->setStatusCode(Response::HTTP_OK);
     } catch (\Throwable $th) {
        return response()->json([
            'status'=>'ERROR',
            'message'=>'Failed to delete product',
        ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
     }
    }
}
