<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Exception;
use App\Post;
use App\Fan;

class PostController extends Controller
{
    /**
    *  Envia todos los posts de la base de datos.
    *   
    */
    public function index(){
        $posts = Post::all();

        return response()->json([
            'success'=> true, 
            'posts'=> $posts
            ]);
    }

    /**
    *  Añade un post a la base de datos.
    *   
    */
    public function add(Request $request){

        $data = $request->only(['id_insta','date']);

        $rules = [
            'id_insta' => 'required|unique:posts',
            'date' => 'required'
        ];
        $validator = Validator::make($data, $rules);
                
        if($validator->fails()) {
            return response()->json([
                'success'=> false, 
                'error'=> $validator->messages()
                ]);
        }

        try{
            Post::create($data);
        }
        catch(Exception $ex){
            return response()->json([
                'success'=> false, 
                'error'=> $ex->getMessage()
                ]);
        }

        return response()->json([
            'success'=>'true',
            'controller'=>'addPost',
            'temp'=> $data
            ]);
    }

    /**
    * Añade los usuarios que hicieron like a un post existente.
    *
    */
    public function addUsers(Request $request){

        $data = $request->all();        
        return response()->json(['register' => $data[20],'temp'=> $data]);
    }

}
