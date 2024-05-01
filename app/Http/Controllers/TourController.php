<?php

namespace App\Http\Controllers;

use App\Models\DataTour;
use App\Models\User;
use Illuminate\Http\Request;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_tours = DataTour::all();
        return view('tour',compact('data_tours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_tours = User::all();
        return view('tambah_tour', compact('data_tours'));   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_tours                                  = new DataTour();
        $data_tours->id_tour_leader                  = $request->id_tour_leader;
        $data_tours->nama_tour                       = $request->nama_tour;
        $data_tours->tgl_berangkat_tour              = $request->tgl_berangkat_tour;
        $data_tours->tgl_selesai_tour                = $request->tgl_selesai_tour;
        $data_tours->destinasi_tour                  = $request->destinasi_tour;
        $data_tours->rombongan_tour                  = $request->rombongan_tour;
        $data_tours->jumlah_peserta_tour             = $request->jumlah_peserta_tour;
        $data_tours->save();
         
        return redirect('tour');
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
