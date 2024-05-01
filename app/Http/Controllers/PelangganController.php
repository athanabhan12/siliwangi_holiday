<?php

namespace App\Http\Controllers;

use App\Imports\ImportPesertaTourClass;
use App\Imports\ImportPeserta_PgiiClass;
use App\Models\DataTour;
use App\Models\Peserta;
// use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Library\Qrcode;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use Endroid\QrCode\Qrcode;
use Endroid\QrCode\Writer\PngWriter;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $pelanggans = Peserta::latest()->get();
        $pelanggans = DB::select('CALL sp_datatable_peserta_tour()');
        return view('pelanggan', compact('pelanggans'));
    }
    
    public function cetak_tiket()
    {
        return view('cetak_tiket');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $results = DB::select('CALL sp_generate_nopes()');
        $generateno = $results[0];
        $peserta = DataTour::all();
        // var_dump($generateno);die;
        return view('tambah_pelanggan', compact('generateno', 'peserta'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pelanggan = ('id');
        
        $pelanggan                                  = new Peserta;
        $pelanggan->id_tour                         = $request->id_tour;
        $pelanggan->no_peserta_tour                 = $request->no_peserta_tour;
        $pelanggan->nama_peserta                    = $request->nama_peserta;
        $pelanggan->no_telepon                      = $request->no_telepon;
        $pelanggan->no_peserta_tour                 = $request->no_peserta_tour;
        $pelanggan->kelas                           = $request->kelas;
        $pelanggan->jurusan                         = $request->jurusan;
        $pelanggan->no_bus_kendaraan                = $request->no_bus_kendaraan;
        $pelanggan->save();
         
        return redirect('pelanggan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $pelanggan = Peserta::findOrFail($id);
        $tour = DataTour::findOrFail($id);
        return view('detail_pelanggan', compact('pelanggan','tour'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $peserta = Peserta::whereId($id)->first();
        return view('ubah_pelanggan')->with('peserta', $peserta);
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
        $pelanggan                                  = Peserta::find($id);
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
    public function delete($id)
    {
        $peserta = Peserta::find($id);
        $peserta->delete();
        return redirect('pelanggan');
    }

    public function import(Request $request) 
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);
 
		// menangkap file excel
		$file = $request->file('file');
 
		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();
 
		// upload ke folder file_siswa di dalam folder public
		$file->move('file_peserta',$nama_file);
 
		// import data
		Excel::import(new ImportPesertaTourClass, public_path('/file_peserta/'.$nama_file));
 
		// alihkan halaman kembali
		return redirect('/pelanggan');
	}
    public function import_pgii(Request $request) 
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);
 
		// menangkap file excel
		$file = $request->file('file');
 
		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();
 
		// upload ke folder file_siswa di dalam folder public
		$file->move('file_peserta',$nama_file);
 
		// import data
		Excel::import(new ImportPeserta_PgiiClass, public_path('/file_peserta/'.$nama_file));
 
		// alihkan halaman kembali
		return redirect('/peserta_pgii');
	}


    protected $fpdf;
 
    public function __construct()
    {
        $this->fpdf = new Fpdf('P', 'cm', 'A4');
    }

    public function pdf() 
    {
        $peserta = Peserta::all();
        
        foreach ($peserta as $value) {
            // var_dump($value);die;

        $this->fpdf->AddPage();
        $qrcode = new QRcode($value['nama_peserta'] . ' ' . $value['kelas'], 'H'); // error level : L, M, Q, H
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
