<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCpuponRequest;
use App\Models\ProductCoupon;
use Illuminate\Http\Request;

class ProductCouponController extends Controller
{
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_product_coupons, show_product_coupons')) {
            return redirect('admin/index');
        }
        $coupons = ProductCoupon::query()
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.product_coupons.index', compact('coupons'));
    }
    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_product_coupons')) {
            return redirect('admin/index');
        }


        return view('backend.product_coupons.create');

    }
    public function store(ProductCpuponRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_product_coupons')) {
            return redirect('admin/index');
        }
        try {
            ProductCoupon::create($request->validated());
            return redirect()->route('admin.product_coupons.index')->with([
                'message' => 'Created successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
    }
    public function show($id)
    {
        if (!auth()->user()->ability('admin', 'display_product_coupons')) {
            return redirect('admin/index');
        }

        return view('backend.product_coupons.show');

    }
    public function edit(ProductCoupon $productCoupon)
    {
        if (!auth()->user()->ability('admin', 'update_product_coupons')) {
            return redirect('admin/index');
        }
        return view('backend.product_coupons.edit', compact('productCoupon'));
    }
    public function update(ProductCpuponRequest $request, ProductCoupon $productCoupon)
    {

        if (!auth()->user()->ability('admin', 'update_product_coupons')) {
            return redirect('admin/index');
        }
        try {
            $productCoupon->update($request->validated());
            return redirect()->route('admin.product_coupons.index')->with([
                'message' => 'Updated successfully',
                'alert-type' => 'success'
            ]);
            return redirect()->route('admin.product_coupons.index')->with([
                'message' => 'Updated successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $ex) {
            //عرض  ايرور  لارفيل
            // return redirect()->back()->withErrors(['error' => $ex ->getMessage()]);
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);

        }
    }
    public function destroy(ProductCoupon $productCoupon)
    {
        if (!auth()->user()->ability('admin', 'delete_product_coupons')) {
            return redirect('admin/index');
        }

        $productCoupon->delete();

        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    }
}
