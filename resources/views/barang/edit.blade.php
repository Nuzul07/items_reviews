@extends('app')

    @section('header')

        <title>Add&raquo; Items</title>
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <style>
          .thumb {
            width: 200px;  
            height: 117px;
            /*margin: 10px 5px 0 0;*/
          }
        </style>

    @endsection

@section('content')
 
<div class="container">
  <h2>Add Items</h2>
  <form class="col s12" method="POST"
    action="{{url('barang/update')}}"
    enctype="multipart/form-data">

    <div class="form-group">
      <label for="nama_barang">Items Name</label>
     <input id="nama_barang" type="text" 
    class="form-control" name="nama_barang" value="{{$barang->nama_barang}}">
    </div>

 <div class="form-group">
      <label for="pwd">Home Store</label>
         <label for="sel1">(select one):</label>
      <select class="form-control" id="sel1" name="asal">
       @foreach($category as $data)
       <option value="{{ $data->nama_category }}">{{ $data->nama_category }}
       </option>
       @endforeach
      </select>
    </div>

      <div class="form-group">
      <label for="email">Seller</label>
      <input type="text" class="form-control" id="email" placeholder="Seller" name="penjual" value="{{$barang->penjual}}">
    </div>

          <div class="form-group">
      <label for="email">Price</label>
      <input type="text" class="form-control" id="email" placeholder="Price" name="harga" value="{{$barang->harga}}">
    </div>

    <div class="form-group">
      <label for="pwd">Condition</label>
      <label for="sel1">(select one):</label>
      <select class="form-control" id="sel1" name="kondisi" value="{{$barang->kondisi}}">
       <option>{{$barang->kondisi}}</option>
        <option>New</option>
        <option>Former</option>
      </select>
    </div>

        <div class="form-group">
      <label for="email">Desc</label>
      <textarea type="text" class="form-control" id="email" placeholder="Desc" name="desc">{{$barang->desc}}</textarea>
       </div>
       <div class="form-group">
               <div class="col-md-4">
            <label for="email">Photo Header</label><p></p>
         <img class="thumb thumbnail" src="{{url('images/'.$barang->photo_header)}}" id="profile-img-tag" />
        <div class="right"><p>
          <div class="btn btn btn-default">
              <input name="sampul[]" id="photo_header" type="file">
          </div>
      </div>
      </div>

      <div class="form-group">
        <label for="sampul2">Photo Detail</label>
        <div id="bahlul">
        <?php
            $cek = App\Image::where('id_barang', $barang->id)->get();
          ?>          
        @foreach($cek as $value)        
        <img class ="thumb thumbnail col-md-5" 
        src = "{{ url('images/'.$value->lokasi_file) }}">        
        @endforeach
        </div>                        
        <output style="display: none;" id="list"></output> 
        <!-- max-width: 421px; max-height: 100px; -->
        <p><br></p>
          <div class="btn btn-default col-md-4 center">
              <input name="sampul2[]" multiple="true" accept=".PNG, .JPEG, .JPG" id="sampul2" 
              type="file">
          </div>
   </div>
   </div>


      <div class="col-md-3 center pull-right">            
      <br>
      <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>
    </div>
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <input type="hidden" name="id" value="{{$barang->id}}">
  </form>
  </div>


   <script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#profile-img-tag').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            document.getElementById('profile-img-tag').style.display = "block";

        }
    }
    $("#photo_header").change(function(){
        readURL(this);
    });
</script>
<script>
  function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb thumbnail col-md-3" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
          document.getElementById('bahlul').style.display = "none";
          document.getElementById('list').style.display = "block";
          document.getElementById('listdiv').style.display = "block";
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

  document.getElementById('sampul2').addEventListener('change', handleFileSelect, false);  
</script>
 
@endsection