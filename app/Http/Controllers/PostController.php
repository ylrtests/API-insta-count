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
    public function addUsersWhoLikedPost(Request $request){

        set_time_limit(300);

        $data = json_decode($request->getContent());

        $id_insta = $data->id_insta;
        $fansUsernames = $data->fansUsernames;

        $post = Post::select('id')->where('id_insta',$id_insta)->first();
        $fans = Fan::all('id','username');

       

        
        foreach ($fansUsernames as $fanUsername){
            
            $fan = $fans->where('username', $fanUsername)->first();

            if(!$fan){                
                $fan = new Fan;
                $fan->username = $fanUsername;
                $fan->save();
                //Fan::create(['username' => $fanUsername]);

            }

            $fan->posts()->sync([$post->id], false);     

        }

        
        
    }

}
