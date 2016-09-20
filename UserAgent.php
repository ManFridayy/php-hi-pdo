<?php
    class UserAgent
    {
        private $userAgent;
        private $browser;
        private $browserLength;
        private $cBrowser;

        private function getBrowser()
        {
            for($uaSniff=0;$uaSniff < $this->browserLength;$uaSniff ++)
            {
                if(strstr($this->userAgent,$this->browser[$uaSniff]))
                {
                    $this->cBrowser = $this->browser[$uaSniff];
                    return $this->browser[$uaSniff];
                }
            }
        }
        
        public function __construct()
        {
            $this->userAgent=$_SERVER['HTTP_USER_AGENT'];
            $this->userAgent=strtolower($this->userAgent);
            $this->browser= array('firefox', 'chrome', 'opera', 'msie', 'safari', 'hi');
            $this->browserLength=count($this->browser);

            $this->getBrowser();
        }

        public function getUserAgent()
        {
            return $this->userAgent;
        }

        public function getCurrentBrowser()
        {
            return $this->cBrowser;
        }

        public function isValidAgent($agent = "hi")
        {
            return (strcmp($this->cBrowser, $agent) == 0);
        }

    }
?>
