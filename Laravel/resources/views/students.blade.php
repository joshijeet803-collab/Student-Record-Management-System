<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List | SRMS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Student Records</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Roll No</th>
            <th>Email</th>
            <th>Course</th>
            <th>Year</th>
            <th>Phone</th>
        </tr>

        @forelse ($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->roll_no }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->course }}</td>
                <td>{{ $student->year }}</td>
                <td>{{ $student->phone }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No students found.</td>
            </tr>
        @endforelse
    </table>

    <p><a href="/">Back to Home</a></p>
</body>
</html>