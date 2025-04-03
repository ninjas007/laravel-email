<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactList;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contacts.index', [
            'page' => 'kontak',
            'contactList' => ContactList::all(),
            'contacts' => Contact::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create', [
            'page' => 'Tambah Kontak',
            'contactList' => ContactList::all(),
            'fields' => Field::all()
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
            'contact_list' => 'required|array|min:1',
            'contact_list.*' => 'integer',
            'name' => 'required|string',
            'email' => 'required|email'
        ], [
            'contact_list.required' => 'Kontak harus terdaftar dalam setidaknya satu daftar',
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
        ]);

        if ($request->custom_fields != null && count($request->custom_fields['name']) > 0) {
            $customFields = [];
            for ($i = 0; $i < count($request->custom_fields['name']); $i++) {
                $customFields[$request->custom_fields['name'][$i]] = $request->custom_fields['value'][$i];
            }
        }

        try {
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone ?? '';
            $contact->contact_list_id = json_encode($request->contact_list);
            $contact->custom_fields = isset($customFields) ? json_encode($customFields) : null;
            $contact->save();

            return redirect()->route('contacts.index')->with('success', 'Kontak berhasil dibuat');
        } catch (\Exception $e) {
            $this->logError($request, $e);
            return redirect()->back()->with('error', 'Server error. Silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = decodeId($id);
        $contact = Contact::find($id);

        if (request()->ajax()) {
            if (!$contact) {
                return response()->json(['error' => 'Kontak tidak ditemukan'], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $contact,
            ]);
        }

        return view('contacts.show', [
            'page' => 'Detail Kontak',
            'contact' => $contact
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $id = decodeId($id);
        $contact = Contact::findOrFail($id);

        return view('contacts.edit', [
            'page' => 'Edit Kontak',
            'contact' => $contact,
            'contactList' => ContactList::all(),
            'fields' => Field::all()
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
            'contact_list' => 'required|array|min:1',
            'contact_list.*' => 'integer',
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        try {
            $id = decodeId($id);
            $contact = Contact::findOrFail($id);

            if (!$contact) {
                return redirect()->back()->with('error', 'Kontak tidak ditemukan');
            }

            if ($request->custom_fields != null && count($request->custom_fields['name']) > 0) {
                $customFields = [];
                for ($i = 0; $i < count($request->custom_fields['name']); $i++) {
                    $customFields[$request->custom_fields['name'][$i]] = $request->custom_fields['value'][$i];
                }
            }

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone ?? '';
            $contact->contact_list_id = json_encode($request->contact_list);
            $contact->custom_fields = isset($customFields) ? json_encode($customFields) : null;
            $contact->save();

            return redirect()->route('contacts.index')->with('success', 'Kontak berhasil diperbarui');
        } catch (\Exception $e) {
            $this->logError($request, $e);
            return redirect()->back()->with('error', 'Server error. Silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = decodeId($id);
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['error' => 'Kontak tidak ditemukan'], 404);
        }

        $contact->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menghapus kontak',
        ]);
    }

    /**
     * Handle the upload of a contact list file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|mimes:csv,txt'
        ]);

        if (!$request->hasFile('file_upload')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file_upload');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }

        // Simpan file secara manual ke storage/app/contacts/
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = storage_path('app/contacts/' . $fileName);
        $file->move(storage_path('app/contacts/'), $fileName);

        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded successfully.',
            'file_path' => 'contacts/' . $fileName,
        ]);
    }


    /**
     * Process a batch of contact data from a CSV file and insert it into the database.
     *
     * This function validates the incoming request, reads a CSV file starting from the given offset,
     * and processes each row into a contact record. It handles processing in batches, inserting records
     * into the database, and deleting the file once processing is complete.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing:
     *                                          - 'file_path': Path to the CSV file.
     *                                          - 'offset': Starting position in the file for processing.
     *                                          - 'contact_list': Array of contact list IDs.
     * @return \Illuminate\Http\Response JSON response containing:
     *                                   - 'status': Success or error status.
     *                                   - 'inserted': Number of records inserted.
     *                                   - 'next_offset': Offset for the next batch or null if completed.
     *                                   - 'message': Message indicating the outcome.
     */
    public function processBatch(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'offset' => 'required|integer',
            'contact_list' => 'required|array',
            'contact_list.*' => 'integer',
        ]);

        $filePath = storage_path('app/' . $request->file_path);
        $offset = $request->offset;
        $batchSize = 100;
        $insertedCount = 0;

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Buka file dalam mode baca
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return response()->json(['error' => 'Failed to open file'], 500);
        }

        $dataBatch = [];
        $isHeader = true;
        $currentRow = 0;
        $headers = [];
        $customFields = [];
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($isHeader) {
                $headers = explode(';', $row[0]);
                $isHeader = false;
                continue; // Lewati baris header
            }

            // pastikan posisi header sesuai.
            // kalau tidak sesuai maka return error
            // if (!in_array($headers, ['No', 'Nama', 'Email', 'No Hp'])) {

            // }


            if ($currentRow < $offset) {
                $currentRow++;
                continue;
            }

            // Pastikan jumlah kolom sesuai
            // if (count($row) !== 3) {
            //     continue;
            // }

            $data = explode(';', $row[0]);

            $customFields = [];

            // jika data ke 4 dan seterusnya tidak kosong
            for ($i = 4; isset($data[$i]); $i++) {
                $customFields[$headers[$i]] = $data[$i];
            }


            $dataBatch[] = [
                'name'   => trim($data[1]),
                'email'  => trim($data[2]),
                'phone'  => trim($data[3] ?? ''),
                'custom_fields' => json_encode($customFields),
                'contact_list_id' => json_encode($request->contact_list),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $insertedCount++;
            $currentRow++;

            // Hentikan jika batch penuh
            if (count($dataBatch) >= $batchSize) {
                break;
            }
        }

        // Insert batch ke database
        if (!empty($dataBatch)) {
            DB::table('contacts')->insert($dataBatch);
        }

        // Cek apakah masih ada data di file
        $isFinished = feof($handle);

        // Tutup file setelah selesai membaca
        fclose($handle);

        // Hapus file setelah selesai diproses
        if ($isFinished) {
            unlink($filePath);
        }

        return response()->json([
            'status' => 'success',
            'inserted' => $insertedCount,
            'next_offset' => $isFinished ? null : $offset + $batchSize,
            'message' => "Inserted {$insertedCount} records.",
        ]);
    }
}
