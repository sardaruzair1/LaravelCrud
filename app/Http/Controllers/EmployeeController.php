<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //
    public function index(){
        $employees = Employee::orderBy('id','DESC')->get();
        return view('employee.list',['employees'=>$employees]);
    }
    public function create(){
        return view('employee.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
        ]);
        if ( $validator->passes() ) {
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->save();
        //Upload Image
        if ($request->image) {
            $ext = $request->image->getClientOriginalExtension();
            $newFileName = time().'.'.$ext;
            $request->image->move(public_path().'/uploads/employees/',$newFileName); // This will save file in a folder
            
            $employee->image = $newFileName;
            $employee->save();
        }

            $request->session()->flash('success','Employee Added Successfully! ');
            return redirect()->route('employees.index');
        }else {
            return redirect()->route('employees.create')->withErrors($validator)->withInput();
        }
    }

}
