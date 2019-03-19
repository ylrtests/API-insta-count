<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Exception;
use App\Post;
use App\Fan;

class FanController extends Controller
{
    /**
    *  Envia todos los fans de la base de datos.
    *   
    */
    public function index(){

        $fans = Fan::select('id','username')
        ->with('posts:posts.id,posts.id_insta')
        ->withCount('posts')
        ->orderBy('posts_count', 'DESC')
        ->orderBy('username','ASC')
        ->get();

        foreach($fans as $fan){
            $fan->posts->makeHidden('pivot');
        }      
        
        // foreach($fans as $fan){
        //     $totalLikes = count($fan->posts);
        //     $fan['totalLikes'] = $totalLikes;
        // }

        return response()->json([
            'success'=> true, 
            'fans'=> $fans
            ]);
    }

    /**
    *  Añade un fan a la base de datos.
    *   
    */

    public function add(Request $request){

        $data = $request->only(['username']);

        $rules = [
            'username' => 'required|unique:fans'
        ];

        $validator = Validator::make($data, $rules);
                
        if($validator->fails()) {
            return response()->json([
                'success'=> false, 
                'error'=> $validator->messages()
                ]);
        }
        
        try{
            Fan::create($data);
        }
        catch(Exception $ex){
            return response()->json([
                'success'=> false, 
                'error'=> $ex->getMessage()
                ]);
        }

        return response()->json([
            'success'=>'true',
            'controller'=>'Fan@add',
            'temp'=> $data
            ]);
    }


    /**
    *  Añade una lista de JSON de Fans a la base de datos.
    *   
    */

    public function addManyFansByList(Request $request){

        set_time_limit(300);

        $data = json_decode($request->getContent());
        $fansUsernames = $data->fansUsernames;

         
        foreach ($fansUsernames as $fanUsername){
            
            $fan = Fan::where('username', $fanUsername)->first();

            if(!$fan){                
                $fan = new Fan;
                $fan->username = $fanUsername;
                $fan->save();
            }   

        }

        return response()->json([
            'success'=>'true',
            'controller'=>'Fan@addManyFansByList',
            'temp'=> 'Se han añadido lista de fans con éxito'
            ]);
    }
}
