<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\Services\CacheArticleID;

class MainController extends AbstractController
{
    public function main(): void
    {
        $lastID = Article::getLastID();
        if ($lastID === null) {
            throw new NotFoundException();
        }
        $this->after($lastID + 1);
    }

    public function before(int $id)
    {
        $this->page(Article::getPageBefore($id, 5));
    }

    public function after(int $id)
    {
        $this->page(Article::getPageAfter($id, 5));
    }

    public function page(array $articles)
    {
        if ($articles === []) {
            throw new NotFoundException();
        }

        $firstID = $articles[0]->getId();
        $lastID = $articles[count($articles)-1]->getId();

        $this->view->renderHtml('main/main.php',[
            'articles' => $articles,
            'previousPageLink' => $firstID < CacheArticleID::getLastArticleID() ? '/before/' . $firstID : null,
            'nextPageLink' => Article::hasNextPage($lastID) ? '/after/' . $lastID : null,
        ]);
    }
}