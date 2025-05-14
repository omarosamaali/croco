<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index($lang)
    {
        $data = getViewData($lang);
        return view('contact-us', $data);
    }

    public function store(Request $request, $lang)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'privacy' => 'required',
        ]);

        unset($validated['privacy']);

        ContactMessage::create($validated);

        return redirect()->route('contact-us', ['lang' => $lang])->with('success', 'تم إرسال رسالتك بنجاح!');
    }
    
    public function dashboard($lang)
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        $unreadCount = ContactMessage::where('is_read', false)->count();
        
        $data = getViewData($lang);
        $data['messages'] = $messages;
        $data['unreadCount'] = $unreadCount;
        
        return view('admin.contact-dashboard', $data);
    }
    
    public function markAsRead($lang, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->is_read = true;
        $message->save();
    
        return redirect()->back()->with('success', 'تم تحديد الرسالة كمقروءة');
    }
    
    public function show($lang, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $data = getViewData($lang);
        $data['message'] = $message;
    
        return view('admin.contact.show', $data);
    }
public function destroy($lang, $id)
{
    $message = ContactMessage::findOrFail($id);
    $message->delete();
    return redirect()->route('contact.dashboard', ['lang' => $lang])->with('success', 'تم الحذف بنجاح!');
}

    public function markAsReplied($lang, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->is_replied = true;
        $message->save();
        
        return redirect()->back()->with('success', 'تم تحديد الرسالة كمردود عليها');
    }
}
