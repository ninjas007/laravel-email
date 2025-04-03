<?php

namespace App\Http\Controllers;

use App\Models\ContactList;
use App\Models\Message;
use App\Models\Template;
use Exception;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::with('contact_list', 'template')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('messages.index', [
            'page' => 'pesan',
            'messages' => $messages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('messages.create', [
            'page' => 'buat pesan',
            'templates' => Template::all(),
            'lists' => ContactList::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'subject' => 'required|string',
            'list_id' => 'required|integer|exists:contact_lists,id',
            'template_id' => 'required|integer|exists:templates,id',
        ], [
            'name.required' => 'Nama pesan harus diisi',
            'subject.required' => 'Subject pesan harus diisi',
            'list_id.required' => 'Daftar kontak harus dipilih',
            'template_id.required' => 'Template harus dipilih',
            'list_id.exists' => 'Daftar kontak tidak ditemukan',
            'template_id.exists' => 'Template tidak ditemukan',
        ]);

        try {
            $message = new Message();
            $message->name = $request->name;
            $message->subject = $request->subject;
            $message->list_id = $request->list_id;
            $message->template_id = $request->template_id;
            $message->save();

            return redirect()->route('messages.index')->with('success', 'Pesan berhasil dibuat');
        } catch (Exception $e) {
            $this->logError($request, $e);
            return redirect()->back()->with('error', 'Pesan gagal dibuat');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decodeId($id);

        return view('messages.edit', [
            'page' => 'edit pesan',
            'message' => Message::findOrFail($id),
            'templates' => Template::all(),
            'lists' => ContactList::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'subject' => 'required|string',
            'list_id' => 'required|integer|exists:contact_lists,id',
            'template_id' => 'required|integer|exists:templates,id',
        ], [
            'name.required' => 'Nama pesan harus diisi',
            'subject.required' => 'Subject pesan harus diisi',
            'list_id.required' => 'Daftar kontak harus dipilih',
            'template_id.required' => 'Template harus dipilih',
            'list_id.exists' => 'Daftar kontak tidak ditemukan',
            'template_id.exists' => 'Template tidak ditemukan',
        ]);

        $message = Message::findOrFail(decodeId($id));
        try {
            $message->name = $request->name;
            $message->subject = $request->subject;
            $message->list_id = $request->list_id;
            $message->template_id = $request->template_id;
            $message->save();

            return redirect()->route('messages.index')->with('success', 'Pesan berhasil dibuat');
        } catch (Exception $e) {
            $this->logError($request, $e);
            return redirect()->back()->with('error', 'Pesan gagal dibuat');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = decodeId($id);
        $message = Message::findOrFail($id);

        try {
            $message->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server. Silahkan coba lagi'
            ]);
        }
    }

    public function upload(Request $request)
    {
        dd($request->all());
    }
}
