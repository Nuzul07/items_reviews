<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

class DetailController extends Controller
{


    public function detail($id)
  {
  	$data['barang'] = \App\Barang::whereSlug($id)->first();
    $data['komentar']=\App\komentar::whereIdArtikel($data['barang']->id)->get();
    return view('barang.detail')->with($data);
 }

         public function search(Request $request)
    {
      $search = $request->get('q');
      $hasil =  \App\Barang::where('nama_barang', 'LIKE', '%' . $search . '%')->paginate();
      return view('barang/result', compact('hasil', 'search'));  
    }


    public function all()
    {
      $data['barang'] = \App\Barang::paginate(16);
      return view('barang.all')->with($data);
    }


}