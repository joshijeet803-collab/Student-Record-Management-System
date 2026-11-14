<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;


/* Home */

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');


/* Contact - Public */

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function (Request $request) {

    $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:150',
        'message' => 'required|string|max:5000',
    ]);

    DB::table('contact_messages')->insert([
        'user_id' => Session::get('srms_user_id'),
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message,
        'status' => 'New',
        'created_at' => now(),
    ]);

    return back()->with('success', 'Your message has been submitted successfully.');

})->name('contact.store');


/* Register */

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    DB::table('users')->insert([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'student',
    ]);

    return redirect()->route('login')
        ->with('success', 'Registration successful. Please login.');

})->name('register.store');

/* Admin Login */

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|string',
        'password' => 'required',
    ]);

    $admin = DB::table('users')
        ->where('role', 'admin')
        ->where('email', $request->email)
        ->first();

    if ($admin && password_verify($request->password, $admin->password)) {
        Session::put('srms_user_id', $admin->id);
        Session::put('srms_user_name', $admin->name);
        Session::put('srms_user_role', 'admin');

        return redirect()->route('dashboard');
    }

    return back()->with('error', 'Invalid admin email or password.');
})->name('admin.login.store');

/* Login */

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = DB::table('users')
        ->where('email', $request->email)
        ->first();

    if ($user && password_verify($request->password, $user->password)) {

        Session::put('srms_user_id', $user->id);
        Session::put('srms_user_name', $user->name);
        Session::put('srms_user_role', $user->role);

        return redirect()->route('dashboard');
    }

    return back()->with('error', 'Invalid email or password.');

})->name('login.store');


/* Logout */

Route::get('/logout', function () {

    Session::invalidate();
    Session::regenerateToken();

    return redirect()->route('login');

})->name('logout');

Route::post('/logout', function () {

    Session::invalidate();
    Session::regenerateToken();

    return redirect()->route('login');

})->name('logout.post');


/* Forgot Password */

Route::get('/forgot-password', function () {
    return view('auth.forgot');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
    ]);

    $user = DB::table('users')
        ->where('email', $request->email)
        ->first();

    if (!$user) {
        return back()->with('error', 'Email address not found.');
    }

    Session::put('reset_email', $user->email);

    return redirect()->route('password.reset');

})->name('password.email');


/* Reset Password */

Route::get('/reset-password', function () {

    if (!Session::has('reset_email')) {
        return redirect()->route('password.request');
    }

    return view('auth.reset');

})->name('password.reset');

Route::post('/reset-password', function (Request $request) {

    if (!Session::has('reset_email')) {
        return redirect()->route('password.request');
    }

    $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    DB::table('users')
        ->where('email', Session::get('reset_email'))
        ->update([
            'password' => bcrypt($request->password),
        ]);

    Session::forget('reset_email');

    return redirect()->route('login')
        ->with('success', 'Password updated successfully.');

})->name('password.update');


/* Dashboard */

Route::get('/dashboard', function () {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $total = DB::table('students')->count();

    $courses = DB::table('students')
        ->whereNotNull('course')
        ->where('course', '!=', '')
        ->distinct()
        ->count('course');

    $recentStudents = DB::table('students')
        ->orderByDesc('id')
        ->limit(5)
        ->get();

    return view('dashboard', compact(
        'total',
        'courses',
        'recentStudents'
    ));

})->name('dashboard');


/* Messages List */

Route::get('/messages', function () {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $userId = Session::get('srms_user_id');
    $role = Session::get('srms_user_role', 'student');

    $query = DB::table('contact_messages');

    if ($role !== 'admin') {
        $query->where('user_id', $userId);
    }

    $messages = $query->orderByDesc('id')->get();

    return view('messages.index', compact('messages'));

})->name('messages.index');


/* Edit Message Form */

Route::get('/messages/{id}/edit', function ($id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $message = DB::table('contact_messages')
        ->where('id', $id)
        ->first();

    abort_if(!$message, 404);

    $isAdmin = Session::get('srms_user_role') === 'admin';

    if (!$isAdmin && $message->user_id != Session::get('srms_user_id')) {
        abort(403);
    }

    return view('messages.edit', compact('message'));

})->name('messages.edit');


/* Update Message */

Route::put('/messages/{id}', function (Request $request, $id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:150',
        'message' => 'required|string|max:5000',
    ]);

    $record = DB::table('contact_messages')
        ->where('id', $id)
        ->first();

    abort_if(!$record, 404);

    $isAdmin = Session::get('srms_user_role') === 'admin';

    if (!$isAdmin && $record->user_id != Session::get('srms_user_id')) {
        abort(403);
    }

    DB::table('contact_messages')
        ->where('id', $id)
        ->update([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

    return redirect()->route('messages.index')
        ->with('success', 'Message updated successfully.');

})->name('messages.update');


/* Delete Message */

Route::delete('/messages/{id}', function ($id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $message = DB::table('contact_messages')
        ->where('id', $id)
        ->first();

    abort_if(!$message, 404);

    $isAdmin = Session::get('srms_user_role') === 'admin';

    if (!$isAdmin && $message->user_id != Session::get('srms_user_id')) {
        abort(403);
    }

    DB::table('contact_messages')
        ->where('id', $id)
        ->delete();

    return redirect()->route('messages.index')
        ->with('success', 'Message deleted successfully.');

})->name('messages.destroy');


/* Admin Reply Form */

Route::get('/messages/{id}/reply', function ($id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    if (Session::get('srms_user_role') !== 'admin') {
        abort(403);
    }

    $message = DB::table('contact_messages')
        ->where('id', $id)
        ->first();

    abort_if(!$message, 404);

    return view('messages.reply', compact('message'));

})->name('messages.reply');


/* Save Admin Reply */

Route::post('/messages/{id}/reply', function (Request $request, $id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    if (Session::get('srms_user_role') !== 'admin') {
        abort(403);
    }

    $request->validate([
        'reply' => 'required|string|max:5000',
    ]);

    $message = DB::table('contact_messages')
        ->where('id', $id)
        ->first();

    abort_if(!$message, 404);

    DB::table('contact_messages')
        ->where('id', $id)
        ->update([
            'admin_reply' => $request->reply,
            'reply' => $request->reply,
            'replied_at' => now(),
            'status' => 'Replied',
        ]);

    return redirect()->route('messages.index')
        ->with('success', 'Reply sent successfully.');

})->name('messages.reply.store');


/* Student List, Search and Filter */

Route::get('/students', function (Request $request) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $query = DB::table('students');

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('roll_no', 'like', "%{$search}%");
        });
    }

    if ($request->filled('course')) {
        $query->where('course', $request->course);
    }

    $students = $query->orderByDesc('id')->get();

    $courses = DB::table('students')
        ->whereNotNull('course')
        ->where('course', '!=', '')
        ->distinct()
        ->orderBy('course')
        ->pluck('course');

    return view('students.index', compact(
        'students',
        'courses'
    ));

})->name('students.index');


/* Add Student Form */

Route::get('/students/create', function () {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    return view('students.form');

})->name('students.create');


/* Save Student */

Route::post('/students', function (Request $request) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'roll_no' => 'required|string|max:50',
        'email' => 'required|email',
        'course' => 'required|string|max:100',
        'year' => 'required|string|max:50',
        'phone' => 'required|string|max:20',
    ]);

    DB::table('students')->insert([
        'name' => $request->name,
        'roll_no' => $request->roll_no,
        'email' => $request->email,
        'course' => $request->course,
        'year' => $request->year,
        'phone' => $request->phone,
    ]);

    return redirect()->route('students.index')
        ->with('success', 'Student added successfully.');

})->name('students.store');


/* Export CSV */

Route::get('/students/export/csv', function () {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $students = DB::table('students')
        ->orderBy('id')
        ->get();

    $filename = 'student_records_' . date('Y-m-d') . '.csv';

    return response()->streamDownload(function () use ($students) {

        $handle = fopen('php://output', 'w');

        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'ID',
            'Student Name',
            'Roll Number',
            'Email',
            'Course',
            'Year',
            'Phone'
        ]);

        foreach ($students as $student) {
            fputcsv($handle, [
                $student->id,
                $student->name,
                $student->roll_no,
                $student->email,
                $student->course,
                $student->year,
                $student->phone
            ]);
        }

        fclose($handle);

    }, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);

})->name('students.export');


/* View Student */

Route::get('/students/{id}/view', function ($id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $student = DB::table('students')
        ->where('id', $id)
        ->first();

    abort_if(!$student, 404);

    return view('students.view', compact('student'));

})->name('students.view');


/* Edit Student Form */

Route::get('/students/{id}/edit', function ($id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $student = DB::table('students')
        ->where('id', $id)
        ->first();

    abort_if(!$student, 404);

    return view('students.form', compact('student'));

})->name('students.edit');


/* Update Student */

Route::put('/students/{id}', function (Request $request, $id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'roll_no' => 'required|string|max:50',
        'email' => 'required|email',
        'course' => 'required|string|max:100',
        'year' => 'required|string|max:50',
        'phone' => 'required|string|max:20',
    ]);

    DB::table('students')
        ->where('id', $id)
        ->update([
            'name' => $request->name,
            'roll_no' => $request->roll_no,
            'email' => $request->email,
            'course' => $request->course,
            'year' => $request->year,
            'phone' => $request->phone,
        ]);

    return redirect()->route('students.index')
        ->with('success', 'Student updated successfully.');

})->name('students.update');


/* Delete Student */

Route::delete('/students/{id}', function ($id) {

    if (!Session::has('srms_user_id')) {
        return redirect()->route('login');
    }

    DB::table('students')
        ->where('id', $id)
        ->delete();

    return redirect()->route('students.index')
        ->with('success', 'Student deleted successfully.');

})->name('students.destroy');