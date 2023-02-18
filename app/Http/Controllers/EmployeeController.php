<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //
    public function index(){
        return view('employee.list');
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
            $request->session()->flash('success','Employee Added Successfully! ');
            return redirect()->route('employees.index');
        }else {
            return redirect()->route('employees.create')->withErrors($validator)->withInput();
        }
    }

}
