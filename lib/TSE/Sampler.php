<?php
/**
 * Description of Sampler
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Sampler implements TSE_Log_Loggable
{
    protected $username;

    protected $pasword;

    protected $twiterUrl;

    protected $errorCount;

    /**
     * HTTP Transport
     *
     * @var Zend_Http_Client
     */
    protected $transport;

    /**
     * Logger
     *
     * @var TSE_Log_Logger
     */
    protected $log;

    const LANG_EN = 'en';

    const RPP = 100;

    const CONTEXT_POS = 1;

    const CONTEXT_NEG = 2;

    const GLYPH_POS = ':)';

    const GLYPH_NEG = ':(';

    const PAGE_NUM_REP = '<--PREP-->';

    const ERROR_LIMIT = 5;

    public function __construct($config, Zend_Http_Client $transport)
    {
        $this->twitterUrl = $config['twitterSearchUrl'];
        $this->transport = $transport;
    }

    public function attachLogger(TSE_Log_Logger $logger)
    {
        $this->log = $logger;
    }

    public function loggerAttached()
    {
        return (isset($this->log));
    }

    public function getSample($term, $size)
    {
        $url = $this->createUrl($term, self::CONTEXT_POS);
        
        $posRes = $this->getResults($url, $size);

        $size = count($posRes);

        $url = $this->createUrl($term, self::CONTEXT_NEG);

        $negRes = $this->getResults($url, $size);

        return array("p" => $posRes, "n" => $negRes);
    }

    protected function getResults($urlTemp, $size)
    {
        $pagesReq = ceil($size / self::RPP);

        $results = array();

        for($i=1; $i <= $pagesReq; $i++)
        {
            $url = str_replace(self::PAGE_NUM_REP, $i, $urlTemp);
            $results = array_merge($results, $this->query($url));
            sleep(1);
        }

        return $results;
    }

    protected function query($url)
    {
        $this->transport->setUri($url);
        $this->transport->setMethod(Zend_Http_Client::GET);

        if($this->loggerAttached())
        {
            $this->log->info('Querying Twitter On: ' . $url);
        }

        $result = $this->transport->request();

        return $this->handleResult($result, $url);
    }

    protected function handleResult(Zend_Http_Response $result, $url)
    {
        
        if($this->loggerAttached())
        {
            $this->log->info('Twitter Response: ' . $result->getStatus());
        }

        switch($result->getStatus())
        {
            case '500':

                if($this->errorCount > self::ERROR_LIMIT)
                {
                    sleep(3);
                }

                $this->errorCount++;

                return $this->query($url);

                break;

            case 400:

                throw new RuntimeException('');
                break;

            case 200:

                $this->errorCount = 0;

                $body = $result->getBody();

                $bodyAr = json_decode($body, true);

                $resAr = array();

                foreach($bodyAr['results'] as $k => $value)
                {
                    $resAr[] = $value['text'];
                }

                return $resAr;

                break;
                default:
                    break;
        }
    }

    protected function createUrl($term, $context)
    {

        if($context == self::CONTEXT_POS)
        {
            $term .= '+' . self::GLYPH_POS;
        }
        elseif($context == self::CONTEXT_NEG)
        {
            $term .= '+' . self::GLYPH_NEG;
        }
        
        $params['q'] = 'q='. urlencode($term);
        $params['lang'] = 'lang=' . self::LANG_EN;
        $params['rpp'] = 'rpp=' . self::RPP;
        $params['type'] = 'result_type=recent';
        $params['page'] = self::PAGE_NUM_REP;

        $url = $this->twitterUrl . '?' . implode('&', $params);
    
        return $url;
    }
    
    
}

