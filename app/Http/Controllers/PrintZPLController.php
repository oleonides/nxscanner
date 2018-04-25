<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

            $sensor = $request->input('sensor');
            $radio = $request->input('radio');
            $controller = $request->input('controller');
            $qty_labels = $request->input('labels');
             
            //Create ZPL commands for sample label
            $cmds = "^XA";
            $cmds .= "^FO20,30^GB500,320,4^FS";
            $cmds .= "^FO200,40";
            $cmds .= "^BQN,2,4";
            $cmds .= "^FDMM,A" . $sensor . ' ' . $radio . ' ' . $controller . "^FS";
            $cmds .= "^FO20,30^GB500,180,4^FS";
            $cmds .= "^FO20,206^GB160,144,4^FS";
            $cmds .= "^FO80,250";
            $cmds .= "^BXN,3,200";
            $cmds .= "^FD" . $sensor . "^FS";
            $cmds .= "^FO60,300^AC^FDSENSOR^FS";
            $cmds .= "^FO175,206^GB150,144,4^FS";
            $cmds .= "^FO230,250";
            $cmds .= "^BXN,3,200";
            $cmds .= "^FD" . $radio . "^FS";
            $cmds .= "^FO220,300^AC^FDRADIO^FS";
            $cmds .= "^FO400,250";
            $cmds .= "^BXN,3,200";
            $cmds .= "^FD" . $controller . "^FS";
            $cmds .= "^FO320,300^AC^FD FIX. CONTROLLER^FS";
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
