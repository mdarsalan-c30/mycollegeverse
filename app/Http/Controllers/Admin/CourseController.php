<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:courses,name',
        ]);

        Course::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'New Academic Path initialized.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Academic Path purged from multiverse.');
    }
}
