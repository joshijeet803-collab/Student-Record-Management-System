<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    private function loggedIn()
    {
        if (!Session::has('srms_user_id')) {
            return redirect()->route('login');
        }

        return null;
    }

    private function isAdmin()
    {
        return Session::get('srms_user_role') === 'admin';
    }

    private function getMessage($id)
    {
        $message = DB::table('contact_messages')->where('id', $id)->first();

        abort_if(!$message, 404);

        if (!$this->isAdmin() &&
            $message->user_id != Session::get('srms_user_id')) {
            abort(403);
        }

        return $message;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'message' => 'required|string|max:10000',
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
    }

    public function index()
    {
        if ($redirect = $this->loggedIn()) return $redirect;

        $messages = DB::table('contact_messages')
            ->when(!$this->isAdmin(), function ($query) {
                $query->where('user_id', Session::get('srms_user_id'));
            })
            ->orderByDesc('id')
            ->get();

        return view('messages.index', compact('messages'));
    }

    public function edit($id)
    {
        if ($redirect = $this->loggedIn()) return $redirect;

        $message = $this->getMessage($id);

        return view('messages.edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->loggedIn()) return $redirect;

        $message = $this->getMessage($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'message' => 'required|string|max:10000',
        ]);

        DB::table('contact_messages')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Message updated successfully.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->loggedIn()) return $redirect;

        $this->getMessage($id);

        DB::table('contact_messages')->where('id', $id)->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Message deleted successfully.');
    }

    public function replyForm($id)
    {
        if ($redirect = $this->loggedIn()) return $redirect;

        abort_unless($this->isAdmin(), 403);

        $message = DB::table('contact_messages')->where('id', $id)->first();

        abort_if(!$message, 404);

        return view('messages.reply', compact('message'));
    }

    public function sendReply(Request $request, $id)
    {
        if ($redirect = $this->loggedIn()) return $redirect;

        abort_unless($this->isAdmin(), 403);

        $request->validate([
            'reply' => 'required|string|max:10000',
        ]);

        DB::table('contact_messages')->where('id', $id)->update([
            'admin_reply' => $request->reply,
            'reply' => $request->reply,
            'status' => 'Replied',
            'replied_at' => now(),
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Reply sent successfully.');
    }
}