<?php
namespace App\Http\Controllers;
use App\Http\Requests\OrderRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function create(OrderRequest $request)
    {
        $data = $request->validated();
        /** @var \App\Models\Client $client */
        $client = Client::create([
            'name' => $data['name'],
            'num' => bcrypt($data['num'])
        ]);

    }


    public function me(Request $request)
    {
        return $request->user();
    }
}
