<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = DB::table('students')->get(); // Query Builder 
        // $student = Student::all(); // Eloquent 
        return response()->json($student);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'class_id'=> 'required|',
            'section_id'=> 'required',
            'name'=> 'required|unique:students|max:25',
            'phone'=> 'required',
            'email'=> 'required|unique:students|max:25',
            'password'=> 'required',
            'photo'=> 'required',
            'address'=> 'required',
            'gender'=> 'required',
        ]);

        $data = array();
        $data['class_id'] = $request->class_id;
        $data['section_id'] = $request->section_id;
        $data['name'] = $request->name;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['photo'] = $request->photo;
        $data['address'] = $request->address;
        $data['gender'] = $request->gender;
        DB::table('students')->insert($data);

        return response()->json(['message' => 'Student Inserted Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $student = DB::table('students')->where('id', $id)->first();
        $student= Student::findorfail($id);
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = array();
        $data['class_id'] = $request->class_id;
        $data['section_id'] = $request->section_id;
        $data['name'] = $request->name;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['photo'] = $request->photo;
        $data['address'] = $request->address;
        $data['gender'] = $request->gender;
        DB::table('students')->where('id', $id)->update($data);
        return response()->json(['message' => 'Student Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $img = DB::table('students')->where('id', $id)->first(); // Get the first data
        $image_path= $img->photo; // get only the image data 

        unlink($image_path);
        DB::table('students')->where('id', $id)->delete(); // Image deleted from folder
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
