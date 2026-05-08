<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {

    try {

        throw new Exception("Te tiraste produccion un viernes en la noche");

    } catch (\Throwable $e) {

        // Http::post("https://discordapp.com/api/webhooks/1502105368631967865/Xa8PtZIpvn_aZmQoiCtkA1KDFugfWVnXB-DqOugI0SYSVBcTj877NWKblTmfsSx1aB7e", [
        //     'content' =>
        //         "ERROR BASICO TEST EN LARAVEL\n" .
        //         "Mensaje: {$e->getMessage()}\n" .
        //         "Archivo: {$e->getFile()}\n" .
        //         "Línea: {$e->getLine()}"
        // ]);










        Http::post(env('DISCORD_WEBHOOK'), [

            'embeds' => [
                [
                    'title' => 'ERROR MAS PRO, TEST EN LARAVEL',

                    'description' =>
                        "Mensaje: {$e->getMessage()}\n\n" .
                        "Archivo: {$e->getFile()}\n" .
                        "Línea: {$e->getLine()}",

                    'color' => 16711680,

                    'footer' => [
                        'text' => 'Laravel Error Monitor'
                    ],

                    'timestamp' => now()->toIso8601String()
                ]
            ]

        ]);

        return "Error enviado a Discord";

    }

});

