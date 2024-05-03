<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Library\Qrcode;
use App\Models\DataTour;
use App\Models\PGII;

class PgiiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_pgii(Request $request)
    {
        // $pelanggans = Peserta::latest()->get();
        $pelanggan_pgii = DB::select('CALL sp_datatable_peserta_pgii()');
        return view('peserta_pgii', compact('pelanggan_pgii'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $results = DB::select('CALL sp_generate_nopes_pgii()');
        $generateno = $results[0];
        $peserta = DataTour::all();
        // var_dump($generateno);die;
        return view('tambah_peserta_pgii', compact('generateno', 'peserta'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $pelanggan                                  = new PGII();
        $pelanggan->id_tour                         = $request->id_tour;
        $pelanggan->no_peserta_tour                 = $request->no_peserta_tour;
        $pelanggan->nama_peserta                    = $request->nama_peserta;
        $pelanggan->no_telepon                      = $request->no_telepon;
        $pelanggan->no_peserta_tour                 = $request->no_peserta_tour;
        $pelanggan->kelas                           = $request->kelas;
        $pelanggan->jurusan                         = $request->jurusan;
        $pelanggan->no_bus_kendaraan                = $request->no_bus_kendaraan;
        $pelanggan->save();
         
        return redirect('peserta_pgii');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail_pgii($id)
    {
        $pelanggan = PGII::findOrFail($id);
        $tour = DataTour::findOrFail($id);
        return view('detail_peserta_pgii', compact('pelanggan','tour'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $peserta = PGII::whereId($id)->first();
        return view('ubah_peserta_pgii')->with('peserta', $peserta);
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
        $pelanggan                                  = PGII::find($id);
        $pelanggan->nama_peserta                    = $request->nama_peserta;
        $pelanggan->no_telepon                      = $request->no_telepon;
        $pelanggan->no_peserta_tour                 = $request->no_peserta_tour;
        $pelanggan->kelas                           = $request->kelas;
        $pelanggan->jurusan                         = $request->jurusan;
        $pelanggan->bidang                          = $request->bidang;
        $pelanggan->no_bus_kendaraan                = $request->no_bus_kendaraan;
        $pelanggan->save();
        
        return redirect('pelanggan');
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


    protected $fpdf;
 
    public function __construct()
    {
        $this->fpdf = new Fpdf('P', 'cm', 'A4');
    }

    public function pdf_pgii() 
    {
        $peserta_pgii = PGII::all();
        
        foreach ($peserta_pgii as $value) {
            // var_dump($value);die;

        $this->fpdf->AddPage();
        $qrcode = new QRcode($value['nama_peserta'] . ' ' . $value['kelas'] . ' ' . $value['id'], 'H'); // error level : L, M, Q, H
        // var_dump($value['nama_peserta']);die;

        $this->fpdf->SetFont('helvetica', 'B', 11);
        
        $this->fpdf->Cell(7, 5, '', 0, 0, 'C');
        $this->fpdf->Cell(5, 5, $qrcode->displayFPDF($this->fpdf, $this->fpdf->getX(), $this->fpdf->gety(), 5), 1, 0, 'C');
        $this->fpdf->Ln(3);
        $this->fpdf->SetFont('helvetica', 'B', 10);
        $this->fpdf->Cell(0, 5, $value['nama_peserta'], 0, 0, 'C');
        $this->fpdf->Ln(0.5);
        $this->fpdf->SetFont('helvetica', 'B', 10);
        $this->fpdf->Cell(0, 5, $value['kelas'], 0, 0, 'C');

        }
        $this->fpdf->Output();
        


            exit;
    }
}
