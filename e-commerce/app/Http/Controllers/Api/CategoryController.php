<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        $data=[
        "message"=>"Data berhasil diambil",

        "data"=> $categories,
        ];
        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           $request->validate([
            "name"=>["required"],
            "slug"=>["required"]
           ],[
            "name.required"=>"Nama Kategori harus diisi",
            "slug.required"=>"Slug Kategori harus diisi"
           ]);
           Category::insert([
            "name"=>$request->name,
            "slug"=>$request->slug
           ]);
           return response()->json([
            "message"=>"Data Kategori Berhasil ditambahkan",
        ],Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>"Data Kategori Gagal Ditambahkan",
                "eror"=>$th->getMessage(),
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
       try {
        $request->validate([
            "name"=>["required"],
            "slug"=>["required"]
           ],[
            "name.required"=>"Nama Kategori harus diisi",
            "slug.required"=>"Slug Kategori harus diisi"
           ]);
           Category::where("id",$id)->update([
            "name"=>$request->name,
            "slug"=>$request->slug
           ]);
           return response()->json([
            "message"=>"Data Kategori Berhasil Diubah",
        ],Response::HTTP_OK);
       } catch (\Throwable $th) {
        return response()->json([
            "message"=>"Data Kategori Gagal Diubah",
            "eror"=>$th->getMessage(),
        ],Response::HTTP_BAD_REQUEST);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category=Category::where("id",$id)->first();
            if(!$category){
                return response()->json([
                    "message" => "Data Kategori Tidak ditambahkan",
                ],Response::HTTP_NOT_FOUND);
            }
            $category->delete();
            return response()->json([
                "message"=>"Data Kategori Berhasil Dihapus  ",
            ],Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>"Data Kategori Gagal Dihapus",
                "eror"=>$th->getMessage(),
            ],Response::HTTP_BAD_REQUEST); 
        }
    }
}
