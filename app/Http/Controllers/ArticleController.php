<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Interfaces\ArticleInterface;
use App\Models\Article;
use App\Repositories\ArticleRepositories;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    /**
     * The user repository implementation.
     *
     * @var ArticleRepository
     */
    protected $articles;


    /**
     * Create a new controller instance.
     *
     * @param  ArticleRepository  $article
     * @return void
     */

    public function __construct(ArticleInterface $articleRepositories, FileService $fileService)
    {
        $this->articleRepository = $articleRepositories;
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->articleRepository->getAllArticles(10);
        return view('articles.index', ['articles' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $imageName = $this->fileService->uploadFile($request->image);

        if ($imageName) {
            $data = $request->only([
                'title',
                'content'
            ]);

            $data['article_image'] = $imageName;
            $data['user_id'] = Auth::user()->id;

            $isArticleStored = $this->articleRepository->createArticle($data);

            if ($isArticleStored) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Article Stored Successfully'
                ], 200);
            } else {
                $filePath = 'article_images/' . $imageName;
                $this->fileService->deleteFile($filePath);

                return response()->json([
                    'status' => 500,
                    'message' => 'Article Failed To Store'
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed To Upload Image'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->only([
            'title',
            'content'
        ]);

        $data['user_id'] = Auth::user()->id;

        if ($request->hasFile('image')) {
            $imageName = $this->fileService->uploadFile($request->image);
            if ($imageName) {
                $oldImageName = $article->article_image;

                $data['article_image'] = $imageName;

                $isArticleStored = $this->articleRepository->updateArticle($article->id, $data);

                if ($isArticleStored) {
                    $filePath = 'article_images/' . $oldImageName;
                    $this->fileService->deleteFile($filePath);

                    return response()->json([
                        'status' => 200,
                        'message' => 'Article Stored Successfully'
                    ], 200);
                } else {
                    $filePath = 'article_images/' . $imageName;
                    $this->fileService->deleteFile($filePath);

                    return response()->json([
                        'status' => 500,
                        'message' => 'Article Failed To Store'
                    ], 500);
                }
            }
        } else {
            $isArticleStored = $this->articleRepository->updateArticle($article->id, $data);

            if ($isArticleStored) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Article Updated Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Article Failed To Update'
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $articleId = $article->id;
        $articleImage = $article->article_image;

        $article->delete();

        $isArticleStillExist = $this->articleRepository->getArticleById($articleId);

        if (!$isArticleStillExist) {
            $filePath = 'article_images/' . $articleImage;
            $this->fileService->deleteFile($filePath);

            return response()->json([
                'status' => 200,
                'message' => 'Article Deleted Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Article Failed To Delete'
            ], 500);
        }
    }
}
