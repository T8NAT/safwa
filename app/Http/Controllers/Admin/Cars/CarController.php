<?php

namespace App\Http\Controllers\Admin\Cars;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Cars\Car;
use App\Models\Admin\Cars\CarArea;
use App\Models\Admin\Cars\CarType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class CarController extends Controller
{
    public function index()
    {
        $page_title = __("Cars");

        $carsWithBookingStatus = Car::join('car_bookings', 'cars.id', '=', 'car_bookings.car_id')
            ->select('cars.id', 'cars.car_area_id', 'cars.car_type_id', 'cars.slug', 'cars.car_model', 'cars.car_number', 'cars.seat', 'cars.experience', 'cars.fees','cars.image', 'cars.status', DB::raw('MAX(car_bookings.status) as booking_status'))
            ->groupBy('cars.id', 'cars.car_area_id', 'cars.car_type_id', 'cars.slug', 'cars.car_model', 'cars.car_number', 'cars.seat', 'cars.experience', 'cars.fees','cars.image', 'cars.status')
            ->get();

        $carsWithoutBookings = Car::whereDoesntHave('bookings')->get();

        $cars = $carsWithBookingStatus->concat($carsWithoutBookings);
        return view('admin.sections.cars.index', compact(
            'page_title',
            'cars'
        ));
    }
    /**
     * Method for show car create page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function create()
    {
        $page_title = __("Car Create");
        $car_area = CarArea::orderBy('name', 'ASC')->get();

        return view('admin.sections.cars.create', compact(
            'page_title',
            'car_area',
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
        $area = CarArea::with(['types' => function ($type) {
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
            'car_model'   => 'required|string',
            'car_number'  => 'required|string|max:100',
            'seat'        => 'required|numeric',
            'experience'  => 'required|numeric',
            'fees'        => 'required|numeric',
            'image'       => 'required|image|mimes:png,jpg,jpeg,svg,webp',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $validated                   = $validator->validate();
        $validated['slug']           = Str::uuid();
        $validated['car_area_id']    = $validated['area'];
        $validated['car_type_id']    = $validated['type'];

        if (Car::where('car_number', $validated['car_number'])->exists()) {
            throw ValidationException::withMessages([
                'car_number'  => __("Car already exists!"),
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
                $upload = uploadImage($image, '/site-section/', $car->image ?? null); 
                $validated['image'] = $upload;
                
            } catch (\Exception $e) {
                \Log::error('Error uploading image: ' . $e->getMessage());
                return back()->with(['error' => ['Failed to upload image: ' . $e->getMessage()]]); 
                }
              }
              
        $validated = Arr::except($validated, ['area', 'type']);
        try {
            $car = Car::create($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__("Something went wrong! Please try again.")]]);
        }
        return redirect()->route('admin.car.index')->with(['success' => [__("Car Created Successfully!")]]);
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
        $cars = Car::find($validated['data_target']);
        try {
            $cars->update([
                'status'   => ($validated['status']) ? false : true,
            ]);
        } catch (Exception $e) {
            $errors = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($errors, null, 500);
        }
        $success = ['success' => [__('Car status updated successfully!')]];
        return Response::success($success);
    }
    /**
     * Method for show car edit page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function edit($id)
    {
        $page_title = __("Car Edit");
        $cars  = Car::find($id);
        if (!$cars) return back()->with(['error' => [__("Car Does not exists")]]);

        $car_area = CarArea::where('status', true)->orderBy('name', 'ASC')->get();
        $car_type = CarType::where('status', true)->get();

        return view('admin.sections.cars.edit', compact(
            'page_title',
            'cars',
            'car_area',
            'car_type',
        ));
    }
    /**
     * Method for update car
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        $validator = Validator::make($request->all(), [
            'area'        => 'required',
            'type'        => 'required',
            'car_model'   => 'required|string',
            'car_number'  => 'required|string|max:100',
            'seat'        => 'required|numeric',
            'experience'  => 'required|numeric',
            'fees'        => 'required|numeric',
            'image'       => 'nullable|image|mimes:png,jpg,jpeg,svg,webp',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validated                 = $validator->validate();
        $validated['slug']         = Str::uuid();
        $validated['car_area_id']  = $validated['area'];
        $validated['car_type_id']  = $validated['type'];

        if (Car::where('car_number', $validated['car_number'])->exists()) {
            throw ValidationException::withMessages([
                'car_number' => __("Car already exists!"),
            ]);
        }
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            try {
                $upload = uploadImage($image, '/site-section/', $car->image ?? null); 
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
            $car->update($validated);
        } catch (Exception $e) {
            return back()->with(['error'  => [__('Something went wrong! Please try again.')]]);
        }
        return redirect()->route('admin.car.index')->with(['success' => [__('Car Updated Successfully!')]]);
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
        $cars = Car::find($request->target);

        try {
            $cars->delete();
        } catch (Exception $e) {
            return back()->with(['error'  =>  [__("Something went wrong! Please try again.")]]);
        }
        return back()->with(['success'  => [__("Car Deleted Successfully!")]]);
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
