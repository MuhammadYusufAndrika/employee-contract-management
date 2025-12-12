@extends('layouts.bootstrap')

@section('title', 'Edit Document')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-pencil-square"></i> Edit Document</h2>
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Library
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Document Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $document->title) }}" 
                                   placeholder="e.g., Undang-Undang Nomor 13 Tahun 2003"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document_number" class="form-label">Document Number</label>
                            <input type="text" 
                                   class="form-control @error('document_number') is-invalid @enderror" 
                                   id="document_number" 
                                   name="document_number" 
                                   value="{{ old('document_number', $document->document_number) }}" 
                                   placeholder="e.g., UU No. 13 Tahun 2003">
                            @error('document_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('document_type') is-invalid @enderror" 
                                       id="document_type" 
                                       name="document_type" 
                                       value="{{ old('document_type', $document->document_type) }}" 
                                       placeholder="e.g., Undang-undang, Perppu, Peraturan Pemerintah"
                                       required>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="theme" class="form-label">Theme</label>
                                <input type="text" 
                                       class="form-control @error('theme') is-invalid @enderror" 
                                       id="theme" 
                                       name="theme" 
                                       value="{{ old('theme', $document->theme) }}" 
                                       placeholder="e.g., Ketenagakerjaan">
                                @error('theme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Brief description of the document">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="enacted_date" class="form-label">Enacted Date (Tanggal Ditetapkan)</label>
                                <input type="date" 
                                       class="form-control @error('enacted_date') is-invalid @enderror" 
                                       id="enacted_date" 
                                       name="enacted_date" 
                                       value="{{ old('enacted_date', $document->enacted_date?->format('Y-m-d')) }}">
                                @error('enacted_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="published_date" class="form-label">Published Date (Tanggal Diundangkan)</label>
                                <input type="date" 
                                       class="form-control @error('published_date') is-invalid @enderror" 
                                       id="published_date" 
                                       name="published_date" 
                                       value="{{ old('published_date', $document->published_date?->format('Y-m-d')) }}">
                                @error('published_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="file_pdf" class="form-label">PDF File</label>
                            <input type="file" 
                                   class="form-control @error('file_pdf') is-invalid @enderror" 
                                   id="file_pdf" 
                                   name="file_pdf" 
                                   accept=".pdf">
                            <small class="form-text text-muted">
                                Leave empty to keep current file. Upload new PDF file to replace (max 10MB).
                                <br>
                                Current file: <strong>{{ basename($document->file_path) }}</strong>
                            </small>
                            @error('file_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $document->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Document is Active (Berlaku)
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
