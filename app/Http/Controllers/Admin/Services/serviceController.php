<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\Services\Service;
use App\Models\Admin\Services\ServiceArea;
use App\Models\Admin\Services\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class serviceController extends Controller
{
    public function index()
    {
        $page_title = __("Services");

        $servicesWithBookingStatus = Service::join('service_bookings', 'services.id', '=', 'service_bookings.service_id')
            ->select('services.id', 'services.service_area_id', 'services.service_type_id', 'services.slug', 'services.name','services.size', 'services.number_bottles', 'services.price','services.image', 'services.status', DB::raw('MAX(service_bookings.status) as booking_status'))
            ->groupBy('services.id', 'services.service_area_id', 'services.service_type_id', 'services.slug', 'services.number_bottles', 'services.price','services.image', 'services.status')
            ->get();

        $servicesWithoutBookings = Service::query()->whereDoesntHave('bookings')->get();

        $services = $servicesWithBookingStatus->concat($servicesWithoutBookings);
        return view('admin.sections.services.index', compact(
            'page_title',
            'services'
        ));
    }
    /**
     * Method for show car create page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function create()
    {
        $page_title = __("Service Create");
        $service_area = ServiceArea::orderBy('name', 'ASC')->get();

        return view('admin.sections.services.create', compact(
            'page_title',
            'service_area',
        ));
    }
    /**
     * Method for get all departments based on branch
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function getAreaTypes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area'  => 'required|integer',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        $area = ServiceArea::with(['types' => function ($type) {
            $type->with(['type'=> function($car_type){
                $car_type->where('status', true);
            }]);
        }])->find($request->area);
        if (!$area) return Response::error([__('Area Not Found')], 404);

        return Response::success([__('Data fetch successfully')], ['area' => $area], 200);
    }
    /**
     * Method for store car
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area'        => 'required',
            'type'        => 'required',
            'name'        => 'required|string',
            'number_bottles'  => 'required|string|max:100',
            'size'        => 'required|numeric',
            'price'        => 'required|numeric',
            'image'       => 'required|image|mimes:png,jpg,jpeg,svg,webp',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $validated                   = $validator->validate();
        $validated['slug']           = Str::uuid();
        $validated['service_area_id']    = $validated['area'];
        $validated['service_type_id']    = $validated['type'];

        if (Service::where('name', $validated['name'])->exists()) {
            throw ValidationException::withMessages([
                'name'  => __("Name already exists!"),
            ]);
        }
        // if ($request->hasFile("image")) {
        //     $image = get_files_from_fileholder($request, 'image');
        //     $upload = upload_files_from_path_dynamic($image, 'site-section');
        //     $validated['image'] = $upload;
        // }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            try {
                $upload = uploadImage($image, 'images/services/', $service->image ?? null);
                $validated['image'] = $upload;

            } catch (\Exception $e) {
                \Log::error('Error uploading image: ' . $e->getMessage());
                return back()->with(['error' => ['Failed to upload image: ' . $e->getMessage()]]);
            }
        }

        $validated = Arr::except($validated, ['area', 'type']);
        try {
            $service = Service::query()->create($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__("Something went wrong! Please try again.")]]);
        }
        return redirect()->route('admin.service.index')->with(['success' => [__("Service Created Successfully!")]]);
    }
    /**
     * Method for update car status
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function statusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_target'  => 'required|numeric|exists:cars,id',
            'status'       => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $errors = ['error' => $validator->errors()];
            return Response::error($errors);
        }

        $validated = $validator->validate();
        $service = Service::find($validated['data_target']);
        try {
            $service->update([
                'status'   => ($validated['status']) ? false : true,
            ]);
        } catch (Exception $e) {
            $errors = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($errors, null, 500);
        }
        $success = ['success' => [__('Service status updated successfully!')]];
        return Response::success($success);
    }
    /**
     * Method for show car edit page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function edit($id)
    {
        $page_title = __("Service Edit");
        $service  = Service::find($id);
        if (!$service) return back()->with(['error' => [__("Car Does not exists")]]);

        $service_area = ServiceArea::where('status', true)->orderBy('name', 'ASC')->get();
        $service_type = ServiceType::where('status', true)->get();

        return view('admin.sections.services.edit', compact(
            'page_title',
            'service',
            'service_area',
            'service_type',
        ));
    }
    /**
     * Method for update car
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        $validator = Validator::make($request->all(), [
            'area'        => 'required',
            'type'        => 'required',
            'name'        => 'required|string',
            'number_bottles'  => 'required|string|max:100',
            'size'        => 'required|numeric',
            'price'        => 'required|numeric',
            'image'       => 'required|image|mimes:png,jpg,jpeg,svg,webp',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validated                 = $validator->validate();
        $validated['slug']         = Str::uuid();
        $validated['service_area_id']  = $validated['area'];
        $validated['service_type_id']  = $validated['type'];

        if (Service::where('name', $validated['name'])->exists()) {
            throw ValidationException::withMessages([
                'name' => __("Name already exists!"),
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            try {
                $upload = uploadImage($image, '/images/services/', $service->image ?? null);
                $validated['image'] = $upload;

            } catch (\Exception $e) {
                \Log::error('Error uploading image: ' . $e->getMessage());
                return back()->with(['error' => ['Failed to upload image: ' . $e->getMessage()]]);
            }
        }

        // if ($request->hasFile('image')) {
        //     $image = get_files_from_fileholder($request, 'image');
        //     $upload = upload_files_from_path_dynamic($image, 'site-section', $car->image);
        //     $validated['image'] = $upload;
        // }
        $validated = Arr::except($validated, ['area', 'type']);
        try {
            $service->update($validated);
        } catch (Exception $e) {
            return back()->with(['error'  => [__('Something went wrong! Please try again.')]]);
        }
        return redirect()->route('admin.service.index')->with(['success' => [__('Service Updated Successfully!')]]);
    }
    /**
     * Method for delete car
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function delete(request $request)
    {
        $request->validate([
            'target'    => 'required|numeric',
        ]);
        $service = Service::find($request->target);

        try {
            $service->delete();
        } catch (Exception $e) {
            return back()->with(['error'  =>  [__("Something went wrong! Please try again.")]]);
        }
        return back()->with(['success'  => [__("Service Deleted Successfully!")]]);
    }
    /**
     * Method for image validate
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function imageValidate($request, $input_name, $old_image = null)
    {
        if ($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name), [
                $input_name => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();
            $image = get_files_from_fileholder($request, $input_name);
            $upload = upload_files_from_path_dynamic($image, 'site-section', $old_image);
            return $upload;
        }
        return false;
    }
}
