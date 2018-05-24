@extends('layouts.test')
 
@section('body')
 
<h1>NX Scanner</h1>
<label class="checkbox">
    <input type="checkbox" id="useDefaultPrinter" /> <strong>Use default printer</strong> or...
</label>
<div id="loadPrinters">
<br />
    WebClientPrint can detect the installed printers in your machine.
<br />
<input type="button" onclick="javascript:jsWebClientPrint.getPrinters();" value="Load installed printers..." />
                 
<br /><br />
</div>
<div id="installedPrinters" style="visibility:hidden">
<br />
<label for="installedPrinterName">Select an installed Printer:</label>
<select name="installedPrinterName" id="installedPrinterName"></select>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card">
                <div class="card-header bg-secondary">
                    <strong style="font-size:20px;color:white">Print Label</strong>    
                    <img src="{{ asset('assets/hubbell.png')}}" width="140px" class="float-right">
                </div>
                <div class="card-body">
                        <div class="form-group">
                            <label>Quantity of labels to print</label>
                            <select class="form-control" name="labels" id="labels">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="scan">Scan code</label>
                            <input type="text" class="form-control" id="code" name="code">
                        </div>
                        <div class="form-group">
                            <label>SM</label>
                            <input type="text" class="form-control" id="sm" name="sm" disabled>
                        </div>
                        <div class="form-group">
                            <label>RM</label>
                            <input type="text" class="form-control" id="rm" name="rm" disabled>
                        </div>
                        <div class="form-group">
                            <label>FM</label>
                            <input type="text" class="form-control" id="fm" name="fm" disabled>
                        </div>
                    
                        <button type="button" onclick="printLabel();" class="btn btn-primary">Print label 
                            <i class="fas fa-print"></i>
                        </button>
                </div>
            </div>
            
        </div>
    </div>
</div>
       
@endsection
 
@section('scripts')
 
<script type="text/javascript">
    var wcppGetPrintersTimeout_ms = 10000; //10 sec
    var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
 
    function wcpGetPrintersOnSuccess(){
        // Display client installed printers
        if(arguments[0].length > 0){
            var p=arguments[0].split("|");
            var options = '';
            for (var i = 0; i < p.length; i++) {
                options += '<option>' + p[i] + '</option>';
            }
            $('#installedPrinters').css('visibility','visible');
            $('#installedPrinterName').html(options);
            $('#installedPrinterName').focus();
            $('#loadPrinters').hide();                                                        
        }else{
            alert("No printers are installed in your system.");
        }
    }
 
    function wcpGetPrintersOnFailure() {
        // Do something if printers cannot be got from the client
        alert("No printers are installed in your system.");
    }

    function printLabel(){
        if($("#sm").val() != '' && $("#rm").val() != '' && $("#fm").val() != '')
        {
            javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&sm=' + $('#sm').val() + '&rm=' + $('#rm').val() + '&fm=' + $('#fm').val() + '&labels=' + $('#labels').val());

            $('#sm').val('');
            $('#rm').val('');
            $('#fm').val('');
        }else{
            swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Some data is missing',
                })
        }
    }

    function validateScannedCode(code, route, value){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: route + code,
            type: 'POST',
            dataType: "JSON",
            data: {
                "code": code,
            },
            success: (response) =>
            {
                if(response.data)
                {
                    swal(
                    'Incorrect',
                     response.data,
                    'error'
                    )
                }else
                    $(`#${value}`).val(code);
            },
            error: (xhr) => {
                console.log(xhr.responseText);
            }
        });
    }

    $(document).ready( () => {
        $("#code").keypress( (e) => {
            if (e.which == 13) {
                assingCode();
            }
        });    
    });

    function assingCode(){
        let route = "";
        let value = "";
        let code = $("#code").val();
            $("#code").val('');
            const patt = new RegExp("^[a-fA-F0-9]{8}$");
                if(patt.test(code)){
                    let res = code.substring(0,2);
                    if(res === "01"){
                        route = "validate-fm/";
                        value = "fm";
                        validateScannedCode(code, route, value);
                    }else 
                        if(res === "90"){
                            route = "validate-rm/";
                            value = "rm";
                            validateScannedCode(code, route, value);
                        }else 
                            if(res === "81"){
                                route = "validate-sm/";
                                value = "sm";
                                validateScannedCode(code, route, value);
                            }
                }else{
                    swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    })
                }
    }
   
</script>
 
{!! 
 
// Register the WebClientPrint script code
// The $wcpScript was generated by PrintZPLController@index
 
$wcpScript;
 
!!}
 
@endsection