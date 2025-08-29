<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ApiMessages;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\User\CategoryResource as UserCategoryResource;
use App\Model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return Response::successResponse(200,__('api.category_index_success'),$categories);
    }
    public function indexUserCategory(){
        $categories = Category::paginate(10);
        return Response::successResponse(200,__('api.category_index_success'),
        [
            'categories' => UserCategoryResource::collection($categories),
            'links' => UserCategoryResource::collection($categories)->response()->getData()->links,
        ]);
    }
    public function store(CategoryRequest $request)
    {
        $validatedCategory = $request->validated();
        $category = Category::create($validatedCategory);
        return Response::successResponse(200,__('api.category_store_success'),new CategoryResource($category));
    }

    public function show(Category $category){
        return Response::successResponse(200,__('api.category_show_success'),new CategoryResource($category));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validatedCategory = $request->validated();
        $categoryUnique = Category::query()
            ->where('name', $validatedCategory['name'])
            ->where('id','!=',$category->id)
            ->first();
        if($categoryUnique)
        {
            return Response::errorResponse(422,__('api.category_update_error'));
        }
        $category->update($validatedCategory);
        return Response::successResponse(200,__('api.category_update_success'),new CategoryResource($category));

    }

    public function delete(Category $category){
        $category->delete();
        return Response::successResponse(200,__('api.category_delete_success'));
    }
    public function forceDelete(Category $category){
        $category->forceDelete();
        return Response::successResponse(200,__('api.category_delete_success'));
    }
    public function restore($id){
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return Response::successResponse(200,__('api.category_restore_success'));
    }

    public function getItems(Category $category)
    {
        return Response::SuccessResponse(200,__('api.get_category_success'),new CategoryResource($category->load('items')));
    }
    }
