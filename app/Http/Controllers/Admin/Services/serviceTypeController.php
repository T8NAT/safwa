<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\Services\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class serviceTypeController extends Controller
{

    public function index()
    {
        $page_title = __("Service Types");
        $service_types = ServiceType::query()->orderByDesc("id")->paginate(11);

        return view('admin.sections.services.service-type.index',compact('page_title', 'service_types'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:80|unique:service_types,name',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","add-car-types");
        }

        $validated = $validator->validate();


        $slug  = Str::slug($request->name);

        $validated['slug']              = $slug;
        $validated['status']            = 1;
        $validated['last_edit_by']      = auth()->user()->id;

        try{
            ServiceType::create($validated);
        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Service Type created successfully!')]]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:service_types,id',
            'edit_name'     => 'required|string|max:80|',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","service-types-edit");
        }

        $validated = $validator->validate();

        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;
        $validated['last_edit_by']   = auth()->user()->id;
        $service_types = ServiceType::find($request->target);

        try{
            $service_types->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return back()->with(['success' => [__('Service Type updated successfully!')]]);

    }
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
        $service_types = ServiceType::find($request->target);

        try {
            $service_types->delete();
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return back()->with(['success' => [__('Service Type Deleted Successfully!')]]);

    }

    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:service_types,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $service_types = ServiceType::find($validated['data_target']);

        try{
            $service_types->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            dd($e);
            $errors = ['error' => [__('Something went wrong! Please try again.')] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => [__('Service Type status updated successfully!')]];
        return Response::success($success);
    }
}
