<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactList;
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

        try {
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone ?? '';
            $contact->contact_list_id = json_encode($request->contact_list);
            $contact->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil membuat kontak',
                'data' => $contact,
            ]);
        } catch (\Exception $e) {
            $this->logError($request, $e);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat kontak',
                'error' => $e->getMessage(),
            ], 500);
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

        if (!$contact) {
            return response()->json(['error' => 'Kontak tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $contact,
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
            $contact = Contact::find($id);

            if (!$contact) {
                return response()->json(['error' => 'Kontak tidak ditemukan'], 404);
            }

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone ?? '';
            $contact->contact_list_id = json_encode($request->contact_list);
            $contact->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memperbarui kontak'
            ]);
        } catch (\Exception $e) {
            $this->logError($request, $e);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui kontak',
                'error' => $e->getMessage(),
            ], 500);
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

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($isHeader) {
                $isHeader = false;
                continue; // Lewati baris header
            }

            if ($currentRow < $offset) {
                $currentRow++;
                continue;
            }

            // Pastikan jumlah kolom sesuai
            // if (count($row) !== 3) {
            //     continue;
            // }

            $data = explode(';', $row[0]);

            $dataBatch[] = [
                'name'   => trim($data[1]),
                'email'  => trim($data[2]),
                'phone'  => trim($data[3] ?? ''),
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
