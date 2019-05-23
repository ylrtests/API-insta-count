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
        set_time_limit(2000);

        $fans = Fan::select('id','username','status')
        ->with('posts:posts.id,posts.id_insta')
        ->withCount('posts')
        ->orderBy('status','DESC')
        ->orderBy('posts_count', 'ASC')
        ->orderBy('username','ASC')
        ->get();

        foreach($fans as $fan){

            $fan->postCount = $fan->posts_count;
            $fan->save();

            $fan->posts->makeHidden('pivot');
            $fan['url'] = "https://www.instagram.com/".$fan->username."/";
        }      

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

        set_time_limit(1000);

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


    /**
    *  Añade y actualiza lista de Followers y personas que sigo
    *  a la base de datos
    *   
    */

    public function addFollowersAndFriends(Request $request){

        set_time_limit(2000);

        $data = json_decode($request->getContent());
        $followingUsers = $data->following;
        $followersUsers = $data->followers;
        $fans = Fan::all();

        foreach ($fans as $fan){

            $value = $fan->username;
            $valueFollower = array_search($value,$followersUsers);
            $valueFollowing = array_search($value, $followingUsers);

            $valueFollower++;
            $valueFollowing++;

            if($valueFollower){

                if($valueFollowing){

                    if($fan->status != 'both') {
                        $fan->status = 'both';
                        $fan->save();
                    }
                    
                    $valueFollower--;
                    $valueFollowing--;
                    unset($followersUsers[$valueFollower]);
                    unset($followingUsers[$valueFollowing]);
                }

                else{

                    if($fan->status != 'follower') {
                        $fan->status = 'follower';
                        $fan->save();
                    }

                    $valueFollower--;
                    $valueFollowing--;
                    unset($followersUsers[$valueFollower]);
                }

            }

            else if($valueFollowing ){

                if($fan->status != 'following') {
                    $fan->status = 'following';
                    $fan->save();
                }

                $valueFollower--;
                $valueFollowing--;
                unset($followingUsers[$valueFollowing]);
            }

            else{

                if($fan->status != 'none') {
                    $fan->status = 'none';
                    $fan->save();
                }
            }

        }

        
        foreach ($followersUsers as $index => $followersUser){

                $fan = new Fan;
                $fan->username = $followersUser;

                $valueFollowing = array_search($followersUser, $followingUsers);
                $valueFollowing++;

          
            if($valueFollowing){  

                $fan->status = 'both';
                $fan->save();
                
                $valueFollowing--;
                unset($followersUsers[$index]);
                unset($followingUsers[$valueFollowing]);

            }
            else{

                $fan->status = 'follower';
                $fan->save();

                unset($followersUsers[$index]);

            }  

        }

        foreach ($followingUsers as $index => $followingUser){

            $fan = new Fan;
            $fan->username = $followingUser;
            $fan->status = 'following';
            $fan->save();
      
            unset($followingUsers[$index]);
            
        }

         return response()->json([
            'success'=>'true',
            'controller'=>'Fan@addFollowersAndFriends',
            'followingUsers'=> $followingUsers,
            'followersUsers' => $followersUsers
            ]);
    }


    /**
     * Elimina fans de la base de datos con status = "none"
     * de acuerdo al número de posts igual o menores al indicado.
     * Ejemplo: Si postNumber = 3, eliminará todos los fans con status = "none"
     * que hayan hecho like a tres o menos posts
     */

     public function deleteFansWithStatusNone(Request $request){

        $postNumber = $request->postNumber;

        $rules = [
            'postNumber' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }

        $fans = Fan::select('id','username','status','postCount')
        ->where([
            ['status','=','none'],
            ['postCount', '<=' , $postNumber]
        ])
        ->orderBy('postCount', 'ASC')
        ->delete();

        return response()->json([
            'success'=>'true',
            'controller'=>'Fan@deleteFansWithStatusNone',
            'mensaje'=> 'Se han eliminado con éxito',
            'postNumber' => $postNumber
            ]);

     }
}
