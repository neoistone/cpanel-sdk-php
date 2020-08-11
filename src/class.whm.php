<?PHP 

namespace neoistone\cpsdk;
class WHM
{
	private $HOST = '';
	private $USER = '';
	private $PASS = '';
	private $HASH = '';


	public function __construct( $SSL , $IP , $USER , $HASH , $HASH_IS_PASS = true )
	{
		$HOST = ( $SSL ) ? ( 'https://' . $IP . ':2087' ) : ( 'http://' . $IP . ':2086' );
		$this->HOST = $HOST;
		$this->USER = $USER;
		
		if( $HASH_IS_PASS  )
		{
			$this->PASS = $HASH;
		} else
		{
			$this->HASH = $HASH;
		}
		return true;
	}
	private function open( $page )
	{
		$curl = curl_init();
		$url = $this->HOST . '/json-api/' . $page;
		curl_setopt( $curl , CURLOPT_SSL_VERIFYHOST , 0 ); 
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , 0 );
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , 1 );  
		if( empty( $this->HASH ) )
		{
			$header[0] = 'Authorization: Basic ' . base64_encode( $this->USER . ':' . $this->PASS );
		} else
		{
			$header[0] = 'Authorization: WHM ' . $this->USER . ":" . $this->HASH;
		}
		curl_setopt( $curl , CURLOPT_URL , $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt( $curl, CURLOPT_HTTPHEADER , $header );
		$data = curl_exec( $curl ); 
		if( $data  == false )
		{
			return false;
		}
		return $data;
	}
	private function get_size( $mbytes )
	{
		if( is_numeric( $mbytes ) )
		{
			if( $mbytes >= 10485764 )
			{
				$size = round( $mbytes / 10485764 , 2 ) . ' TB';
			} else
			if( $mbytes >= 1024 )
			{
				$size = round( $mbytes / 1024 , 2 ) . ' GB';
			} else
			{
				$size = $mbytes . ' MB';
			}
		} else
		{
			$size = 'Unknown';
		}
		return $size;
	}
	public function getResult( $data )
	{
		$object = json_decode( $data );
		return $object->result[0];
	}
	public function getResult2( $data )
	{
		return json_decode( $data );
	}
	private function make_random_password( $length = 12 )
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!$()|';
		$chars_length = ( strlen( $chars ) - 1 );
		$string = $chars{ mt_rand( 0 , $chars_length ) };
	   
		for( $i = 1; $i < $length; $i = strlen( $string ) )
		{
			$r = $chars{ mt_rand( 0 , $chars_length ) };
			if( $r != $string{ $i - 1 } ) $string .= $r;
		}
		return $string;
	}
	private function make_random_username( $length = 8 )
	{
		$chars = 'abcdefghijklmnopqrstuvwxyz123456789';
		$chars_length = ( strlen( $chars ) - 1 );
		$string = $chars{ mt_rand( 0 , $chars_length ) };
	   
		for( $i = 1; $i < $length; $i = strlen( $string ) )
		{
			$r = $chars{ mt_rand( 0 , $chars_length ) };
			if( $r != $string{ $i - 1 } ) $string .= $r;
		}
		return $string;
	}
	public function create_account($config){
		
		$domain = $config['domain'];
		$email = $config['email'];
		$package = $config['package'];
		$username = $this->make_random_username();
		$password = $this->make_random_password();
		
		$d = $this->open( 'createacct?domain=' . $domain. '&username=' . $username . '&useregns=0&reseller=0&ip=n&contactemail=' . $email . '&plan=' . $package . '&password=' . $password );
		$res = $this->getResult( $d );
		
		if( $res->status == 0 )
		{
			return $res->statusmsg;
		} else
		{
			$accinfo = '';
			if( preg_match_all( '/<pre>(.*)<\/pre>/siU' , $res->rawout , $n ) )
			{
				$accinfo = $n[0][1];
			}
			$result = array();
			$result['domain'] = $domain;
			$result['username'] = $username;
			$result['password'] = $password;
			$result['package'] = $package;
			$result['nameserver1'] = $res->options->nameserver;
			$result['nameserver2'] = $res->options->nameserver2;
			$result['nameserver3'] = $res->options->nameserver3;
			$result['nameserver4'] = $res->options->nameserver4;
			$result['account_info'] = $accinfo;
			return $result;
		}
	}
	public function delete_account( $username )
	{
		$d = $this->open( 'removeacct?user=' . $username );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
		}
		return $out;
	}
	public function suspend_account( $username , $reson = '' )
	{
		$d = $this->open( 'suspendacct?user=' . $username . '&reson=' . $reson );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
		}
		return $out;
	}
	public function unsuspend_account( $username )
	{
		$d = $this->open( 'unsuspendacct?user=' . $username );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
		}
		return $out;
	}
	public function change_password_account( $username , $password = '' )
	{
		$password = ( empty( $password ) ? $this->make_random_password() : $password );
		
		$d = $this->open( 'passwd?user=' . $username . '&pass=' . $password );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
			$out[2] = '';
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
			$out[2] = $password;
		}
		return $out;
	}
	public function list_accounts( $search_type = '' , $search_word = '' )
	{
		$allow_search_type = array( 'domain' , 'user' , 'ip' , 'package' );
		
		if( ( $search_type != '' ) && ( in_array( $search_type , $allow_search_type ) ) )
		{
			$url = 'listaccts?searchtype=' . $search_type . '&search=' . $search_word;
		} else
		{
			$url = 'listaccts';
		}
		
		$d = $this->open( $url );
		
		$res = $this->getResult2( $d );
		$acc = $res->acct;
		
		$out = array();
		$i = 0;
		foreach( $acc as $ac )
		{
			$out[$i]['domain'] = $ac->domain;
			$out[$i]['user'] = $ac->user;
			$out[$i]['email'] = $ac->email;
			$out[$i]['startdate'] = $ac->startdate;
			$out[$i]['starttime'] = strtotime( $ac->startdate );
			$out[$i]['disklimit'] = $ac->disklimit;
			$out[$i]['diskused'] = $ac->diskused;
			$out[$i]['ip'] = $ac->ip;
			$out[$i]['suspended'] = $ac->suspended;
			$out[$i]['suspendreason'] = $ac->suspendreason;
			$out[$i]['suspendtime'] = $ac->suspendtime;
			
			$i++;
		}
		return $out;
	}
	public function search_account_by_package( $package )
	{
		return $this->list_accounts( 'package' , $package );
	}
	public function search_account_by_domain( $domain )
	{
		return $this->list_accounts( 'domain' , $domain );
	}
	public function search_account_by_ip( $ip )
	{
		return $this->list_accounts( 'ip' , $ip );
	}
	public function search_account_by_user( $user )
	{
		return $this->list_accounts( 'user' , $user );
	}
	public function limit_user_bandwidth( $username , $new_bandwidth )
	{
		$d = $this->open( 'limitbw?user=' . $username . '&bwlimit=' . $new_bandwidth );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
			$out[2] = '';
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
			$out[2] = $res->bwlimit->human_bwused;
		}
		return $out;
	}
	public function list_packages()
	{
		$d = $this->open( 'listpkgs' );
		$res = $this->getResult2( $d );
		$pkg = $res->package;
		
		$out = array();
		$i = 0;
		foreach( $pkg as $pk )
		{
			$out[$i]['name'] = $pk->name;
			$out[$i]['bandwidth'] = ( $pk->BWLIMIT == 'unlimited' ) ? 'unlimited' : $this->get_size( $pk->BWLIMIT );
			$out[$i]['quota'] = ( $pk->QUOTA == 'unlimited' ) ? 'unlimited' : $this->get_size( $pk->QUOTA );
			$out[$i]['sql'] = $pk->MAXSQL;
			$out[$i]['sub'] = $pk->MAXSUB;
			$out[$i]['park'] = $pk->MAXPARK;
			$out[$i]['addon'] = $pk->MAXADDON;
			$out[$i]['ftp'] = $pk->MAXFTP;
			$out[$i]['pop'] = $pk->MAXPOP;
			$out[$i]['list'] = $pk->MAXLST;
			$out[$i]['ip'] = $pk->IP;
			
			$i++;
		}
		$out = json_encode($out);
		return $out;
	}
	public function add_package( $name , $quota , $bandwidth , $subdomain , $park , $addon , $ftp , $pop , $list , $sql , $feature = 'default' , $ip = 0 , $cgi = 0 , $fronpage = 0 , $lang = 'en' , $theme = 'x3' , $shell = 0 )
	{
		$d = $this->open( 'addpkg?name=' . $name . '&quota=' . $quota . '&bwlimit=' . $bandwidth . '&maxpark=' . $park . '&maxsub=' . $subdomain . '&maxaddon=' . $addon . '&maxpop=' . $pop . '&maxftp=' . $ftp . '&maxlists=' . $list . '&maxsql=' . $sql . '&featurelist=' . $feature . '&ip=' . $ip . '&cgi=' . $cgi . '&frontpage=' . $fronpage . '&language=' . $lang . '&cpmod=' . $theme . '&hasshell=' . $shell );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
		}
		return $out;
	}
	public function edit_package( $name , $quota , $bandwidth , $subdomain , $park , $addon , $ftp , $pop , $list , $sql , $feature = 'default' , $ip = 0 , $cgi = 0 , $fronpage = 0 , $lang = 'en' , $theme = 'x3' , $shell = 0 )
	{
		$d = $this->open( 'editpkg?name=' . $name . '&quota=' . $quota . '&bwlimit=' . $bandwidth . '&maxpark=' . $park . '&maxsub=' . $subdomain . '&maxaddon=' . $addon . '&maxpop=' . $pop . '&maxftp=' . $ftp . '&maxlists=' . $list . '&maxsql=' . $sql . '&featurelist=' . $feature . '&ip=' . $ip . '&cgi=' . $cgi . '&frontpage=' . $fronpage . '&language=' . $lang . '&cpmod=' . $theme . '&hasshell=' . $shell );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
		}
		return $out;
	}
	public function delete_package( $name )
	{
		$d = $this->open( 'killpkg?pkg=' . $name );
		$res = $this->getResult( $d );
		
		$out = array();
		if( $res->status == 0 )
		{
			$out[0] = false;
			$out[1] = $res->statusmsg;
		} else
		{
			$out[0] = true;
			$out[1] = $res->statusmsg;
		}
		return $out;
	}
	public function show_load_avg()
	{
		$d = $this->open( 'loadavg' );
		$res = $this->getResult2( $d );
		
		$out = array();
		$out['now'] = $res->one;
		$out['5min'] = $res->five;
		$out['15min'] = $res->fifteen;
		return $out;
	}
	
	public function login_with_user($cpanel_user){
		$d = $this->open( 'create_user_session?api.version=2&user='.$cpanel_user.'&service=cpaneld' );
      return json_decode( $d, true )['data']['url'];
	}
	public function get_host_name()
	{
		$d = $this->open( 'gethostname' );
		$res = $this->getResult2( $d );
		return $res->hostname;
	}
}
?>
