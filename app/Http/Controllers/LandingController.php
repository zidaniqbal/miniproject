<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function news()
    {
        return view('landing.news');
    }

    public function getNews(Request $request)
    {
        try {
            $category = $request->query('category', 'nasional');
            $baseUrl = match($category) {
                'nasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional',
                'internasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/internasional',
                'ekonomi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/ekonomi',
                'olahraga' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/olahraga',
                'teknologi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/teknologi',
                'hiburan' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/hiburan',
                default => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional'
            };
            
            $response = Http::get($baseUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                
                $articles = collect($data['data'])->map(function($article) {
                    return [
                        'title' => $article['title'],
                        'description' => $article['contentSnippet'],
                        'url' => $article['link'],
                        'urlToImage' => $article['image']['large'],
                        'publishedAt' => $article['isoDate'],
                    ];
                });

                return response()->json([
                    'success' => true,
                    'articles' => $articles
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch news'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error fetching news: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching news: ' . $e->getMessage()
            ], 500);
        }
    }
}
