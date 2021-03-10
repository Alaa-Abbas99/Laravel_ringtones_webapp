<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Ringtone;

class Ringtonecontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ringtones = Ringtone::paginate(10);
        return view('ringtone.index', compact('ringtones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('ringtone.create');
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
        $this->validate($request, [
            't' => 'required',
            'description' => 'required',
            'file' => 'required|max:2000',
            'category' => 'required',
        ]);

        $fileName = $request->file->hashName();
        $format = $request->file->getClientOriginalExtension();
        $size = $request->file->getSize();
        $request->file->move(public_path('audio'), $fileName);

        $ringtone = new Ringtone;
        $ringtone->title = $request->get('t');
        $ringtone->slug = Str::slug($request->get('t'));
        $ringtone->description = $request->get('description');
        $ringtone->category_id = $request->get('category');
        $ringtone->format = $format;
        $ringtone->size = $size; 
        $ringtone->file = $fileName;
        $ringtone->save();
        return redirect()->back()->with('message', "Ringtone Created!");


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $ringtone = Ringtone::find($id);
        return view('ringtone.edit', compact('ringtone'));
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
        $this->validate($request, [
            't' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);

        $ringtone = Ringtone::find($id);
        $fileName = $ringtone->file;
        $format = $ringtone->format;
        $size = $ringtone->size;
        $download = $ringtone->download;
        if($request->hasFile('file')){
            $fileName = $request->file->hashName();
            $format = $request->file->getClientOriginalExtension();
            $size = $request->file->getSize();
            $request->file->move(public_path('audio'), $fileName);
            unlink(public_path('/audio/'.$ringtone->file));
            $download = 0;
        }
        $ringtone->title = $request->get('t');
        $ringtone->slug = Str::slug($request->get('t'));
        $ringtone->description = $request->get('description');
        $ringtone->category_id = $request->get('category');
        $ringtone->format = $format;
        $ringtone->size = $size;
        $ringtone->download = $download; 
        $ringtone->file = $fileName;
        $ringtone->save();
        return redirect()->route('ringtones.index')->with('message', "Ringtone Updated!");
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
        $ringtone = Ringtone::find($id);
        $ringtone->delete();
        unlink(public_path('/audio/'.$ringtone->file));
        return redirect()->route('ringtones.index')->with('message', "Ringtone Deleted!");

    }
}
