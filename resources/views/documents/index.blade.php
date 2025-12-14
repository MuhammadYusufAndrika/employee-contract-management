@extends('layouts.bootstrap')

@section('title', 'Document Library')

@section('content')
<div class="doclib-wrapper">
    <div class="doclib-container">
        <!-- Left Sidebar - Filters -->
        <aside class="doclib-sidebar">
            <div class="doclib-filter-header">
                <h3 class="doclib-filter-title">Filter</h3>
                <a href="{{ route('documents.index') }}" class="doclib-btn-reset">
                    <i class="bi bi-trash"></i> Reset
                </a>
            </div>

            <form method="GET" action="{{ route('documents.index') }}" class="doclib-filter-form">
                <!-- Search -->
                <div class="doclib-filter-section">
                    <input type="text" 
                           class="doclib-search-input" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari dokumen...">
                </div>

                <!-- Document Type Filter -->
                <div class="doclib-filter-section">
                    <h4 class="doclib-filter-subtitle">Jenis Dokumen Hukum</h4>
                    <input type="text" 
                           class="doclib-filter-search" 
                           placeholder="Cari Jenis..." 
                           id="doclib-type-search">
                    
                    <div class="doclib-checkbox-list" id="doclib-type-list">
                        @foreach($documentTypes as $type)
                            <label class="doclib-checkbox-item">
                                <input type="checkbox" 
                                       name="document_type" 
                                       value="{{ $type }}"
                                       {{ request('document_type') == $type ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span class="doclib-checkbox-label">{{ $type }}</span>
                                <span class="doclib-checkbox-count">
                                    {{ $documents->where('document_type', $type)->count() }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @if($documentTypes->count() > 5)
                        <button type="button" class="doclib-btn-more" onclick="toggleExpand('type')">
                            <i class="bi bi-chevron-down"></i> Lebih Banyak
                        </button>
                    @endif
                </div>

                <!-- Theme Filter -->
                <div class="doclib-filter-section">
                    <h4 class="doclib-filter-subtitle">Tema</h4>
                    <input type="text" 
                           class="doclib-filter-search" 
                           placeholder="Cari Tema..." 
                           id="doclib-theme-search">
                    
                    <div class="doclib-checkbox-list" id="doclib-theme-list">
                        @foreach($themes as $theme)
                            <label class="doclib-checkbox-item">
                                <input type="checkbox" 
                                       name="theme" 
                                       value="{{ $theme }}"
                                       {{ request('theme') == $theme ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span class="doclib-checkbox-label">{{ $theme }}</span>
                                <span class="doclib-checkbox-count">
                                    {{ $documents->where('theme', $theme)->count() }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @if($themes->count() > 5)
                        <button type="button" class="doclib-btn-more" onclick="toggleExpand('theme')">
                            <i class="bi bi-chevron-down"></i> Lebih Banyak
                        </button>
                    @endif
                </div>
            </form>
        </aside>

        <!-- Right Content - Document List -->
        <main class="doclib-main">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="doclib-header">
                <div class="doclib-results-info">
                    <h2 class="doclib-results-title">Ditemukan {{ $documents->count() }} hasil</h2>
                </div>
                <div class="doclib-sort">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('documents.create') }}" class="doclib-btn-create">
                            <i class="bi bi-plus-circle"></i> Add Document
                        </a>
                    @endif
                </div>
            </div>

            <!-- Document Cards -->
            <div class="doclib-content">
                @forelse($documents as $document)
                    <div class="doclib-card">
                        <div class="doclib-card-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="doclib-card-content">
                            <div class="doclib-card-header-with-actions">
                                <a href="{{ route('documents.show', $document) }}" 
                                   target="_blank" 
                                   class="doclib-card-title">
                                    @if($document->document_type)
                                        <span class="doclib-card-category">{{ strtoupper($document->document_type) }}</span>
                                    @endif
                                    {{ $document->title }}
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <div class="doclib-card-actions">
                                        <a href="{{ route('documents.edit', $document) }}" 
                                           class="doclib-btn-action doclib-btn-edit" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('documents.destroy', $document) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this document?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="doclib-btn-action doclib-btn-delete" 
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            @if($document->is_active)
                                <span class="doclib-badge-active">
                                    <i class="bi bi-check-circle"></i> Berlaku
                                </span>
                            @endif

                            @if($document->description)
                                <p class="doclib-card-description">{{ $document->description }}</p>
                            @endif

                            <div class="doclib-card-meta">
                                @if($document->enacted_date)
                                    <div class="doclib-meta-item">
                                        <i class="bi bi-pencil"></i>
                                        Ditetapkan: {{ $document->enacted_date->format('d F Y') }}
                                    </div>
                                @endif
                                @if($document->published_date)
                                    <div class="doclib-meta-item">
                                        <i class="bi bi-calendar3"></i>
                                        Diundangkan: {{ $document->published_date->format('d F Y') }}
                                    </div>
                                @endif
                                <div class="doclib-meta-item">
                                    <i class="bi bi-download"></i>
                                    Diunduh: {{ $document->download_count }} kali
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="doclib-empty">
                        <i class="bi bi-inbox"></i>
                        <p>Tidak ada dokumen ditemukan</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>

<style>
/* Document Library Modern Redesign */
.doclib-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 30px 0;
}

.doclib-container {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 30px;
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 30px;
}

/* Sidebar Modern Styles */
.doclib-sidebar {
    background: linear-gradient(180deg, #003DA5 0%, #002060 100%);
    border-radius: 16px;
    padding: 25px;
    height: fit-content;
    position: sticky;
    top: 30px;
    box-shadow: 0 10px 40px rgba(0, 61, 165, 0.3);
}

.doclib-filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.15);
}

.doclib-filter-title {
    color: white;
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.5px;
}

.doclib-btn-reset {
    background: #FF6B00;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
}

.doclib-btn-reset:hover {
    background: #e55d00;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 107, 0, 0.4);
}

.doclib-filter-section {
    margin-bottom: 28px;
}

.doclib-search-input,
.doclib-filter-search {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.doclib-search-input:focus,
.doclib-filter-search:focus {
    outline: none;
    border-color: #FF6B00;
    background: rgba(255, 255, 255, 0.2);
}

.doclib-search-input::placeholder,
.doclib-filter-search::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.doclib-filter-subtitle {
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.doclib-checkbox-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: 12px;
    max-height: 300px;
    overflow-y: auto;
}

.doclib-checkbox-list::-webkit-scrollbar {
    width: 6px;
}

.doclib-checkbox-list::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

.doclib-checkbox-list::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
}

.doclib-checkbox-item {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: rgba(255, 255, 255, 0.05);
}

.doclib-checkbox-item:hover {
    background: rgba(255, 107, 0, 0.2);
    transform: translateX(3px);
}

.doclib-checkbox-item input[type="checkbox"] {
    margin-right: 12px;
    cursor: pointer;
    width: 18px;
    height: 18px;
    accent-color: #FF6B00;
}

.doclib-checkbox-label {
    flex: 1;
    color: rgba(255, 255, 255, 0.95);
    font-size: 0.9rem;
    font-weight: 500;
}

.doclib-checkbox-count {
    background: rgba(255, 107, 0, 0.8);
    color: white;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 700;
}

.doclib-btn-more {
    width: 100%;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    color: #FF6B00;
    border: 2px solid rgba(255, 107, 0, 0.3);
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 10px;
    transition: all 0.3s ease;
}

.doclib-btn-more:hover {
    background: rgba(255, 107, 0, 0.2);
    border-color: #FF6B00;
}

/* Main Content Modern Styles */
.doclib-main {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.doclib-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 3px solid #f0f0f0;
}

.doclib-results-title {
    color: #002060;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.5px;
}

.doclib-results-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 5px 0 0 0;
}

.doclib-btn-create {
    background: linear-gradient(135deg, #FF6B00 0%, #e55d00 100%);
    color: white;
    padding: 14px 28px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(255, 107, 0, 0.3);
}

.doclib-btn-create:hover {
    background: linear-gradient(135deg, #e55d00 0%, #cc5200 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 107, 0, 0.4);
}

/* Document Cards Grid */
.doclib-content {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
    gap: 25px;
}

.doclib-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 14px;
    padding: 25px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.doclib-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(180deg, #003DA5 0%, #FF6B00 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.doclib-card:hover {
    border-color: #003DA5;
    box-shadow: 0 10px 40px rgba(0, 61, 165, 0.15);
    transform: translateY(-5px);
}

.doclib-card:hover::before {
    opacity: 1;
}

.doclib-card-icon {
    display: none;
}

.doclib-card-content {
    width: 100%;
}

.doclib-card-header-with-actions {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}

.doclib-card-title {
    color: #002060;
    font-size: 1.15rem;
    font-weight: 700;
    text-decoration: none;
    display: block;
    line-height: 1.5;
    flex: 1;
    transition: color 0.3s ease;
}

.doclib-card-title:hover {
    color: #003DA5;
}

.doclib-card-category {
    color: #FF6B00;
    font-weight: 800;
    display: inline-block;
    font-size: 0.75rem;
    margin-bottom: 8px;
    padding: 4px 12px;
    background: rgba(255, 107, 0, 0.1);
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.doclib-badge-active {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.doclib-card-description {
    color: #495057;
    font-size: 0.95rem;
    margin: 12px 0;
    line-height: 1.6;
}

.doclib-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid #e9ecef;
}

.doclib-meta-item {
    color: #6c757d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 500;
}

.doclib-meta-item i {
    font-size: 1rem;
    color: #003DA5;
}

/* Card Actions */
.doclib-card-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.doclib-btn-action {
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.doclib-btn-edit {
    color: #003DA5;
    border-color: #003DA5;
}

.doclib-btn-edit:hover {
    background: #003DA5;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 61, 165, 0.3);
}

.doclib-btn-delete {
    color: #dc3545;
    border-color: #dc3545;
}

.doclib-btn-delete:hover {
    background: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
}

/* Empty State */
.doclib-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    color: #adb5bd;
}

.doclib-empty i {
    font-size: 5rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.doclib-empty p {
    font-size: 1.2rem;
    font-weight: 600;
}

/* Alert Styling */
.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border: 2px solid #10b981;
    color: #065f46;
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 25px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 1200px) {
    .doclib-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 992px) {
    .doclib-container {
        grid-template-columns: 1fr;
    }

    .doclib-sidebar {
        position: static;
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .doclib-wrapper {
        padding: 15px 0;
    }

    .doclib-container {
        padding: 0 15px;
        gap: 20px;
    }

    .doclib-main {
        padding: 25px 20px;
    }

    .doclib-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .doclib-btn-create {
        width: 100%;
        justify-content: center;
    }

    .doclib-card {
        padding: 20px;
    }

    .doclib-card-meta {
        flex-direction: column;
        gap: 12px;
    }

    .doclib-card-header-with-actions {
        flex-direction: column;
    }

    .doclib-card-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<script>
function toggleExpand(type) {
    const list = document.getElementById(`doclib-${type}-list`);
    list.classList.toggle('expanded');
}

// Search within filters
document.getElementById('doclib-type-search')?.addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('#doclib-type-list .doclib-checkbox-item').forEach(item => {
        const text = item.querySelector('.doclib-checkbox-label').textContent.toLowerCase();
        item.style.display = text.includes(search) ? 'flex' : 'none';
    });
});

document.getElementById('doclib-theme-search')?.addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('#doclib-theme-list .doclib-checkbox-item').forEach(item => {
        const text = item.querySelector('.doclib-checkbox-label').textContent.toLowerCase();
        item.style.display = text.includes(search) ? 'flex' : 'none';
    });
});
</script>
@endsection
