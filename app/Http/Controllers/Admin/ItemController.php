<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ApiMessages;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ItemStoreRequest;
use App\Http\Requests\Admin\ItemUpdateRequest;
use App\Http\Resources\User\ItemResource as UserItemResource;
use App\Http\Resources\Admin\ItemResource;
use App\Model\Category;
use App\Model\Item;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
        $items = Item::paginate(10);
        return Response::successResponse(200,__('api.item_index_success'),ItemResource::collection($items),
        ItemResource::collection($items)
        );
    }

    public function indexUserItem(){
        $items = Item::paginate(10);
        return Response::successResponse(200,__('api.item_index_success'),
        [
            'items' => UserItemResource::collection($items),
            'links' => UserItemResource::collection($items)->response()->getData()->links,
        ]);
    }

    public function store(ItemStoreRequest $request,FileUploadService $fileUploadService){
        $validatedItem = $request->validated();
        if ($request->hasFile('picture')) {
            $validatedItem['picture'] = $fileUploadService::uploadImage($request->file('picture'),'Items');
        }
        $item = Item::create($validatedItem);
        return Response::successResponse(200,__('api.item_store_success'),new ItemResource($item));
    }

    public function show(Item $item){
        return Response::successResponse(200,__('api.item_show_success'),new ItemResource($item));
    }

    public function update(Item $item,ItemUpdateRequest $request,FileUploadService $fileUploadService)
    {
        $validatedItem = $request->validated();
        if($request->hasFile('picture'))
        {
            if ($item->picture && file_exists(public_path('storage/images/Items/' . $item->picture))) {
                unlink(public_path('storage/images/Items/' . $item->picture));
            }
            $validatedItem['picture'] = $fileUploadService::uploadImage($request->file('picture'),'Items');
        }
        $item->update($validatedItem);
        return Response::successResponse(200,__('api.item_update_success'),
        new ItemResource($item)
        );
    }

    public function delete(Item $item){

        $item->delete();
        return Response::successResponse(200,__('api.item_delete_success'));
    }
    public function forceDelete(Item $item,FileUploadService $fileUploadService){
        if($item->picture)
        {
            unlink(public_path('storage/images/Items/'.$item->picture));
        }
        $item->forceDelete();
        return Response::successResponse(200,__('api.item_delete_success'),);
    }
    public function restore($id){
        $item= Item::withTrashed()->findOrFail($id);
        $item->restore();
        return Response::successResponse(200,__('api.item_restore_success'));
    }
}
