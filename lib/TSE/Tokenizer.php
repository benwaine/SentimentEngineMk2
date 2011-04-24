<?php
/**
 * Description of Tokenizer
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Tokenizer
{

    private $startsWith;

    private $smilies;

    private $punctuation;

    public function __construct($config)
    {

        $this->startsWith = $config['startsWith'];
        $this->smilies = $config['smilies'];
        $this->punctuation = $config['punctuation'];

    }

    public function tokenizse($string)
    {
        $string = $this->lowerCase($string);
        $string = $this->removeSmilies($string);
        $string = $this->removeURLs($string);
        $tokens = $this->split($string);
        $tokens = $this->removeStartsWith($tokens);
        $tokens = $this->removePunctuation($tokens);

        return $tokens;
    }

    protected function split($string)
    {
        $tokens = explode(' ', $string);

        foreach($tokens as &$token)
        {
            $token = trim($token);
        }

        return $tokens;
    }

    protected function removeStartsWith($tokens)
    {
        foreach($tokens as $key => &$token)
        {
            foreach($this->startsWith as $val)
            {
                if(strpos($token, $val) === 0)
                {
                    unset($tokens[$key]);
                }
            }
        }

        return $tokens;
    }

    protected function removePunctuation($tokens)
    {
        //var_dump($this->punctuation);die;
        foreach($tokens as $key => &$token)
        {
            $token = preg_replace($this->punctuation, '', $token);
            
            if(strlen($token) == 0)
            {
                unset($tokens[$key]);
            }
        }

        return $tokens;
    }

    protected function removeURLs($string)
    {
        return preg_replace("/http:\/\/(.*?)\/\.?$/", '', $string);
    }

    protected function removeSmilies($string)
    {
        
        return preg_replace($this->smilies, '', $string);
    }

    protected function lowerCase($string)
    {
        return strtolower($string);
    }
}

