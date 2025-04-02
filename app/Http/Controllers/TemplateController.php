<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Exception;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates.index', [
            'page' => 'template',
            'templates' => Template::orderBy('created_at', 'desc')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('templates.create', [
            'page' => 'buat template',
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
            'name' => 'required',
            'category' => 'required',
            'background_color' => 'required',
            'summernote' => 'required',
        ], [
            'name.required' => 'Nama template harus diisi',
            'kategori.required' => 'Kategori harus diisi',
            'background_color.required' => 'Warna latar harus diisi',
            'summernote.required' => 'Template harus diisi',
        ]);

        try {

            $template = new Template();
            $template->name = $request->name;
            $template->category = $request->category;
            $template->background_color = $request->background_color;
            $template->body_template = json_encode($request->summernote);
            $template->save();

            return redirect()->route('templates.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            $this->logError($request, $e);

            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo 'Masih dalam pengembangan';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $template
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decodeId($id);
        $template = Template::findOrFail($id);

        return view('templates.edit', [
            'page' => 'Edit Template',
            'template' => $template
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required',
            'background_color' => 'required',
            'summernote' => 'required',
        ], [
            'name.required' => 'Nama template harus diisi',
            'kategori.required' => 'Kategori harus diisi',
            'background_color.required' => 'Warna latar harus diisi',
            'summernote.required' => 'Template harus diisi',
        ]);

        $id = decodeId($id);
        $template = Template::findOrFail($id);

        try {
            $template->name = $request->name;
            $template->category = $request->category;
            $template->background_color = $request->background_color;
            $template->body_template = json_encode($request->summernote);
            $template->updated_at = now();
            $template->save();

            return redirect()->route('templates.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            $this->logError($request, $e);

            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = decodeId($id);
        $template = Template::findOrFail($id);

        try {
            $template->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (Exception $e) {
            $this->logError($request, $e);

            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal dihapus'
            ]);
        }
    }
}
