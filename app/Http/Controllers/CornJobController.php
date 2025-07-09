<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

class CornJobController extends Controller
{
    public $userModel;

    function __construct()
    {
        // Constructor logic can be added here if needed
        $this->userModel = new \App\Models\User();
    }


    public function index()
    {
        $users = $this->userModel->getAuthIdentifierName();
        // This method can be used to handle requests to the index route
        return response()->json(['message' => 'Welcome to the Corn Job Controller']);
    }
}