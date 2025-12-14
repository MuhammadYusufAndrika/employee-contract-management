<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::query()->where('is_active', true);

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by theme
        if ($request->filled('theme')) {
            $query->where('theme', $request->theme);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->orderBy('published_date', 'desc')->get();

        // Get unique document types and themes for filters
        $documentTypes = Document::where('is_active', true)->distinct()->pluck('document_type')->filter();
        $themes = Document::where('is_active', true)->distinct()->pluck('theme')->filter();

        return view('documents.index', compact('documents', 'documentTypes', 'themes'));
    }

    
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can add documents.');
        }

        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can add documents.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:100',
            'document_type' => 'required|string|max:100',
            'theme' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'enacted_date' => 'nullable|date',
            'published_date' => 'nullable|date',
            'file_pdf' => 'required|file|mimes:pdf|max:10240',
            'is_active' => 'boolean'
        ]);

        // Handle file upload
        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('documents', $filename, 'public');
            $validated['file_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['download_count'] = 0;

        Document::create($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Document has been added successfully!');
    }

    /**
     * Display the specified resource (open PDF).
     */
    public function show(Document $document)
    {
        // Increment download count
        $document->incrementDownloadCount();

        // Return PDF file
        $filePath = storage_path('app/public/' . $document->file_path);

        if (file_exists($filePath)) {
            return response()->file($filePath);
        }

        abort(404, 'Document not found');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can edit documents.');
        }

        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can update documents.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:100',
            'document_type' => 'required|string|max:100',
            'theme' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'enacted_date' => 'nullable|date',
            'published_date' => 'nullable|date',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'is_active' => 'boolean'
        ]);

        // Handle file upload if new file provided
        if ($request->hasFile('file_pdf')) {
            // Delete old file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file_pdf');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('documents', $filename, 'public');
            $validated['file_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $document->update($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Document has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete documents.');
        }

        // Delete file from storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document has been deleted successfully!');
    }
}
