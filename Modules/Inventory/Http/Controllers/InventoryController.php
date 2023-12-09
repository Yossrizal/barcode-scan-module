<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Inventory\Entities\Inventory;

use App\Traits\HasImage;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    use HasImage;

    public function index()
    {
        return view('inventory::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('inventory::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|max:255',
            'price' => 'required'
        ]);

        $data = new Inventory;
        
        $data->title = $request->title;
        $data->description = htmlspecialchars($request->description);
        $data->image_url = ($request->hasFile('image_url'))? $this->upload_image($request->image_url) : null;;
        $data->decoded_text = isset($request->decoded_text)? $request->decoded_text : '';
        $data->format_code = isset($request->format_code)? $request->format_code : '';
        $data->price = $request->price;

        $data->save();

        return response()->json(['status' => 'success insert!']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($decodedText)
    {
        // echo json_encode($decodedText);die;
        $data = [];
        $decodedText = urldecode($decodedText);
        $data = Inventory::where('decoded_text', $decodedText)->first();
        if(isset($data->description)) $data->description = html_entity_decode($data->description);
        

        return response()->json($data);
    }

    public function showbyname(Request $request)
    {
        // echo json_encode($request->all());die;
        $data = new \stdClass();
        
        if (isset($request->q)){
            $title = strtolower(urldecode($request->q));
        } else {
            return response()->json($data);
        }
        
        $data->items = Inventory::select('title', 'decoded_text', 'price')->whereRaw('LOWER(title) LIKE "%'.$title.'%"')->get();
        if(isset($data->description)) $data->description = html_entity_decode($data->description);
        
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('inventory::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        //
        // echo json_encode($request->all());die;

        $validated = $request->validate([
            'title' => 'required|max:255',
            'decoded_text' => 'required'
        ]);

        $request->decoded_text = urldecode($request->decoded_text);

        $data = Inventory::where('decoded_text', $request->decoded_text)->first();
        if($data != null){
            $data->title = $request->title;
            $data->description = $request->description;
            $data->price = $request->price;
            $data->decoded_text = $request->decoded_text;
            $data->save();
        } else {
            $data = new Inventory;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->price = $request->price;
            $data->decoded_text = $request->decoded_text;
            $data->save();
        }
        

        return redirect('inventory')->with('success','Data Updated!');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
