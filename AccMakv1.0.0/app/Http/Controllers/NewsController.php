<?php

namespace App\Http\Controllers;

use App\Models\Forum;

class NewsController extends Controller
{
    public function latest() {
        $last_tickers = Forum::select('players.name', 'z_forum.id AS forumId', 'z_forum.author_guid', 'z_forum.post_text', 'z_forum.post_topic', 'z_forum.post_smile', 'z_forum.replies', 'z_forum.post_date')
        ->join('players', 'players.id', '=', 'z_forum.author_guid')
        ->where('section', 1)
        ->where('post_topic', 'News Ticker')
        ->whereColumn('z_forum.id', 'first_post')
        ->orderBy('last_post', 'DESC')->take(5)->get();

        $last_featuredArticle = Forum::select('players.name', 'z_forum.id AS forumId', 'z_forum.author_guid', 'z_forum.post_text', 'z_forum.post_topic', 'z_forum.post_smile', 'z_forum.replies', 'z_forum.post_date')
        ->join('players', 'players.id', '=', 'z_forum.author_guid')
        ->where('section', 1)
        ->where('post_topic', 'Featured Article')
        ->whereColumn('z_forum.id', 'first_post')
        ->orderBy('last_post', 'DESC')->first();

        $last_threads = Forum::select('players.name', 'z_forum.id AS forumId', 'z_forum.author_guid', 'z_forum.post_text', 'z_forum.post_topic', 'z_forum.post_smile', 'z_forum.replies', 'z_forum.post_date')
        ->join('players', 'players.id', '=', 'z_forum.author_guid')
        ->where('section', 1)
        ->whereNotIn('post_topic', ['News Ticker', 'Featured Article'])
        ->whereColumn('z_forum.id', 'first_post')
        ->orderBy('last_post', 'DESC')->take(config('custom.news_limit'))->get();

        return view('news.latestnews', ['pageTitle' => 'Latest News', 'subtopic' => 'latestnews', 'last_tickers' => $last_tickers, 'last_featuredArticle' => $last_featuredArticle, 'last_threads' => $last_threads]);
    }
}
