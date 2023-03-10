<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $articleQuery = Article::query();

        if ($request->has('search')) {
            $validated = $this->validate($request, [
                'search' => 'required|nullable|string'
            ]);

            $articleQuery->where(function ($query) use ($validated) {
                $query->where('title', 'like', '%' . $validated['search'] . '%')
                    ->orWhere('title_en', 'like', '%' . $validated['search'] . '%')
                    ->orWhere('slug', 'like', '%' . $validated['search'] . '%')
                    ->orWhere('content', 'like', '%' . $validated['search'] . '%');
            });
        }

        if ($request->has('tags')) {
//            $validated = $this->validate($request, [
//                'tags.*' => 'required|numeric|exists:App\Models'
//            ])
        }

        if ($request->has('paginate')) {
            return $articleQuery->paginate(20);
        }

        return $articleQuery->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
