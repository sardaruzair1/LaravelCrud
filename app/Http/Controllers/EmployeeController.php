<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    //
    public function index(){
        $employees = Employee::orderBy('id','DESC')->paginate(5);
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

    public function edit($id){
        $employee = Employee::findOrFail($id);
      
        return view("employee.edit",['employee'=>$employee]);
    }
    public function update($id, Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
        ]);
        if ( $validator->passes() ) {
            $employee = Employee::find($id);
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->save();
        //Upload Image
        if ($request->image) {
            $oldImage = $employee->image;
            $ext = $request->image->getClientOriginalExtension();
            $newFileName = time().'.'.$ext;
            $request->image->move(public_path().'/uploads/employees/',$newFileName); // This will save file in a folder
            
            $employee->image = $newFileName;
            $employee->save();
            File::delete(public_path().'/uploads/employees/'.$oldImage);
        }

            $request->session()->flash('success','Employee Updated Successfully! ');
            return redirect()->route('employees.index');
        }else {
            return redirect()->route('employees.edit',$id)->withErrors($validator)->withInput();
        }
    }

    public function destroy($id, Request $request){
        $employee = Employee::findOrFail($id);
        File::delete(public_path().'/uploads/employees/'.$employee->image);
        $employee->delete();
        $request->session()->flash('success','Employee Deleted Successfully! ');
        return redirect()->route('employees.index');
    }

}
