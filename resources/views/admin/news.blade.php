@extends('layouts.app')

@section('cssPage')
<style>
    /* Main Content Layout */
    .main-content {
        flex: 1;
        min-height: 100vh;
        padding: 20px;
        transition: all 0.3s;
    }

    .container-fluid {
        padding: 0;
        width: 100%;
    }

    .settings-wrapper {
        background: transparent;
        width: 100%;
        margin: 0;
        padding: 1.5rem;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 0 0.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    /* Filter Section */
    .filter-section {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
        border: 1px solid #edf2f7;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #111827;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* News Card Styles */
    .news-card {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
        border: 1px solid #edf2f7;
        height: 100%;
    }

    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .news-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-radius: 16px 16px 0 0;
    }

    .news-card .card-body {
        padding: 1.5rem;
    }

    .news-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-card .card-text {
        font-size: 0.875rem;
        line-height: 1.5;
        color: #6B7280;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .news-card .card-footer {
        background: none;
        border-top: 1px solid #edf2f7;
        padding: 1rem 1.5rem;
    }

    /* Button Styles */
    .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }

    .view-switcher .btn {
        padding: 0.625rem 1.25rem;
        border-color: #E5E7EB;
        color: #6B7280;
    }

    .view-switcher .btn.active {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
    }

    .view-switcher .btn:hover:not(.active) {
        background-color: #F9FAFB;
        border-color: #E5E7EB;
        color: #4F46E5;
    }

    /* List View Styles */
    .list-view .col-12,
    .list-view .col-md-6,
    .list-view .col-lg-4 {
        width: 100% !important;
        margin-bottom: 1rem;
    }

    .list-view .news-card {
        display: flex;
        flex-direction: row;
        height: auto;
    }

    .list-view .card-img-top {
        width: 300px;
        height: 220px;
        border-radius: 16px 0 0 16px;
    }

    .list-view .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .list-view .card-text {
        -webkit-line-clamp: 2;
        margin-bottom: 1rem;
    }

    .list-view .card-footer {
        margin-top: auto;
        border-top: 1px solid #edf2f7;
    }

    /* Loading Spinner */
    #loadingSpinner {
        padding: 3rem 0;
    }

    .spinner-border {
        color: #4F46E5;
        width: 3rem;
        height: 3rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .settings-wrapper {
            padding: 1rem;
        }

        .filter-section {
            padding: 1rem;
        }

        .list-view .news-card {
            flex-direction: column;
        }

        .list-view .card-img-top {
            width: 100%;
            height: 200px;
            border-radius: 16px 16px 0 0;
        }

        .news-card .card-body {
            padding: 1rem;
        }

        .news-card .card-footer {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title h3 mb-0">Manajemen Berita</h1>
        <button class="btn btn-primary" id="refreshNews">
            <i class="bi bi-arrow-clockwise"></i> Perbarui
        </button>
    </div>

    <!-- News Content Wrapper -->
    <div class="settings-wrapper">
        <!-- Filter Section -->
        <div class="filter-section card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select shadow-none" id="category">
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                            <option value="ekonomi">Ekonomi</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="teknologi">Teknologi</option>
                            <option value="hiburan">Hiburan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 shadow-none" id="search" 
                                   placeholder="Cari berita...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="view-switcher btn-group w-100">
                            <button class="btn btn-outline-primary active" data-view="grid" title="Tampilan Grid">
                                <i class="bi bi-grid"></i>
                            </button>
                            <button class="btn btn-outline-primary" data-view="list" title="Tampilan List">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <div class="row g-4" id="newsContainer">
            <!-- Berita akan dimuat di sini -->
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Memuat berita...</span>
            </div>
            <div class="mt-3 text-muted">Sedang memuat berita...</div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Terjadi Kesalahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    // Setup AJAX untuk mengirim CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function loadNews(category = 'nasional') {
        $('#loadingSpinner').removeClass('d-none');
        $('#newsContainer').empty();

        $.ajax({
            url: `{{ url('/admin/get-news') }}?category=${category}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const newsContainer = $('#newsContainer');
                    newsContainer.empty();
                    
                    response.articles.forEach(article => {
                        const newsCard = `
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card news-card h-100">
                                    <img src="${article.urlToImage}" class="card-img-top" alt="${article.title}" 
                                         onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                                    <div class="card-body">
                                        <h5 class="card-title">${article.title}</h5>
                                        <p class="card-text">${article.description || 'Tidak ada deskripsi'}</p>
                                        <a href="${article.url}" target="_blank" class="btn btn-primary w-100">Baca Selengkapnya</a>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            ${formatDate(article.publishedAt)}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `;
                        newsContainer.append(newsCard);
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading news:', xhr);
                $('#newsContainer').html(`
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal memuat berita. Silakan coba lagi nanti.
                        </div>
                    </div>
                `);
            },
            complete: function() {
                $('#loadingSpinner').addClass('d-none');
            }
        });
    }

    // Load news on page load
    loadNews();

    // Handle category change
    $('#category').change(function() {
        loadNews($(this).val());
    });

    // Search functionality
    $('#search').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        const newsCards = $('#newsContainer > div');
        
        newsCards.each(function() {
            const card = $(this);
            const title = card.find('.card-title').text().toLowerCase();
            const description = card.find('.card-text').text().toLowerCase();
            
            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                card.show();
            } else {
                card.hide();
            }
        });

        // Show "no results" message if all cards are hidden
        if (newsCards.filter(':visible').length === 0) {
            if ($('#noResults').length === 0) {
                $('#newsContainer').append(`
                    <div id="noResults" class="col-12 text-center py-4">
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            Tidak ada berita yang sesuai dengan pencarian "${searchTerm}"
                        </div>
                    </div>
                `);
            }
        } else {
            $('#noResults').remove();
        }
    });

    // Handle refresh button
    $('#refreshNews').click(function() {
        loadNews($('#category').val());
    });

    // View Mode Switcher
    $('.view-switcher button').click(function() {
        $('.view-switcher button').removeClass('active');
        $(this).addClass('active');
        
        const viewMode = $(this).data('view');
        const newsContainer = $('#newsContainer');
        
        if (viewMode === 'list') {
            newsContainer.addClass('list-view');
            newsContainer.find('.col-12, .col-md-6, .col-lg-4').addClass('mb-3');
        } else {
            newsContainer.removeClass('list-view');
            newsContainer.find('.col-12, .col-md-6, .col-lg-4').removeClass('mb-3');
        }
    });

    // Save/Bookmark Feature
    function saveNews(article) {
        const savedNews = JSON.parse(localStorage.getItem('savedNews') || '[]');
        savedNews.push(article);
        localStorage.setItem('savedNews', JSON.stringify(savedNews));
    }

    // Auto-refresh Timer
    let timeLeft = 300; // 5 minutes
    const refreshTimer = setInterval(() => {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        $('#refreshTimer').text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
        
        if (timeLeft <= 0) {
            loadNews($('#category').val());
            timeLeft = 300;
        }
    }, 1000);
});
</script>
@endsection 