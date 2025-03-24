<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\Services\AreaHasType;
use App\Models\Admin\Services\ServiceArea;
use App\Models\Admin\Services\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class serviceAreaController extends Controller
{
    public function index(){
        $page_title = __("Service Area");
        $service_area = ServiceArea::query()->orderByDesc("id")->get();

        return view('admin.sections.services.service-area.index',compact('page_title', 'service_area'));
    }

    public function create(){
        $page_title = __("Service Area Create");
        $service_types  = ServiceType::query()->where('status',true)->get();

        return view('admin.sections.services.service-area.create',compact('page_title', 'service_types'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'        => 'required|string|max:80|unique:service_areas,name',
            'types'       => 'required|array',
            'types.*'     => 'required|integer',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $validated = $validator->validate();

        $slug  = Str::slug($request->name);

        $validated['slug']              = $slug;
        $validated['status']            = 1;
        $validated['last_edit_by']      = auth()->user()->id;

        try{
            $area = ServiceArea::query()->create($validated);
            if(count($validated['types']) > 0) {
                $types = [];
                foreach($validated['types'] as $type_id) {
                    $types[] = [
                        'service_area_id'  => $area->id,
                        'service_type_id'  => $type_id,
                        'created_at'   => now(),
                    ];
                }
                AreaHasType::insert($types);
            }
        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return redirect()->route('admin.service.area.index')->with(['success' => [__('Service Area created successfully!')]]);

    }

    public function edit($id){
        $area = ServiceArea::query()->find($id);
        $service_types   = ServiceType::query()->where('status',true)->get();
        if(!$area) return back()->with(['error'=> [__('Area Not Found')]]);
        $page_title  = __("Service Area Edit");

        return view('admin.sections.services.service-area.edit',compact(
            'area',
            'service_types',
            'page_title'
        ));

    }
    public function update(Request $request,$id)
    {
        $area     = ServiceArea::query()->find($id);
        $validator = Validator::make($request->all(),[
            'name'        => 'required|string|max:80',
            'types'       => 'required|array',
            'types.*'     => 'required|integer',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated     = $validator->validate();
        $request_types = $validated['types'];

        $validated     = Arr::except($validated,['types']);


        $slug                        = Str::slug($validated['name']);
        $validated['slug']           = $slug;
        $validated['last_edit_by']   = auth()->user()->id;

        try{
            $area_type_ids = $area->types->pluck('id');

            AreaHasType::whereIn('id',$area_type_ids)->delete();

            $area->update($validated);
            if(count($request_types) > 0) {

                $types = [];
                foreach($request_types as $type_id) {
                    $types[] = [
                        'service_area_id'      => $area->id,
                        'service_type_id'  => $type_id,
                        'created_at'   => now(),
                    ];
                }
                AreaHasType::insert($types);
            }


        }catch(Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again')]]);
        }

        return redirect()->route('admin.service.area.index')->with(['success' => [__('Service Area updated successfully!')]]);

    }
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
        $service_area = ServiceArea::find($request->target);

        try {
            $service_area->delete();
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return back()->with(['success' => [__('Service Area Deleted Successfully!')]]);

    }

    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:car_areas,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }
        $validated = $validator->validate();

        $service_types = ServiceArea::find($validated['data_target']);

        try{
            $service_types->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => [__('Something went wrong! Please try again.')] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => [__('Service Area status updated successfully!')]];
        return Response::success($success);
    }
}
