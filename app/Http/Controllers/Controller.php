<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use mikehaertl\pdftk\Pdf;
use Illuminate\Http\Request;
use ZipArchive;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function insertFile(Request $request){

        $request->validate([
            'csv' => 'required',
            'pdf' => 'required',
        ]);

        if(file_exists(public_path().'/pdf/model/lea_generator.zip')){
            unlink(public_path().'/pdf/model/lea_generator.zip');
        }
        $file = $request->file('pdf');
        $file->move(public_path ('\pdf\model'), 'model.pdf');
        if (!($fp = fopen($request->csv, 'r'))) {
            die("Can't open file...");
        }

        //read csv headers
        $key = fgetcsv($fp,"1024",";");
        // parse csv rows into array
        $json = array();
            while ($row = fgetcsv($fp,"1024",";")) {
            $json[] = array_combine($key, $row);
        }

        $zip = new ZipArchive;
        if (TRUE)
        {
            foreach ($json as $pharmacy){
                $zip->open(public_path().'/pdf/model/lea_generator.zip', ZipArchive::CREATE || ZipArchive::OVERWRITE);
                $pdf = new Pdf(public_path().'/pdf/model/model.pdf', [
                    'command' => '/app/bin/pdftk',
                    'useExec' => true,
                ]);

                $test = $pdf->fillForm($pharmacy)->needAppearances()->execute();
                $zip->addFile($pdf->getTmpFile(), $pharmacy[$key[0]].'.pdf');
                $zip->close();
            }

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="'.basename(public_path().'/pdf/model/lea_generator.zip').'"');
            header('Content-Length: ' . filesize(public_path().'/pdf/model/lea_generator.zip'));

            flush();
            readfile(public_path().'/pdf/model/lea_generator.zip');

        }


    }

    function downloadPdf($idFiles,$id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://staging-api.yousign.com/files/'.$idFiles.'/download',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer c06c186f40a88d7ec35ef51915bd9b08',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $base64_decode = base64_decode($response);
        $pdf = fopen(public_path().'/etudiant/testcovid19/testrealiser/'.$id.'_formulaire_test_antigenique.pdf', 'w');
        fwrite($pdf, $base64_decode);
        fclose($pdf);
        DB::table('test_covid19')->where('id', $id)->update(['is_sign' => 1]);
        session()->flash('message',"Test enregistrer");
        session()->flash('status', 'success');

        return redirect('/dashboard');
    }
}
