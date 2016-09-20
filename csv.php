<?php
//csv.php
//Display error for dirty debug
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);

require 'Database.php';
require 'ResponseFactory.php';
include('UserAgent.php');

// Set a valid header so browsers pick it up correctly.
header('Content-type: text/html; charset=utf-8');

// Emulate the header BigPipe sends so we can test through Varnish.
header('Surrogate-Control: BigPipe/1.0');

// Explicitly disable caching so Varnish and other upstreams won't cache.
header("Cache-Control: no-cache, must-revalidate");

// Setting this header instructs Nginx to disable fastcgi_buffering and disable
// gzip for this request.
header('X-Accel-Buffering: no');

header("Pragma: no-cache");

function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}

try 
{
	$ua = new UserAgent();
	if ( $ua->isValidAgent('firefox') ) 
    {
		$stm = removeslashes($_REQUEST['sql']);

        if ( isset($stm) && !empty($stm) && !is_null($stm) )
        {
            $db = Database::getInstance();
            $stmt = $db->prepare($stm);
            $stmt->execute();

            if ( $stmt->fetchColumn() > 0 ) 
            {
                $response = ResponseFactory::Create('Seperate', $stmt);
                //$response = ResponseFactory::Create('SeperateEach', $stmt);
                //$response = ResponseFactory::Create('Json', $stmt);
                $response->Write();

                echo '<hr>';
                var_dump(memory_get_usage());
                var_dump(memory_get_peak_usage());                
            }
        }
	}
    else 
    {
		echo 'Invalid';
	}    
}
catch (Exception $e) 
{
    echo 'Error: '.$e->getMessage();
	exit;
}
?>