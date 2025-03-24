<?php

namespace App\Http\Controllers;

use App\Models\ContactList;
use Illuminate\Http\Request;

class ContactListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact_lists.index', [
            'page' => 'List',
            'contactLists' => ContactList::paginate(10)
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
            'name' => 'required'
        ], [
            'name.required' => 'Nama harus diisi'
        ]);

        try {
            ContactList::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            $this->logError($request, $e);

            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contactList = ContactList::find($id);

        if (!$contactList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'data' => $contactList,
            'status' => 'success'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => 'Nama harus diisi'
        ]);

        try {
            ContactList::find($id)->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            $this->logError($request, $e);

            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactList  $contactList
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $contactList = ContactList::find($id);
            $contactList->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->logError($id, $e);

            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal dihapus'
            ]);
        }
    }
}
