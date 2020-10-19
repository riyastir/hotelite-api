<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use App\Http\Resources\Package as PackageResource;
use App\Http\Resources\PackageCollection;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $package = Package::with('hotel_profile')->where('user_id', Auth::user()->id)->get();
        return new PackageCollection($package);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric',
            'duration_days' => 'required|numeric',
            'duration_nights' => 'required|numeric',
            'validity' => 'required|date',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','message' => $validator->errors()->first()], 401);
        }

        // Sanitization
        $price = $request->price;
        $duration_days = $request->duration_days;
        $duration_nights = $request->duration_nights;
        $validity = $request->validity;
        $description =  strip_tags($request->description);

        //Create entry
        $data = [
            'price' => $price,
            'user_id' => Auth::user()->id,
            'duration_days' => $duration_days,
            'duration_nights' => $duration_nights,
            'validity' => Carbon::parse($validity)->format('Y-m-d'),
            'description' => $description
        ];

        $create = Package::create($data);

        if ($create->id) {
            return  response()->json(['status' => 'success', 'message' => 'Package created', 'data' => $data], 200);
        } else {
            return  response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => $data], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::with('hotel_profile')->find($id);
        return new PackageResource($package);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Check Package exist
        $exist = Package::where('user_id', Auth::user()->id)->where('id', $id)->count();

        if ($exist == 0) {
            return response()->json(['status' => 'error', 'message' => 'Package doesnot exist', 'data' => []], 401);
        }

        // Validations
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric',
            'duration_days' => 'required|numeric',
            'duration_nights' => 'required|numeric',
            'validity' => 'required|date',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // Sanitization
        $price = $request->price;
        $duration_days = $request->duration_days;
        $duration_nights = $request->duration_nights;
        $validity = $request->validity;
        $description =  strip_tags($request->description);

        //Update entry
        $data = [
            'price' => $price,
            'user_id' => Auth::user()->id,
            'duration_days' => $duration_days,
            'duration_nights' => $duration_nights,
            'validity' => Carbon::parse($validity)->format('Y-m-d'),
            'description' => $description
        ];

        $update = Package::where('id', $id)->where('user_id', Auth::user()->id)->update($data);

        if ($update) {
            return  response()->json(['status' => 'success', 'message' => 'Package updated', 'data' => $data], 200);
        } else {
            return  response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => $data], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Exists and Belongs to user
        $exist = Package::where('user_id', Auth::user()->id)->where('id', $id)->count();

        if ($exist == 0) {
            return response()->json(['status' => 'error', 'message' => 'Package doesnot exist', 'data' => []], 401);
        } else {

            $delete = Package::where('user_id', Auth::user()->id)->where('id', $id)->delete();

            if ($delete) {
                return  response()->json(['status' => 'success', 'message' => 'Package deleted', 'data' => []], 200);
            } else {
                return  response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => []], 500);
            }
        }
    }

    /**
     * List all packages irrespective of hotel
     * @return \Illuminate\Http\Response
     */
    public function all(){
        $package = Package::with('hotel_profile')->get();
        return new PackageCollection($package);
    }
}
