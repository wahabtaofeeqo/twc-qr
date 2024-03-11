<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QrCode;
use PDF;

class IndexController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $path = public_path("images/qrs");
        $files = scandir($path);

        $all = [];
        foreach ($files as $key => $file) {
            $filename = $path . "/" . $file;
            if(is_file($filename)) {
                $name = explode('.', $file)[0];
                $all[] = [
                    'name' => $name,
                    'file' => $filename
                ];
            }

            if($key >= 10) break;
        }

        // return view('qr', [
        //     'files' => $all
        // ]);

        $pdf = PDF::loadView('qr', [
            'files' => $all
        ]);

        return $pdf->download('qr.pdf');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $limit = 100;
        $page = $request->page ?? 1;
        $offset = ($page - 1) * $limit;

        $range = [];
        for ($i=$offset; $i < $limit * $page; $i++) {
            $range[] = $i + 1;
        }

        $pdf = PDF::loadView('index', [
            'range' => $range
        ]);

        return $pdf->download('chap.pdf');

        // return view('index', [
        //     'range' => $range
        // ]);
    }

    private function doGenerate($offset, $limit) {
        try {
            for ($i = $offset; $i < $limit; $i++) {
                $code = str_pad(strval($i + 1), 4, "0", STR_PAD_LEFT);

                $path = public_path('qrcode/');
                if(!file_exists($path)) mkdir($path, 0777, true);

                $file = $code . ".png";
                $filename = $path . "/" . $file;

                if(!file_exists($filename)) {
                    QrCode::format('png')->size(100)->generate($code, $filename);
                }
            }
        }
        catch (\Exception $e) {
            //throw $th;
            info($e->getMessage());
        }
    }
}
