@extends('layouts.landing')

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
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .news-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }

    .news-card .card-body {
        padding: 1.5rem;
    }

    .news-card .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.75rem;
    }

    .news-card .card-text {
        color: #4B5563;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .news-card .card-footer {
        background: transparent;
        border-top: 1px solid #edf2f7;
        padding: 1rem 1.5rem;
    }

    /* List View Styles */
    .list-view .news-card {
        display: flex;
        flex-direction: row;
        height: auto;
    }

    .list-view .card-img-top {
        width: 200px;
        height: 100%;
        border-top-right-radius: 0;
        border-bottom-left-radius: 16px;
    }

    .list-view .card-body {
        flex: 1;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .list-view .news-card {
            flex-direction: column;
        }

        .list-view .card-img-top {
            width: 100%;
            height: 200px;
            border-bottom-left-radius: 0;
            border-top-right-radius: 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <h1 class="display-4 fw-bold mb-4">Latest News</h1>
            <p class="lead mb-5">Stay updated with the latest news from various categories.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Filter Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category">
                                <option value="nasional">Nasional</option>
                                <option value="internasional">Internasional</option>
                                <option value="ekonomi">Ekonomi</option>
                                <option value="olahraga">Olahraga</option>
                                <option value="teknologi">Teknologi</option>
                                <option value="hiburan">Hiburan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="search" placeholder="Search news...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="view-switcher btn-group w-100">
                                <button class="btn btn-outline-primary active" data-view="grid">
                                    <i class="bi bi-grid"></i>
                                </button>
                                <button class="btn btn-outline-primary" data-view="list">
                                    <i class="bi bi-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Container -->
            <div class="row g-4" id="newsContainer">
                <!-- News will be loaded here -->
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-3 text-muted">Loading news...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    let currentCategory = 'nasional';

    function loadNews(category) {
        $('#loadingSpinner').removeClass('d-none');
        const newsContainer = $('#newsContainer');
        newsContainer.empty();

        fetch(`{{ route('landing.get-news') }}?category=${category}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.articles.forEach(article => {
                        const card = `
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="news-card card">
                                    <img src="${article.urlToImage || '/images/no-image.jpg'}" 
                                         class="card-img-top" 
                                         alt="${article.title}"
                                         onerror="this.src='/images/no-image.jpg'">
                                    <div class="card-body">
                                        <h5 class="card-title">${article.title}</h5>
                                        <p class="card-text">${article.description || ''}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-muted">
                                                ${new Date(article.publishedAt).toLocaleDateString('id-ID')}
                                            </small>
                                            <a href="${article.url}" target="_blank" 
                                               class="btn btn-primary btn-sm">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        newsContainer.append(card);
                    });
                } else {
                    newsContainer.html(`
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load news
                            </div>
                        </div>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                newsContainer.html(`
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Failed to load news. Please try again later.
                        </div>
                    </div>
                `);
            })
            .finally(() => {
                $('#loadingSpinner').addClass('d-none');
            });
    }

    // Initial load
    loadNews(currentCategory);

    // Event handlers
    $('#category').change(function() {
        currentCategory = $(this).val();
        loadNews(currentCategory);
    });

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

        if (newsCards.filter(':visible').length === 0) {
            if ($('#noResults').length === 0) {
                $('#newsContainer').append(`
                    <div id="noResults" class="col-12 text-center py-4">
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            No news found matching "${searchTerm}"
                        </div>
                    </div>
                `);
            }
        } else {
            $('#noResults').remove();
        }
    });

    $('.view-switcher button').click(function() {
        $('.view-switcher button').removeClass('active');
        $(this).addClass('active');
        
        const viewMode = $(this).data('view');
        const newsContainer = $('#newsContainer');
        
        if (viewMode === 'list') {
            newsContainer.addClass('list-view');
        } else {
            newsContainer.removeClass('list-view');
        }
    });

    // Auto refresh every 5 minutes
    setInterval(() => {
        loadNews(currentCategory);
    }, 300000);
});
</script>
@endsection 