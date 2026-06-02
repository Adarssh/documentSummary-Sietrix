<?php

namespace App\Http\Controllers;

use App\Models\Document as ModelsDocument;
use Dom\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $file = $request->file('document');
        $originalFilename = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();
        $filePath = $file->store('documents');

        $title = $request->input('title');

        $document = ModelsDocument::create([
            'title' => $title ? $title : $originalFilename,
            'original_filename' => $originalFilename,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully and queued for processing.',
            'data' => [
                'document_id' => $document->id,
                'status' => 'pending',
            ],
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $document = ModelsDocument::find($id);
        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $document
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
