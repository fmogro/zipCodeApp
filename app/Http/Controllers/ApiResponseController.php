<?php

namespace ZipCodeApp\Http\Controllers;

use Illuminate\Http\Request;

class ApiResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       // $xmlString = file_get_contents(storage_path() . "/app/public/json/CPdescarga.xml");
        $counter = 0;
        $variable = [];
        $variable[$counter] = new \stdClass();
        $xmlFile = storage_path() . "/app/public/json/CPdescarga.xml";
        $xml = new \XMLReader();
        $xml->open($xmlFile);
        try {
            while ($xml->read()) {
                if ($xml->nodeType == \XMLReader::ELEMENT) {
                    //assuming the values you're looking for are for each "item" element as an example
                    if ($xml->name == 'item') {
                        $variable[++$counter] = new \stdClass();
                        $variable[$counter]->thevalueyouwanttoget = '';
                    }
                    if ($xml->name == 'thevalueyouwanttoget') {
                        $variable[$counter]->thevalueyouwanttoget = $xml->readString();
                    }
                }
            }
        } catch (Exception $e) {
            ....
        } finally() {
        $xml->close();
        }
    
        var_dump($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
