<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MQTTController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index() {
        $host = env("WS_HOST");
        $port = env("WS_PORT");
        $username = env("WS_USERNAME");
        $password = env("WS_PASSWORD");

        return response()->json(array("ws_host" => $host, "ws_port" => $port, "ws_username" => $username, "ws_password" => $password));
    }
}
