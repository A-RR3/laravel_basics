<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {

        $student =  Student::all();
        $data = [
            'status' => 200,
            'student' => $student
        ];

        return response()->json($data, 200);
    }

    public function upload(Request $request)
    {
        return $request->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email'
            ]
            // ,[
            //     'name.required'=>"يجب ...",
            // ]
        );

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages()
                // 'message' => $validator->errors()->first()
            ];
            return response()->json($data, 422);
        } else {
            // $student = new Student();
            // $student->name = $request->name;
            // $student->email = $request->email;
            // $student->phone = $request->phone;
            // $student->save();
            Student::create($request->all());
            $data = [
                'status' => 201,
                'message' => 'data uploaded successfuly'
            ];
            return response()->json($data, 201);
        }
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages()
            ], 422);
        } else {
            $student = Student::find($id);
            // $student->name = $request->name;
            // $student->email = $request->email;
            // $student->phone = $request->phone;
            // $student->save();
            $student->update($request->all());
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Data updated successfully',
                    'data' => $student
                ],
                200
            );
        }
    }

    public function delete($id)
    {
        // $student = Student::find($id);
        // $student->delete();
        Student::destroy($id);

        $data = [
            'status' => 200,
            'message' => 'deleted successfully'
        ];
        return response()->json($data,200);
    }

    public function search($name)
    {
        // $student = Student::find($id);
        // $student->delete();
        return Student::where('name', 'like', '%'.$name.'%')->get();
    }
}
