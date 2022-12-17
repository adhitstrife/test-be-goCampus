<?php

namespace App\Interfaces;

interface ArticleInterface
{
    public function getAllArticles($paginate);
    public function getArticleById($ArticleId);
    // public function deleteArticle($ArticleId);
    public function createArticle(array $ArticleDetails);
    public function updateArticle($articleId, array $newDetails);
}
