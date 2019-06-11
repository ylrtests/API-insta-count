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

        $posts = Post::select('id','id_insta','date','updated_at')
        // ->with('fans:fans.id,fans.username')
        ->withCount('fans')
        ->orderBy('date','DESC')
        ->get();

        // foreach($posts as $post){
        //     $post->fans->makeHidden('pivot');
        // }  
        
        return response()->json([
            'success'=> true, 
            'posts'=> $posts
            ]);
    }

    /**
    *  Envia un posts en especifico
    *   
    */
    public function getPostByInstagramId(Request $request, $id){

        $post = Post::select('id','id_insta','date','updated_at')
        ->with('fans:fans.id,fans.username')
        ->withCount('fans')
        ->where("id_insta","=",$id)
        ->get();

        foreach($post as $p){
            $p->fans->makeHidden('pivot');
        }  
        
        return response()->json([
            'success'=> true, 
            'post'=> $post[0]
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
            'controller'=>'Post@add',
            'temp'=> $data
            ]);
    }

    /**
    * Añade los usuarios que hicieron like a un post existente.
    *
    */
    public function addUsersWhoLikedPost(Request $request){

        set_time_limit(1000);

        $data = json_decode($request->getContent());

        $id_insta = $data->id_insta;
        $fansUsernames = $data->fansUsernames;

        try{
            $post = Post::select('id')->where('id_insta',$id_insta)->first();
            foreach ($fansUsernames as $fanUsername){
                $fan = Fan::select('id','username')->where('username',$fanUsername)->first();
    
                if(!$fan){                
                    $fan = new Fan;
                    $fan->username = $fanUsername;
                    $fan->save();
                    //Fan::create(['username' => $fanUsername]);
                }
    
                //Mantiene la tabla pivot sincronizada
                $fan->posts()->sync([$post->id], false);     
    
            }
            //Actualizo tabla de posts
            $post->touch();


        }
        catch(Exception $ex){
            return response()->json([
                'success'=> false, 
                'message'=> $ex->getMessage()
                ]);
        }


        return response()->json([
            'success'=>'true',
            'controller'=>'Post@addUsersWhoLikedPost',
            'message'=> 'Se han añadido fans con éxito'
            ]);

        
        
    }


}
