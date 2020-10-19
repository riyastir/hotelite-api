<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HotelProfile;
use Illuminate\Http\Request;
use App\Http\Resources\HotelProfileCollection;
use Illuminate\Support\Facades\Auth;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\HotelProfile as HotelProfileResource;
use Image;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\AtMost;

class HotelProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hotels = new HotelProfileCollection(HotelProfile::all());
        return $hotels;
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
            'name' => 'required',
            'locality' => 'required',
            'city' => 'required',
            'state' => 'required|string',
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'country' => 'required',
            'country_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','message' => $validator->errors()->first()], 401);
        }

        // Sanitization
        $name = strip_tags($request->name);
        $locality = strip_tags($request->locality);
        $city = strip_tags($request->city);
        $state = strip_tags($request->state);
        $lat = $request->lat;
        $lng = $request->lng;
        $country = $request->country;
        $countryCode = $request->country_code;
        $logo = $request->logo;

        if ($logo != '') {
            $ext = explode('/', mime_content_type($logo))[1];
            $filename = $this->getName(10) . '.' . $ext;
            $path = storage_path('app/public/hotels/logos/' . Auth::user()->id . '/' . $filename);
            Storage::makeDirectory('public/hotels/logos/' . Auth::user()->id);
            Image::make(file_get_contents($logo))->save($path);
            $relative_path = '/storage/hotels/logos/' . Auth::user()->id . '/' . $filename;
        }


        //Check data exists for this hotel
        $exists = HotelProfile::where('user_id', Auth::user()->id)->count();
        if ($exists == 0) {
            if ($logo != '') {
                //Create entry
                $data = [
                    'logo' => $relative_path,
                    'name' => $name,
                    'user_id' => Auth::user()->id,
                    'locality' => $locality,
                    'city' => $city,
                    'state' => $state,
                    'lat' => $lat,
                    'lng' => $lng,
                    'country' => $country,
                    'country_code' => $countryCode
                ];
            } else {
                //Create entry
                $data = [
                    'name' => $name,
                    'user_id' => Auth::user()->id,
                    'locality' => $locality,
                    'city' => $city,
                    'state' => $state,
                    'lat' => $lat,
                    'lng' => $lng,
                    'country' => $country,
                    'country_code' => $countryCode
                ];
            }


            $create = HotelProfile::create($data);

            if ($create->id) {
                return  response()->json(['status' => 'success', 'message' => 'Profile created', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => $data], 500);
            }
        } else {
            // Update entry
            $id = HotelProfile::where('user_id', Auth::user()->id)->first()->id;

            if ($logo != '') {
                //Update entry
                $data = [
                    'logo' => $relative_path,
                    'name' => $name,
                    'user_id' => Auth::user()->id,
                    'locality' => $locality,
                    'city' => $city,
                    'state' => $state,
                    'lat' => $lat,
                    'lng' => $lng,
                    'country' => $country,
                    'country_code' => $countryCode
                ];
            } else {
                //Update entry
                $data = [
                    'name' => $name,
                    'user_id' => Auth::user()->id,
                    'locality' => $locality,
                    'city' => $city,
                    'state' => $state,
                    'lat' => $lat,
                    'lng' => $lng,
                    'country' => $country,
                    'country_code' => $countryCode
                ];
            }
            $update = HotelProfile::where('id', $id)->update($data);
            if ($update) {
                return  response()->json(['status' => 'success', 'message' => 'Profile updated', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => $data], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HotelProfile  $hotelProfile
     * @return \Illuminate\Http\Response
     */
    public function show(HotelProfile $hotelProfile)
    {
        $hotels = HotelProfile::where('user_id', Auth::user()->id)->first();
        $hotels = new HotelProfileResource($hotels);
        return $hotels;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HotelProfile  $hotelProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validations
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'locality' => 'required',
            'city' => 'required',
            'state' => 'required|string',
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // Sanitization
        $name = strip_tags($request->name);
        $locality = strip_tags($request->locality);
        $city = strip_tags($request->city);
        $state = strip_tags($request->state);
        $lat = $request->lat;
        $lng = $request->lng;

        //Check data exists for this hotel
        $exists = HotelProfile::where('user_id', Auth::user()->id)->count();

        if ($exists == 1) {
            $id = HotelProfile::where('user_id', Auth::user()->id)->first()->id;

            //Update entry
            $data = [
                'name' => $name,
                'user_id' => Auth::user()->id,
                'locality' => $locality,
                'city' => $city,
                'state' => $state,
                'lat' => $lat,
                'lng' => $lng
            ];
            $update = HotelProfile::where('id', $id)->update($data);
            if ($update) {
                return  response()->json(['status' => 'success', 'message' => 'Profile updated', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'data' => $data], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Please create profile', 'data' => []], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HotelProfile  $hotelProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelProfile $hotelProfile)
    {
        //
    }

    public function base64_to_jpeg($base64_string, $output_file)
    {
        // open the output file for writing
        $ifp = fopen($output_file, 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }

    public function getName($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     * to get recommended hotels (4)
     */
    public function getRecommended(){
        $hotels = HotelProfile::limit(4)->get();
        return new HotelProfileCollection($hotels);
    }
}
