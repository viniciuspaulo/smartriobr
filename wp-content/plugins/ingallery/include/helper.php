<?php
defined('ABSPATH') or die(':)');

abstract class ingalleryHelper
{	
	const MATCH_IG_USER = '~^\@([a-z0-9_\-\.]+)$~';
	const MATCH_IG_HASHTAG = '~^\#(\w+)$~u';
	const MATCH_IG_MEDIA_CODE_CLASS = '[a-zA-Z0-9_\-]+';
    const MATCH_IG_LOCATION_URI = '~^https\://www\.instagram\.com/explore/locations/([0-9]+)~i';

	static function onInit(){
		load_plugin_textdomain( 'ingallery', false, INGALLERY_PATH.'languages' );
		wp_register_style('google-font-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i&subset=cyrillic,cyrillic-ext,latin-ext');
		wp_register_style('ingallery-colorpicker-styles', plugins_url('assets/colorpicker/jquery.minicolors.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-tokenfield-styles', plugins_url('assets/css/tokenfield.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-app-styles', plugins_url('assets/css/app.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-frontend-styles', plugins_url('assets/css/frontend.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-frontend-styles-custom',  get_stylesheet_directory_uri().'/ingallery/frontend.css', array(), INGALLERY_VERSION);
		wp_register_style('ingallery-icon-font', plugins_url('assets/css/ingfont.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-admin-styles', plugins_url('assets/css/backend.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-slick-styles', plugins_url('assets/slick/slick.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_script('ingallery-slick', plugins_url('assets/slick/slick.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-admin-scripts', plugins_url('assets/js/backend.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-colorpicker', plugins_url('assets/colorpicker/jquery.minicolors.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-tokenfield', plugins_url('assets/js/tokenfield.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-stepper', plugins_url('assets/js/stepper.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-plugin', plugins_url('assets/js/jq-ingallery.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('jq-form', plugins_url('assets/js/jquery.form.min.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-app', plugins_url('assets/js/app.js', INGALLERY_FILE), array('jquery','jq-form','ingallery-plugin','ingallery-tokenfield','ingallery-stepper'), INGALLERY_VERSION);
	}
	
	static function sourcesToArray($str,$add=array()){
		$result = array();
		if(is_string($str)){
			$parts = explode(',',$str);
			foreach($parts as $part){
				$part = trim($part);
                if(strpos($part,'&')!==false){
                    $result[] = $part;
                }
                else{
                    preg_match('~^(https\:\/\/www\.instagram\.com\/p\/'.self::MATCH_IG_MEDIA_CODE_CLASS.')~',$part,$img);
                    preg_match(self::MATCH_IG_LOCATION_URI,$part,$location);
                    if(preg_match(self::MATCH_IG_USER,$part) || preg_match(self::MATCH_IG_HASHTAG,$part)){
                        $result[] = $part;
                    }
                    else if(is_array($img) && isset($img[1])){
                        $result[] = $img[1];
                    }
                    else if(is_array($location) && isset($location[1])){
                        $result[] = $part;
                    }
                    else if(in_array($part,$add)){
                        $result[] = $part;
                    }
                }
			}
		}
		return array_unique($result);
	}
	
	static function getSourceData($source){
		if(preg_match(self::MATCH_IG_USER,$source)){
			return array(
				'type' => 'user',
				'value' => substr($source,1),
			);
		}
		else if(preg_match(self::MATCH_IG_HASHTAG,$source)){
			return array(
				'type' => 'hashtag',
				'value' => substr($source,1),
			);
		}
        else if(preg_match(self::MATCH_IG_LOCATION_URI,$source,$match)){
            return array(
				'type' => 'location',
				'value' => $match[1],
			);
		}
		else{
			preg_match('~^https\:\/\/www\.instagram\.com\/p\/('.self::MATCH_IG_MEDIA_CODE_CLASS.')~',$source,$img);
			if(is_array($img) && isset($img[1])){
				return array(
					'type' => 'picture',
					'value' => $img[1],
				);
			}
		}
		return false;
	}
	
	static function loadUserData($username){
		if($username==''){
			return false;
		}
		$data = self::getData('https://www.instagram.com/'.$username.'/?__a=1',array(),36000);
		if(is_array($data) && isset($data['user']) && isset($data['user']['username']) && $data['user']['username']==$username){
			return $data['user'];
		}
		return false;
	}
    
    static function getUserByID($id,$mediaCode){
        $cacheLifetime = 1209600; //14 days in seconds
        $cacheID = 'ing_user_'.$id;
        $cached = get_transient($cacheID);
		if($cached!==false){
			return json_decode($cached,true);
		}
        $data = self::getData('https://www.instagram.com/p/'.$mediaCode.'/?__a=1',array(),$cacheLifetime);
        if(isset($data['graphql']['shortcode_media']['owner']['username'])){
            $user = $data['graphql']['shortcode_media']['owner'];
            set_transient($cacheID, json_encode($user), $cacheLifetime);
            return $user;
        }
        return false;
    }
	
	static function getMediaListFromSource($sourceStr,$album,$bindData=array()){
		$result = array();
		$cacheLifetime = (int)$album['cache_lifetime'];
		$source = self::getSourceData($sourceStr);
		if($source && $source['type']=='user'){
			$userData = self::loadUserData($source['value'],$cacheLifetime);
			if(is_array($userData) && isset($userData['media']) && isset($userData['media']['nodes']) && is_array($userData['media']['nodes']) && count($userData['media']['nodes'])){
				$cursor = '';
                $owner = $userData;
                unset($owner['media']);
				foreach($userData['media']['nodes'] as $node){
                    $node['owner'] = $owner;
					$result[] = self::makeGalleryItem($node,$album);
				}
				if(isset($userData['media']['page_info']) && isset($userData['media']['page_info']['has_next_page']) && $userData['media']['page_info']['has_next_page']){
					$cursor = $userData['media']['page_info']['end_cursor'];
					for($step=0;$step<5;$step++){
						if($cursor==''){
							break;
						}
						$media = self::getData('https://www.instagram.com/graphql/query/?query_id=17888483320059182&variables={"id":"'.$userData['id'].'","first":200,"after":"'.$cursor.'"}',array(),$cacheLifetime);
						if(is_array($media) && isset($media['data']) && isset($media['data']['user']) && isset($media['data']['user']['edge_owner_to_timeline_media']) && isset($media['data']['user']['edge_owner_to_timeline_media']['edges']) && is_array($media['data']['user']['edge_owner_to_timeline_media']['edges']) && count($media['data']['user']['edge_owner_to_timeline_media']['edges'])){
							foreach($media['data']['user']['edge_owner_to_timeline_media']['edges'] as $node){
								if(isset($node['node']) && is_array($node['node'])){
                                    $node['node']['owner'] = $owner;
									$result[] = self::makeGalleryItem($node['node'],$album);
								}
							}
							$cursor = '';
							if(isset($media['data']['user']['edge_owner_to_timeline_media']['page_info']) && isset($media['data']['user']['edge_owner_to_timeline_media']['page_info']['has_next_page']) && $media['data']['user']['edge_owner_to_timeline_media']['page_info']['has_next_page'] && isset($media['data']['user']['edge_owner_to_timeline_media']['page_info']['end_cursor'])){
								$cursor = $media['data']['user']['edge_owner_to_timeline_media']['page_info']['end_cursor'];
							}
						}
						else{
							break;
						}
					}
					unset($media);	
				}
			}
			unset($userData);
		}
		else if($source && $source['type']=='hashtag'){
            $cursor = '';
            for($step=0;$step<5;$step++){
                if($step>0 && !$cursor){
                    break;
                }
                $media = self::getData('https://www.instagram.com/graphql/query/?query_id=17882293912014529&tag_name='.urlencode($source['value']).'&first=200'.($step>0?'&after='.$cursor:''),array(),$cacheLifetime);
                if(isset($media['data']['hashtag']['edge_hashtag_to_media']['edges']) && is_array($media['data']['hashtag']['edge_hashtag_to_media']['edges'])){
                    foreach($media['data']['hashtag']['edge_hashtag_to_media']['edges'] as $node){
                        if(isset($node['node']) && is_array($node['node'])){
                            $result[] = self::makeGalleryItem($node['node'],$album);
                        }
                    }
                    $cursor = '';
                    if(isset($media['data']['hashtag']['edge_hashtag_to_media']['page_info']['has_next_page']) && $media['data']['hashtag']['edge_hashtag_to_media']['page_info']['has_next_page']){
                        $cursor = $media['data']['hashtag']['edge_hashtag_to_media']['page_info']['end_cursor'];
                    }
                    else{
                        break;
                    }
                }
                else{
                    break;
                }
            }
		}
        else if($source && $source['type']=='location'){
            $cursor = '';
            for($step=0;$step<5;$step++){
                if($step>0 && !$cursor){
                    break;
                }
                $media = self::getData('https://www.instagram.com/graphql/query/?query_id=17881432870018455&id='.$source['value'].'&first=200'.($step>0?'&after='.$cursor:''),array(),$cacheLifetime);
                if(isset($media['data']['location']['edge_location_to_media']['edges']) && is_array($media['data']['location']['edge_location_to_media']['edges'])){
                    foreach($media['data']['location']['edge_location_to_media']['edges'] as $node){
                        if(isset($node['node']) && is_array($node['node'])){
                            $result[] = self::makeGalleryItem($node['node'],$album);
                        }
                    }
                    $cursor = '';
                    if(isset($media['data']['location']['edge_location_to_media']['page_info']['has_next_page']) && $media['data']['location']['edge_location_to_media']['page_info']['has_next_page']){
                        $cursor = $media['data']['location']['edge_location_to_media']['page_info']['end_cursor'];
                    }
                    else{
                        break;
                    }
                }
                else{
                    break;
                }
            }
		}
		else if($source && $source['type']=='picture'){
			$data = self::getData('https://www.instagram.com/p/'.$source['value'].'/?__a=1',array(),$cacheLifetime);
			if(isset($data['media'])){
				$result[] = self::makeGalleryItem($data['media'],$album,$bindData);
			}
            else if(isset($data['graphql']['shortcode_media'])){
                $result[] = self::makeGalleryItem($data['graphql']['shortcode_media'],$album,$bindData);
            }
		}
        
		return $result;
	}
	
	static function makeGalleryItem($node,$album,$bindData=array()){
		$item = array_merge(array(
			'caption' => '',
		),$bindData,$node);
		$item['album_id'] = $album['id'];

		$item['video_url'] = (isset($item['video_url'])?$item['video_url']:'');
		$item['is_video'] = (isset($item['is_video'])?$item['is_video']:false);
		$item['thumbnail_src'] = (isset($item['thumbnail_src'])?$item['thumbnail_src']:@$item['display_src']);
        $item['thumbnail_src'] = (isset($item['display_url'])?$item['display_url']:$item['thumbnail_src']);
        $item['date'] = (isset($item['taken_at_timestamp'])?$item['taken_at_timestamp']:$item['date']);
        $item['code'] = (isset($item['shortcode'])?$item['shortcode']:$item['code']);
		if(isset($item['edge_media_preview_like'])){
			$item['likes'] = $item['edge_media_preview_like'];
		}
		else{
			$item['likes'] = (isset($item['edge_liked_by'])?$item['edge_liked_by']:@$item['likes']);
		}
        $item['comments'] = (isset($item['edge_media_to_comment'])?$item['edge_media_to_comment']:$item['comments']);
        $item['display_src'] = (isset($item['display_url'])?$item['display_url']:$item['display_src']);
        $item['caption'] = (isset($item['edge_media_to_caption'])?$item['edge_media_to_caption']['edges'][0]['node']['text']:$item['caption']);
        
        if(isset($item['edge_media_to_caption'])){
            unset($item['edge_media_to_caption']);
        }
        
        if(!isset($item['owner']['username']) && isset($item['owner']['id'])){
            $item['owner'] = self::getUserByID($item['owner']['id'],$item['code']);
        }
        
        preg_match_all('~\#(\w+)~u',$item['caption'],$hashtags);
		$item['hashtags'] = array();
		if(isset($hashtags[1]) && is_array($hashtags[1])){
			foreach($hashtags[1] as $hashtag){
				$item['hashtags'][] = $hashtag;
			}
		}
        
        if($item['is_video']){
            $videoData = self::getData('https://www.instagram.com/p/'.$item['code'].'/?__a=1',array(),604800);
            if(isset($videoData['graphql']) && isset($videoData['graphql']['shortcode_media']) && isset($videoData['graphql']['shortcode_media']['video_url'])){
                $item['video_url'] = $videoData['graphql']['shortcode_media']['video_url'];
            }
        }
        
        $item['subgallery'] = array();
        if(isset($node['__typename']) && $node['__typename']=='GraphSidecar' && !isset($node['edge_sidecar_to_children'])){
            $gallery = self::getMediaListFromSource('https://www.instagram.com/p/'.$item['code'].'/',$album);
            if(count($gallery)==1){
                $gallery = $gallery[0];
            }
            if(
                    isset($gallery['edge_sidecar_to_children']) 
                    && isset($gallery['edge_sidecar_to_children']['edges']) 
                    && is_array($gallery['edge_sidecar_to_children']['edges'])
                ){
                
                foreach($gallery['edge_sidecar_to_children']['edges'] as $imgItem){
                    if(
                            isset($imgItem['node']) 
                            && is_array($imgItem['node']) 
                            && isset($imgItem['node']['__typename'])
                            && isset($imgItem['node']['display_url'])
                            && $imgItem['node']['__typename']=='GraphImage'
                            && $imgItem['node']['display_url']!=''
                        ){
                        $img = array(
                            'src' => $imgItem['node']['display_url'],
                            'width' => $imgItem['node']['dimensions']['width'],
                            'height' => $imgItem['node']['dimensions']['height'],
                        );
                        $item['subgallery'][] = $img;
                    }
                }
            }
        }
        else if(isset($node['edge_sidecar_to_children']) && isset($node['edge_sidecar_to_children']['edges']) && is_array($node['edge_sidecar_to_children']['edges'])){
            $item['subgallery'] = array();

            foreach($node['edge_sidecar_to_children']['edges'] as $imgItem){
                if(
                        isset($imgItem['node']) 
                        && is_array($imgItem['node']) 
                        && isset($imgItem['node']['__typename'])
                        && isset($imgItem['node']['display_url'])
                        && $imgItem['node']['__typename']=='GraphImage'
                        && $imgItem['node']['display_url']!=''
                    ){
                    $img = array(
                        'src' => $imgItem['node']['display_url'],
                        'width' => $imgItem['node']['dimensions']['width'],
                        'height' => $imgItem['node']['dimensions']['height'],
                    );
                    $item['subgallery'][] = $img;
                }
            }
        }
		return $item;
	}
    
	static function setCookie($cookie){
		$cacheID = 'ing_cookie';
		set_transient($cacheID, json_encode($cookie), 172800);
	}
	
	static function getCookie(){
		$cacheID = 'ing_cookie';
		$cached = get_transient($cacheID);
		if($cached!==false){
			return json_decode($cached,true);
		}
		$params = array( 
			'method' => 'GET',
			'headers' => array(
				'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
				'Origin' => 'https://www.instagram.com',
				'Referer' => 'https://www.instagram.com',
				'Connection' => 'close'
			)
		);
		if(!class_exists('WP_Http')) include_once(ABSPATH.WPINC.'/class-http.php'); 
		if(!class_exists('WP_Http_Cookie')) include_once(ABSPATH.WPINC.'/class-wp-http-cookie.php'); 
		$request = new WP_Http;
		$result = $request->request('https://www.instagram.com/p/BKTCB3HAYvn/', $params);
		if(is_wp_error($result)) {
		   throw new Exception('WP Error: '.$result->get_error_message());
		}
		$newCookie = array();
		if(isset($result['cookies']) && is_array($result['cookies'])){
			foreach($result['cookies'] as $_cookie){
				if($_cookie instanceof WP_Http_Cookie){
					$newCookie[$_cookie->name] = $_cookie->value;
				}
			}
			self::setCookie($newCookie);
		}
		return $newCookie;
	}
			
	static function getData($url,$post,$cacheLifetime,$strict=false){
		$cookie = self::getCookie();
		$cacheID = 'ing_r_'.md5($url.(count($post)?http_build_query($post):''));
		$cached = get_transient($cacheID);
		if($cacheLifetime>0 && $cached!==false){
			return json_decode($cached,true);
		}
		else{
			delete_transient($cacheID);
		}
		
		if(!class_exists('WP_Http')) include_once(ABSPATH.WPINC.'/class-http.php'); 
		if(!class_exists('WP_Http_Cookie')) include_once(ABSPATH.WPINC.'/class-wp-http-cookie.php'); 
		$params = array( 
			'method' => 'GET',
			'headers' => array(
				'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
				'Origin' => 'https://www.instagram.com',
				'Referer' => 'https://www.instagram.com',
				'Connection' => 'close'
			)
		);
		if(count($post)){
			$params['method'] = 'POST';
			$params['body'] = $post;
		}
		if(count($cookie)){
			$params['headers']['X-Csrftoken'] = $cookie['csrftoken'];
			$params['headers']['X-Requested-With'] = 'XMLHttpRequest';
			$params['headers']['X-Instagram-Ajax'] = '1';
			$params['headers']['Cookie'] = array();
			foreach($cookie as $k=>$v){
				$params['headers']['Cookie'][] = $k.'='.$v;
			}
			$params['headers']['Cookie'] = implode('; ',$params['headers']['Cookie']);
		}
		$request = new WP_Http;
		$result = $request->request($url, $params);
		
		if(is_wp_error($result)) {
		   throw new Exception('WP Error: '.$result->get_error_message());
		}
		if($result['response']['code'] != 200) {
			self::logError('Instagram server responce error. Request: "'.$url.'". Responce:'.(isset($result['body'])?'<pre>'.print_r($result['body'],true).'</pre>':''));
			if($strict){
				throw new Exception('Instagram server responce error. Request: "'.$url.'". Responce:'.(isset($result['body'])?'<pre>'.print_r($result['body'],true).'</pre>':''));
			}
			else{
				$result['body'] = '{}';
			}
		}
		if(count($cookie)==0 && isset($result['cookies']) && is_array($result['cookies'])){
			$newCookie = array();
			foreach($result['cookies'] as $_cookie){
				if($_cookie instanceof WP_Http_Cookie){
					$newCookie[$_cookie->name] = $_cookie->value;
				}
			}
			self::setCookie($newCookie);
		}
		if(!isset($result['body']) || !self::isJSON($result['body'])){
			throw new Exception('Instagram server invalid responce');
		}
		if($cacheLifetime>0){
			set_transient($cacheID, $result['body'], $cacheLifetime);
		}
		return json_decode($result['body'],true);
	}
	
	static function filterMedia($items,$filters){
		$result = array();
		if(count($filters['except'])==0 && count($filters['only'])==0){
			return $items;
		}
		$addedIDs = array();
		if(count($filters['only'])>0){
			foreach($filters['only'] as $onlySource){
				foreach($items as $item){
                    if(self::itemMatchFilter($item,$onlySource) && !in_array($item['code'],$addedIDs)){
                        $result[] = $item;
                        $addedIDs[] = $item['code'];
                    }
                }
			}
		}
		else{
			$result = $items;
		}
		if(count($filters['except'])>0){
			$exIDs = array();
			foreach($filters['except'] as $exSource){
				if(self::itemMatchFilter($item,$exSource)){
                    $exIDs[] = $item['code'];
                }
			}
			$result2 = array();
			foreach($result as $item){
				if(!in_array($item['code'],$exIDs)){
					$result2[] = $item;
				}
			}
			$result = $result2;
		}
		
		return $result;
	}
    
    static function itemMatchFilter($item,$filter){
        if(strpos($filter,'&')!==false){
            foreach(explode('&',$filter) as $filterElem){
                if(!self::itemMatchFilter($item,$filterElem)){
                    return false;
                }
            }
            return true;
        }
        else if($filter=='videos'){
            if($item['is_video']){
                return true;
            }
        }
        else{
            $source = self::getSourceData($filter);
            if($source){
                if($source['type']=='user' && $source['value']==$item['owner']['username']){
                    return true;
                }
                else if($source['type']=='hashtag' && in_array($source['value'],$item['hashtags'])){
                    return true;
                }
                else if($source['type']=='picture' && $source['value']==$item['code']){
                    return true;
                }
            }
        }
        return false;
    }
	
	static function orderMedia($a,$b){
		if($a['date'] == $b['date']) {
			return 0;
		}
		return ($a['date'] < $b['date']) ? 1 : -1;

	}
	
	static function getPreviewItem($item){
		$result = array(
			'id' => $item['id'],
			'code' => $item['code'],
			'date' => $item['date'],
			'time_passed' => self::getTimePassed($item['date']),
			'full_date' => date('d F Y',$item['date']),
			'date_iso' => date('c',$item['date']),
			'likes' => (int)$item['likes']['count'],
			'comments' => $item['comments']['count'],
			'video_url' => $item['video_url'],
			'owner_id' => $item['owner']['id'],
			'owner_username' => $item['owner']['username'],
			'owner_name' => $item['owner']['full_name'],
			'owner_pic_url' => $item['owner']['profile_pic_url'],
			'is_video' => $item['is_video'],
			'display_src' => $item['display_src'],
			'full_width' => $item['dimensions']['width'],
			'full_height' => $item['dimensions']['height'],
			'ratio' => round((int)$item['dimensions']['width']/(int)$item['dimensions']['height'],5),
			'code' => $item['code'],
			'caption' => self::getMediaDescription($item['caption']),
            'subgallery' => array()
		);
        foreach($item['subgallery'] as $subitem){
            $subitem['ratio'] = round((int)$subitem['width']/(int)$subitem['height'],5);
            $result['subgallery'][] = $subitem;
        }
		return $result;
	}
	
	static function getTimePassed($time){
		$time = max(time() - $time,1);
		$tokens = array (
			31536000 => __('year','ingallery'),
			2592000 => __('month','ingallery'),
			604800 => __('week','ingallery'),
			86400 => __('day','ingallery'),
			3600 => __('hour','ingallery'),
			60 => __('minute','ingallery'),
			1 => __('second','ingallery')
		);
		$tokensPL = array (
			31536000 => __('years','ingallery'),
			2592000 => __('months','ingallery'),
			604800 => __('weeks','ingallery'),
			86400 => __('days','ingallery'),
			3600 => __('hours','ingallery'),
			60 => __('minutes','ingallery'),
			1 => __('seconds','ingallery')
		);
		
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.__($text.(($numberOfUnits>1)?'s':'').' ago','ingallery');
		}
	}

	static function getMediaDescription($str){
		$result = preg_replace_callback('~((?:\@[a-z0-9_\-\.]+)|(?:\#\w+)|(?:https?\:\/\/[^\s]+)|(?:www\.[^\s]+))~u',function($mchs){
			$type = substr($mchs[1],0,1);
			$value = substr($mchs[1],1);
			if($type=='@'){
				return '<a href="https://www.instagram.com/'.$value.'/" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
			else if($type=='#'){
				return '<a href="https://www.instagram.com/explore/tags/'.$value.'/" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
			else if($type=='h'){
				return '<a href="'.$mchs[1].'" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
			else if($type=='w'){
				return '<a href="http://'.$mchs[1].'" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
		},$str);
		return $result;
	}
	
	static function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	
	static function getGalleries(){
		$args = wp_parse_args(array(
			'post_status' => 'any',
			'posts_per_page' => 50,
			'offset' => 0,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => ingalleryModel::ING_WP_POST_TYPE
		));
		$q = new WP_Query();
		$posts = $q->query( $args );
		$galleries = array();
		foreach($posts as $post){
			$gal = new ingalleryModel();
			if($gal->bindPost($post)){
				$galleries[] = $gal;
			}
		}
		unset($posts);
		return $galleries;
	}
    
    static function renderShortcode($atts){
		$attribs = shortcode_atts(array('id'=>0),$atts);
		return '<div class="ingallery-container" data-id="'.$attribs['id'].'"></div>';
	}
	
	static function widgetsInit(){
		require_once INGALLERY_PATH.'widgets/wp_widget_ingallery.php';
		register_widget( 'wp_widget_ingallery' );
	}
	
	static function logError($error){
		if(!WP_DEBUG){
			return;
		}
		$str = date('d.m.Y H:i:s')."\t".$error."\n#####################################################\n";
		$logFile = INGALLERY_PATH.'logs/error.log';
		@file_put_contents($logFile,$str,FILE_APPEND);
	}
    
    public static function httpRequestArgs($r){
        $r['timeout'] = 15;
        return $r;
    }
    
	public static function httpApiCurl($handle){
        curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 15 );
        curl_setopt( $handle, CURLOPT_TIMEOUT, 15 );
    }
}
