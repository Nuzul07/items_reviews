<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use truncate; 
use App\Barang;
use App\Image;
use Illuminate\Support\Facades\Input;
use Auth;
use App\komentar;

class BarangController extends Controller
{


      public function __construct()
    {
        $this->middleware('auth');
    }

      public function index()
    {
      $data['barang'] = \App\Barang::where('id_user',Auth::user()->id)->paginate(1000);
      $data['category'] = \App\Category::paginate(16);
      return view('barang.list_barang')->with($data);
    }

      public function add()
    {
      $data['category'] = \App\Category::paginate(16);
      return view('barang.add')->with($data);
    }

        public function edit($id)
    {
      $data['barang']=\App\Barang::find($id);
      $data['category']=\App\Category::paginate(16);
      $cek = Barang::orderby('id','desc')->first();
      $data['image']= Image::whereIdBarang($cek->id)->get();
      if (!$data['barang']){ return redirect(url('/barang/list')); }
      if (Auth::user()->id != $data['barang']->id_user){ return redirect(url('/barang/list')); }
      return view('barang.edit')->with($data);

    }

    public function savelagi(Request $request)
    {

      if (Input::hasFile('photo_header')) {
        $files = Input::file('photo_header');
         foreach($files as $sampul) {
        $sampul_header = date("YmdHis").uniqid()."."
        .$sampul->getClientOriginalExtension();
        $sampul->move(storage_path('sampul'), $sampul_header);

       Barang::create([

      'slug' => str_slug(Input::get('nama_barang')),
      'nama_barang' => Input::get('nama_barang'),
      'asal' => Input::get('asal'),
      'penjual' => Input::get('penjual'),
      'desc' => Input::get('desc'),
      'harga' => Input::get('harga'),
      'kondisi' => Input::get('kondisi'),
      'id_user' => Auth::user()->id,
      'photo_header' => $sampul_header


        ]);
      }
}
      if (Input::hasFile('sampul')) {

      $files = Input::file('sampul');
      foreach($files as $sampul) {
        $sampul_cek = date("YmdHis").uniqid()."."
        .$sampul->getClientOriginalExtension();

        $sampul->move(storage_path('sampul'), $sampul_cek);

        $cek = Barang::orderby('id','desc')->first();

        Image::create([
          'id_barang' => $cek->id,
          'lokasi_file' => $sampul_cek,
          'id_user' => Auth::user()->id
          ]);

    }
  }
  return redirect(url('barang/list'));
  }

    public function update()
    {


      $a = \App\Barang::find(Input::get('id'));
      $a->slug = str_slug(Input::get('nama_barang'));
      $a->nama_barang = Input::get('nama_barang');
      $a->asal = Input::get('asal');
      $a->penjual = Input::get('penjual');
      $a->harga = Input::get('harga');
      $a->kondisi = Input::get('kondisi');
      $a->desc = Input::get('desc');
          if (Input::hasFile('sampul')) {
        $files = Input::file('sampul');
         foreach($files as $sampul) {
        $sampul_header = date("YmdHis").uniqid()."."
        .$sampul->getClientOriginalExtension();
        $sampul->move(storage_path('sampul'), $sampul_header);

      $a->photo_header = $sampul_header;
    }
  }
      $a->save();


      if (Input::hasFile('sampul2')) {
          $test = Barang::orderby('id','desc')->first();
          Image::whereIdBarang($test->id)->delete();
      $files = Input::file('sampul2');
      foreach($files as $sampul) {
        $sampul_cek = date("YmdHis").uniqid()."."
        .$sampul->getClientOriginalExtension();

        $sampul->move(storage_path('sampul'), $sampul_cek);
        $cek = Barang::orderby('id','desc')->first();

        Image::create([
          'id_barang' => $cek->id,
          'lokasi_file' => $sampul_cek,
          'id_user' => Auth::user()->id
          ]);

    }
  }
      return redirect(url('barang/list'));
}

      public function delete($id)
    {
      $a = \App\Barang::find($id);
      if (!$a){ return redirect(url('/barang/list')); }
      if (Auth::user()->id != $a->id_user){ return redirect(url('/barang/list')); }
      $cek = Barang::orderby('id','desc')->first();
      Image::whereIdBarang($cek->id)->delete();
      $a->delete();
      return redirect(url('barang/list'));
    }


    public function truncate($id)
    {
      Image::whereIdUser(Auth::user()->id)->delete();
      Barang::whereIdUser(Auth::user()->id)->delete();
      return redirect(url('barang/list'));
    }
    



        public function komentar()
    {
        $a = new \App\komentar;
        $a->isi = Input::get('isi');
        $a->id_artikel = Input::get('id_artikel');
        $a->id_user = Auth::user()->id;
        $a->sampul_user = Auth::user()->sampul;
        $a->save();
        $key = \App\Barang::find(Input::get('id_artikel'));
        return  redirect(url('item-'.$key->id));

    }
    public function hapuskomentar($id)
    {
        $key = Barang::orderby('id','desc')->first();
        komentar::whereIdUser(Auth::user()->id)->delete();
        return  redirect(url('item-'.$key->id));
    }
}
