<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Http\Requests\Admin\SubCategoryRequest;
use App\Http\Requests\Admin\SubCategoryUpdateRequest;
use App\Models\QuestionClass;
use App\Service\LessonClassService;
use App\Service\LessonService;
use App\Service\QuestionService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class FreqCategoryController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        return view('admin.freq.category.index', [
            'categories' => $categories
        ]);
    }

    public function subCategory(Request $request, QuestionClass $category)
    {
        $params = $request->all();
        $sub_categories = QuestionService::getQuesClassFromParentAdmin($category->qc_id);

        return view('admin.freq.sub_category.index', [
            'obj_category' => $category,
            'sub_categories' => $sub_categories
        ]);
    }

    public function newCategory(Request $request)
    {
        $params = $request->all();

        $categories = QuestionService::getQuesClassFromParentAdmin(0);

        return view('admin.freq.category.new_category', [
            'categories' => $categories
        ]);
    }

    public function newSubCategory(Request $request, $category)
    {
        $sub_categories = QuestionService::getQuesClassFromParentAdmin($category);

        return view('admin.freq.sub_category.new_category', [
            'category_id' => $category,
            'categories' => $sub_categories
        ]);
    }

    public function addCategory(CategoryRequest $request)
    {
        if (QuestionService::doCreateQuestionCategory($request->all())) {
            $request->session()->flash('success', 'カテゴリーを追加しました。');
            return redirect()->route('admin.freq.category.index');
        }

        $request->session()->flash('error', 'カテゴリー追加が失敗しました。');
        return back();
    }

    public function addSubCategory(SubCategoryRequest $request)
    {
        $params = $request->all();
        if (QuestionService::doCreateQuestionSubCategory($params)) {
            $request->session()->flash('success', 'サブカテゴリーを追加しました。');
            return redirect()->route('admin.freq.sub_category.index', ['category'=>$params['category_id']]);
        }

        $request->session()->flash('error', 'サブカテゴリー追加が失敗しました。');
        return back();
    }

    public function deleteCategory(Request $request)
    {
        $params = $request->all();

        $categories = QuestionService::getQuesClassFromParentAdmin(0);

        return view('admin.freq.category.delete_category', [
            'categories' => $categories
        ]);
    }

    public function deleteSubCategory(Request $request, $category)
    {
        $sub_categories = QuestionService::getQuesClassFromParentAdmin($category);

        return view('admin.freq.sub_category.delete_category', [
            'category_id' => $category,
            'categories' => $sub_categories
        ]);
    }

    public function destroyCategory(Request $request)
    {
        if (QuestionService::doDestroyQuestionCategory($request->all())) {
            $request->session()->flash('success', 'カテゴリーを削除しました。');
            return redirect()->route('admin.freq.category.index');
        }

        $request->session()->flash('error', 'カテゴリー削除が失敗しました。');
        return back();
    }

    public function destroySubCategory(Request $request)
    {
        $params = $request->all();
        if (QuestionService::doDestroyQuestionSubCategory($params)) {
            $request->session()->flash('success', 'サブカテゴリーを削除しました。');
            return redirect()->route('admin.freq.sub_category.index', ['category'=>$params['category_id']]);
        }

        $request->session()->flash('error', 'サブカテゴリー削除が失敗しました。');
        return back();
    }

    public function publicCategory(Request $request)
    {
        $params = $request->all();

        $categories = QuestionService::getQuesClassFromParentAdmin(0);

        return view('admin.freq.category.public_category', [
            'categories' => $categories
        ]);
    }

    public function publicSubCategory(Request $request, $category)
    {
        $categories = QuestionService::getQuesClassFromParentAdmin($category);

        return view('admin.freq.sub_category.public_category', [
            'category_id' => $category,
            'categories' => $categories
        ]);
    }

    public function setPublicCategory(CategoryUpdateRequest $request)
    {
        if (QuestionService::doUpdateQuestionCategory($request->all())) {
            $request->session()->flash('success', 'カテゴリーを更新しました。');
            return redirect()->route('admin.freq.category.index');
        }

        $request->session()->flash('error', 'カテゴリー更新が失敗しました。');
        return back();
    }

    public function setPublicSubCategory(SubCategoryUpdateRequest $request)
    {
        $params = $request->all();
        if (QuestionService::doUpdateQuestionCategory($params)) {
            $request->session()->flash('success', 'サブカテゴリーを更新しました。');
            return redirect()->route('admin.freq.sub_category.index', ['category'=>$params['category_id']]);
        }

        $request->session()->flash('error', 'サブカテゴリー更新が失敗しました。');
        return back();
    }

    public function sortCategory(Request $request)
    {
        $params = $request->all();

        $categories = QuestionService::getQuesClassFromParentAdmin(0);

        return view('admin.freq.category.sort_category', [
            'categories' => $categories
        ]);
    }

    public function sortSubCategory(Request $request, $category)
    {
        $sub_categories = QuestionService::getQuesClassFromParentAdmin($category);

        return view('admin.freq.sub_category.sort_category', [
            'category_id' => $category,
            'categories' => $sub_categories
        ]);
    }

    public function setSortCategory(Request $request)
    {
        $params = $request->all();
        if (QuestionService::doUpdateQuestionCategorySort($params)) {
            $request->session()->flash('success', 'カテゴリーを更新しました。');
            return redirect()->route('admin.freq.category.index');
        }

        $request->session()->flash('error', 'カテゴリー更新が失敗しました。');
        return back();
    }

    public function setSortSubCategory(Request $request)
    {
        $params = $request->all();
        if (QuestionService::doUpdateQuestionSubCategorySort($params)) {
            $request->session()->flash('success', 'サブカテゴリーを更新しました。');
            return redirect()->route('admin.freq.sub_category.index', ['category'=>$params['category_id']]);
        }

        $request->session()->flash('error', 'サブカテゴリー更新が失敗しました。');
        return back();
    }

    public function getSubCategoryAjax(Request $request)
    {
        $result = [];
        $category_id= $request->input('category_id', 0);
        if ($category_id) {
            $result = QuestionService::getQuesClassFromParentAdmin($category_id);
        }
        return response()->json(
        [
            "result_code" => $category_id ? "success" : "fail",
            "result" => $result
        ]);
    }

}
