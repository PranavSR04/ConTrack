<?php

namespace App\Http\Controllers;

use App\Models\Addendums;
use Illuminate\Http\Request;

class AddendumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store generated data
     */
    public function generateData(){
        $addendumData = [
            [
                "contract_id"=>1,
                "addendum_doclink"=>"https://example.com/document1"
            ],
            [
                "contract_id"=>1,
                "addendum_doclink"=>"https://example.com/document12"
            ],
            [
                "contract_id"=>1,
                "addendum_doclink"=>"https://example.com/document13"
            ],
            [
                "contract_id"=>2,
                "addendum_doclink"=>"https://example.com/document20"
            ],
            [
                "contract_id"=>2,
                "addendum_doclink"=>"https://example.com/document21"
            ],
            [
                "contract_id"=>3,
                "addendum_doclink"=>"https://example.com/document30"
            ]
        ];
        foreach ($addendumData as $data) {
            Addendums::create($data);
        }        

        return "Data inserted successfully for Addendum table";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
