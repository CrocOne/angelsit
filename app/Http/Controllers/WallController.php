<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ATehnix\VkClient\Client;
use Illuminate\Support\Facades\Input;

class WallController extends Controller
{
  public function index()
  {
	$page = Input::get('page');
	$count = 20;
	$cur_page = $page;
	if(isset($page)){
		$offset = ($page - 1) * $count;
	}else{
		$offset = 0;
	}
	 
	$api = new Client('5.85');
	$api->setDefaultToken("3195e9e93195e9e93115aff35931c30219331953195e9e9697cc67864e1f0c35fe7c074");
	$get = $api->request('wall.get', ['domain' => 'extrawebdev','count' => $count ,'offset' => $offset ]);
	$posts = [];
	$total = $get['response']['count'];
	foreach($get['response']['items'] as $post ) {
		if(isset($post['copy_history'])){
			$posts[] = $post['copy_history'][0]['owner_id'].'_'.$post['copy_history'][0]['id'];
		}else{
			$posts[] = $post['owner_id'].'_'.$post['id'];
		}
	} 
	$posts = $api->request('wall.getById', ['posts' => implode(',',$posts)]);	 
	$return = [];
	$i = 0;
	foreach($posts['response'] as $post){
		if(isset($post['attachments'])){
			foreach($post['attachments'] as $attachment){
				if($attachment['type'] == 'link'){
					$postlink = $attachment['link']['url'];
				}elseif($attachment['type'] == 'photo'){					 
					$postimage = $this->search($attachment['photo']['sizes'],'type','q')['url'];
				}
				
			}
			
		}
		
		if(empty($postlink)){ $postlink = 'https://vk.com/wall'.$post['owner_id'].'_'.$post['id']; }
		if(empty($postimage)){ $postimage = '/img/noImage.jpg'; }
		$return[] = ['id' => $i++,'text' => $post['text'], 'link' => $postlink, 'image' => $postimage ];
	}	 
	return [
        'items' => (object)$return,
        'current_page' => $cur_page,
		"from" => $cur_page,
		"to" => $cur_page + 10,		 
		'per_page' => 20,		 		 
		'last_page' => $cur_page + 10,		 		 
		'total' => $total,	 
    ];
  }
  
  
	private function search($array, $key, $value)
	{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results = $array;
        }
		
        foreach ($array as $subarray) {			
            if (isset($subarray[$key]) && $subarray[$key] == $value) {
            $results = $subarray;
			}
        }
    } 
		return $results;
	}
	
} 