<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('course')->latest()->paginate(20);
        $courses = Course::all();
        return view('admin.subjects.index', compact('subjects', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required|integer|min:1|max:10',
        ]);

        $data = $request->all();
        $data['course'] = 'deprecated'; // Temporary placeholder for legacy column constraint
        Subject::create($data);

        return back()->with('success', 'Subject mapped to academic node.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Subject node purged.');
    }
}
