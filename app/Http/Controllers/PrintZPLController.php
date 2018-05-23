<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fixture;

//*********************************
// IMPORTANT NOTE 
// ==============
// If your website requires user authentication, then
// THIS FILE MUST be set to ALLOW ANONYMOUS access!!!
//
//*********************************

//Includes WebClientPrint classes
include_once(app_path() . '\WebClientPrint\WebClientPrint.php');
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\ClientPrintJob;

use Session;

class PrintZPLController extends Controller
{
    public function index()
    {

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintZPLController@printCommands'), Session::getId());

        return view('printZPL', ['wcpScript' => $wcpScript]);
    }

    public function printCommands(Request $request)
    {

        if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {


            $useDefaultPrinter = ($request->input('useDefaultPrinter') === 'checked');
            $printerName = urldecode($request->input('printerName'));

            $sm = $request->input('sm');
            $rm = $request->input('rm');
            $fm = $request->input('fm');
            $qty_labels = $request->input('labels');

            $fixture = new Fixture;
            $fixture->sm = $sm;
            $fixture->rm = $rm;
            $fixture->fm = $fm;
            $fixture->save();
             
            //Create ZPL commands for sample label
            $cmds = "^XA";
            $cmds .= "^FO20,30^GB500,320,4^FS";
            $cmds .= "^FO200,60";
            $cmds .= "^BXN,7,200";
            $cmds .= "^FD" . $sm . ' ' . $rm . ' ' . $fm . "^FS";
            $cmds .= "^FO20,30^GB500,180,4^FS";
            $cmds .= "^FO20,206^GB160,144,4^FS";
            $cmds .= "^FO70,240";
            $cmds .= "^BXN,4,200";
            $cmds .= "^FD" . $sm . "^FS";
            $cmds .= "^FO60,300^AC^FDSENSOR^FS";
            $cmds .= "^FO175,206^GB150,144,4^FS";
            $cmds .= "^FO220,240";
            $cmds .= "^BXN,4,200";
            $cmds .= "^FD" . $rm . "^FS";
            $cmds .= "^FO220,300^AC^FDRADIO^FS";
            $cmds .= "^FO390,240";
            $cmds .= "^BXN,4,200";
            $cmds .= "^FD" . $fm . "^FS";
            $cmds .= "^FO320,300^AC^FD   FIX. MODULE^FS";
            $cmds .= "^XZ";
 
            //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
            $cpj = new ClientPrintJob();
            //set ZPL commands to print...
            $cpj->printerCommands = $cmds;
            $cpj->formatHexValues = true;
            $cpj->printerCommandsCopies = $qty_labels;

            if ($useDefaultPrinter || $printerName === 'null') {
                $cpj->clientPrinter = new DefaultPrinter();
            } else {
                $cpj->clientPrinter = new InstalledPrinter($printerName);
            }
         
            //Send ClientPrintJob back to the client
            return response($cpj->sendToClient())
                ->header('Content-Type', 'application/octet-stream');


        }
    }
}
