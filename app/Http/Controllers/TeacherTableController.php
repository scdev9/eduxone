<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherTableController extends Controller
{
    //
 
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        $teachers=DB::select('select * from teachers');
        $user = Auth::user()->role_id;
        if ($user==0 ){

        return view('teachers.index',compact('teachers'));
        }
        return response('Unauthorized.', 401);
    }

    public function create(){
        $user = Auth::user()->role_id;
        $subjects=DB::select('select * from subjects');
        $userName=Auth::user()->name;
        $userEmail=Auth::user()->email;
        if ($user==2 ){
        return view('teachers.create',compact('subjects','userName','userEmail'));
        }
        return response('Unauthorized.', 401);
    }


    public function store(Request $request)  {

        
        $user = Auth::user()->role_id;
        $userId=Auth::user()->id;
       
        if ($user==2 ){
            
       // dd($teacherId);

       $request->validate([
        'teacherName'=> 'required|max:255|string',
        'teacherEmail'=> 'required',
        'subject'=> 'required|string',
        

       ]);



       Teacher::create([
        'teacher_name'=>$request->teacherName,
          'teacher_email'=> $request->teacherEmail,
          'teacher_subject'=> $request->subject,
          'user_id' =>$userId,
               ]);

       return redirect()->back()->with('status','Teacher Profile Created.');
            }
       return response('Unauthorized.', 401);
    }
    

    public function edit(int $id){
        $subjects=DB::select('select * from subjects');
        $teach=Teacher::findOrFail($id);
        $user = Auth::user()->role_id;
        if ($user==0 ){
       // return ($teach);
       return view('teachers.edit',compact('teach','subjects'));
        }

        return response('Unauthorized.', 401);
    }

    public function update(Request $request,int $id){

        $user = Auth::user()->role_id;
        if ($user==0 ){
        $request->validate([
            'teacherName'=> 'required|max:255|string',
            'teacherEmail' => 'required',
            'subject' => 'required',
            
        ]);

        Teacher::findOrFail($id)->update([
            'teacher_name'=> $request->teacherName,
            'teacher_email'=> $request->teacherEmail,
            'teacher_subject'=>$request->subject

        ]);

        return redirect()->back()->with('status','Update Done.');
      }
      return response('Unauthorized.', 401);
    }

    public function destroy(int $id){
        $teacher=Teacher::findOrFail($id);
        $user = Auth::user()->role_id;
        if ($user==0 ){
        $teacher->delete();

        return redirect()->back()->with('status','Record Deleted.');
        }
        return response('Unauthorized.', 401);
    }

    
}
