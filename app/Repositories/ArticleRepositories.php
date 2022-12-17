<?php

namespace App\Repositories;

use App\Interfaces\ArticleInterface;
use App\Models\Article;

class ArticleRepositories implements ArticleInterface
{
    public function getAllArticles($paginate = null)
    {
        if ($paginate) {
            $paginate = $paginate;
        } else {
            $paginate = 10;
        }

        $data = Article::latest()->with(Article::INCLUDED_RELATION)->paginate($paginate);

        return $data;
    }

    public function createArticle(array $ArticlePayload)
    {
        $storedArticle = Article::create($ArticlePayload);

        if ($storedArticle) {
            return true;
        } else {
            return false;
        }
    }

    public function updateArticle($articleId, array $newDetails)
    {
        $article = Article::find($articleId);

        $articleUpdated = $article->update($newDetails);

        if ($articleUpdated) {
            return true;
        } else {
            return false;
        }
    }

    public function getArticleById($ArticleId)
    {
        $article = Article::find($ArticleId);

        return $article;
    }
}
