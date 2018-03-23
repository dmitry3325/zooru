<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 22.03.18
 * Time: 22:30
 */

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\HtmlPages;
use Illuminate\Support\Facades\View;

class HtmlPageController extends Controller
{
    public function index(HtmlPages $page){
        return View::make('htmlpage.page', ['body' => $page->getMetaData()['body']]);
    }
}