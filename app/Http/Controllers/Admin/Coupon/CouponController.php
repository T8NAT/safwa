<?php

namespace App\Http\Controllers\Admin\Coupon;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\Coupons\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{

    public function index()
    {
        $page_title = __("Coupon List");
        $coupons = Coupon::query()->latest()->get();
        return view('admin.sections.coupons.index', compact('coupons','page_title'));
    }


    public function create()
    {
        $page_title = __("Add Coupon");
        return view('admin.sections.coupons.create', compact('page_title'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'discount' => 'required|numeric',
            'code'=>  'required|string|unique:coupons,code',
            'expiry_date' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = $request->only(['name','discount','code','expiry_date']);
        try {
            Coupon::query()->create($data);
        }
        catch (\Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);

        }
        return redirect()->route('admin.coupon.index')->with(['success' => [__('Coupon created successfully!')]]);

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $coupon = Coupon::query()->find($id);
        if(!$coupon) return back()->with(['error'=> [__('Area Not Found')]]);
        $page_title  = __("Coupon Edit");

        return view('admin.sections.coupons.edit',compact('coupon', 'page_title'));
    }

    public function update(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $coupon = Coupon::query()->find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'discount' => 'required|numeric',
            'code'=>  'required|string|unique:coupons,code',
            'expiry_date' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = $request->only(['name','discount','code','expiry_date']);
        try {
           $isUpdated = $coupon->update($data);
        }
        catch (\Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);

        }
        return redirect()->route('admin.coupon.index')->with(['success' => [__('Coupon updated successfully!')]]);
    }


    public function delete(Request $request)
    {
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
        $coupon = Coupon::query()->find($request->target);

        try {
            $coupon->delete();
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return back()->with(['success' => [__('Coupon Deleted Successfully!')]]);
    }

    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:coupons,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }
        $validated = $validator->validate();

        $coupon = Coupon::find($validated['data_target']);

        try{
            $coupon->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => [__('Something went wrong! Please try again.')] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => [__('Coupon status updated successfully!')]];
        return Response::success($success);
    }
}
