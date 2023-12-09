<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name') }} - Inventory Management</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
        #qr-reader {
            /* max-width: 500px; */
            /* max-height: 300px; */
        }
    </style>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    {{-- <div class="col-lg-5 d-none d-lg-block bg-register-image"></div> --}}
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div>
                                @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                                @endif
                                @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }}
                                </div>
                                @endif
                            </div>
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Inventory Management</h1>
                                <p class="mt-4">
                                    Search item. <br>                                    
                                </p>
                                <div class="row mb-4">
                                    <div class="col-lg-12">
                                        {{-- <input type="text" class="form-control" id="dynamic_search" placeholder="search by name"><br> --}}
                                        <select class="form-control js-data-example-ajax">
                                            <option value="" selected disabled>Masukan nama barang disini ...</option>
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-info w-100" id="btn_scan" onclick="location.reload()" hidden>Scan Again</button>
                                <div class="row">
                                    <div class="col-12">
                                        {{-- <video id="video" autoplay></video> --}}
                                        <div id="qr-reader"></div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" id="search_by_code" placeholder="search by code">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-info btn-sm my-2" onclick="scanCode()">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>  

    <div class="modal fade" tabindex="-1" role="dialog" id="scanned_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview_title">Hasil Scan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <p id="scanned_text">

                    </p>
                </div>
                <div class="row">
                        <div class="col-12">
                            <form action="{{ url('inventory/update')}}" method="POST">
                                @csrf
                                <table class="table table-bordered w-100">
                                    <tr>
                                        <td>Harga</td>
                                        <td><input type="text" class="form-control" name="price" id="data_price" required></td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td><input type="text" class="form-control" name="title" id="data_title" required></td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi</td>
                                        <td><input type="text" class="form-control" name="description" id="data_description"></td>
                                    </tr>
                                    <tr>
                                        <td>Decoded Text</td>
                                        <td><input type="text" class="form-control" name="decoded_text" id="data_decoded_text" required></td>
                                    </tr>
                                </table>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-info">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="show_data">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview_title">Data By Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <p id="scanned_text">

                    </p>
                </div>
                <div class="row">
                        <div class="col-12">
                            @csrf
                            <table class="table table-bordered w-100" id="table_data">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>
    </div>

</body>


    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    
    <!-- ...or, you may also directly use a CDN :-->
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.1.0"></script>
    <!-- ...or -->
    <script src="https://unpkg.com/autonumeric"></script>

    {{-- <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script> --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            table_data = new DataTable('#table_data', {
                // scrollY: 300,
                // paging: false
            } );

            $('.js-data-example-ajax').select2({
                ajax: {
                    delay: 250,
                    url: `{{ url('') }}/inventory/showbyname`,
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            q: params.term
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data) {
                        console.log(data);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            // results: data.items
                            results: $.map(data.items, function(obj) {
                                price = new Intl.NumberFormat("de-DE", {currency: "EUR"}).format(obj.price);
                                return { 
                                    id: obj.decoded_text, 
                                    text: `Rp. ${price} - ${obj.title}` };
                            })
                        };
                    }
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                }
            }); 

            $('.js-data-example-ajax').on('select2:select', function (e) {
                // Do something
                var dectext = e.params.data.id;
                getData(dectext);
            });
        });

        init_price = new AutoNumeric('#data_price', {
            decimalCharacter : ',',
            decimalPlaces: '0',
            digitGroupSeparator : '.',
            unformatOnSubmit: true,
        });

        let dectext;
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code scanned = ${decodedText}`, decodedResult);
            dectext = decodedText;            
            getData(dectext);
            html5QrcodeScanner.clear();
            $('#btn_scan').removeAttr('hidden');
        }

        function getData(dectext){
            $('#scanned_text').text(`Decoded Text: ${dectext}`);
            $('#scanned_modal').modal('show');
            $.get(`{{ url('') }}/inventory/show/${encodeURIComponent(encodeURIComponent(dectext))}`, function(data, status){
                // alert("Data: " + data + "\nStatus: " + status);
                setData(data);
            });
        }

        function scanCode(){
            dectext = $('#search_by_code').val();
            if(dectext != ''){
                getData(dectext);
            } else {
                alert('masukan dahulu kodenya');
            }            
        }

        function setData(returned_data){
            console.log(returned_data);
            if(Object.keys(returned_data).length > 0){
                let price = new Intl.NumberFormat("de-DE", {currency: "EUR"}).format(returned_data.price);
                $('#data_title').val(returned_data.title);
                $('#data_description').val(returned_data.description);
                $('#data_price').val(price);
                $('#data_decoded_text').val(returned_data.decoded_text);
                console.log('data ada');
            } else {
                $('#data_title').val('');
                $('#data_description').val('');
                $('#data_price').val('');
                $('#data_decoded_text').val(dectext);
                alert('data belum ada');
                console.log('data belum ada');
            }
            
        }
        
        config = {
            fps: 5,
            qrbox: {width: 250, height: 250},
            rememberLastUsedCamera: true,
            disableFlip: true,
            aspectRatio: 1,
            facingMode: {exact: "environment"},
            focusMode: "continuous",
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA,Html5QrcodeScanType.SCAN_TYPE_FILE] ,
            formatsToSupport: [Html5QrcodeSupportedFormats.EAN_13, Html5QrcodeSupportedFormats.QR_CODE],
            qrbox: 250,
            showTorchButtonIfSupported: true,
            useBarCodeDetectorIfSupported: true,
            showZoomSliderIfSupported: true,
            defaultZoomValueIfSupported: 2
        };

        var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader",config);
        html5QrcodeScanner.render(onScanSuccess);

    </script>
</html>