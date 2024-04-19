<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QrCode;
use PDF;
use App\Models\User;

class IndexController extends Controller
{

    /**
     * Handle the incoming request. SPDp.koptJjP
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

            if($key > 10) break;
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
        $user = User::where('email', $request->email)->firstOrFail();
        return view('id-card', ['user' => $user]);
    }

       /**
     * Handle the incoming request.
     */
    public function pdf(Request $request)
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
            // info($e->getMessage());
        }
    }

    public function idCard() {
        $users = [
            [
                "name" => "Emmanuel Opara",
                "email" => "emmanuelopara@dreambugltd.com",
                "designation" => "Music Producer",
                "phone" => "+2348065129182"
            ],

            [
                "name" => "Daniel Opara",
                "email" => "danielopara@dreambugltd.com",
                "designation" => "Music Producer",
                "phone" => "+2348065129154"
            ],

            [
                "name" => "Grace Ebimoh",
                "email" => "graceebimoh@dreambugltd.com",
                "designation" => "Marketing & Communications Executive",
                "phone" => "+2347065101931"
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
            $code = "https://qr.wristbandsng.com?email=" . $user['email'];
            $this->generateQr($code, "qrcode", $user['email']);
        }

        return view('id-card');
    }

    private function generateQr($code, $filename = "qrcode", $folder = "qrcode") {
        try {
            $path = public_path($folder);
            if(!file_exists($path)) mkdir($path, 0777, true);

            $file = $filename . ".png";
            $filename = $path . "/" . $file;

            if(!file_exists($filename)) {
                QrCode::format('png')->generate($code, $filename);
            }
        }
        catch (\Exception $e) {
            // info($e->getMessage());
        }
    }
}
