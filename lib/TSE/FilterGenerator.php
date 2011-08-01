<?php
/**
 * Description of FilterGenerator
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_FilterGenerator implements TSE_Log_Loggable
{
    /**
     * Tokenizer 
     * 
     * @var TSE_Tokenizer
     */
    private $tokenizer;

    const CONTEXT_POS = 'p';

    const CONTEXT_NEG = 'n';

    public function __construct(TSE_Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    public function createFilter(array $sample)
    {
        $tokens = array(self::CONTEXT_POS => array(), self::CONTEXT_NEG => array());

        foreach($sample[self::CONTEXT_POS] as $samplePos)
        {
            $tokens[self::CONTEXT_POS][] = $this->tokenizer->tokenizse($samplePos);
        }

        foreach($sample[self::CONTEXT_NEG] as $sampleNeg)
        {
            $tokens[self::CONTEXT_NEG][] = $this->tokenizer->tokenizse($sampleNeg);
        }

        $countArray = array('sample_size_pos' => count($tokens[self::CONTEXT_POS]),
                            'sample_size_neg' => count($tokens[self::CONTEXT_NEG]));


        $probs = $this->createWordProbs($tokens);

        array_unshift($probs, $countArray);

        return $probs;

    }

    public function createWordProbs($wcArray)
    {
        $words = array();

        $this->wordCount($wcArray, $words, self::CONTEXT_POS);
        $this->wordCount($wcArray, $words, self::CONTEXT_NEG);

        TSE_Debug_FilterMapper::samplerToDb($words);

        $wordProbs = $this->wordsToProbabilities($words, count($wcArray[self::CONTEXT_POS]), count($wcArray[self::CONTEXT_NEG]));

        return $wordProbs;

    }

    protected function wordsToProbabilities($words, $posSampleSize, $negSampleSize)
    {
        $wordProbs = array();

        foreach($words as $word => $counts)
        {
           $wordProbs[$word][self::CONTEXT_POS] = $counts[self::CONTEXT_POS] / $posSampleSize;
           $wordProbs[$word][self::CONTEXT_NEG] = $counts[self::CONTEXT_NEG] / $negSampleSize;
        }

        return $wordProbs;

    }

    public function wordCount($wcArray, &$words, $context)
    {

        foreach($wcArray[$context] as $wordAr)
        {
            $wordsInMsg = array();

            foreach($wordAr as $word)
            {
                if(array_key_exists($word, $words) && !in_array($word, $wordsInMsg))
                {
                    $words[$word][$context] = $words[$word][$context] + 1;
                }
                else
                {
                    $words[$word] = ($context == self::CONTEXT_POS) ? array(self::CONTEXT_POS => 1, self::CONTEXT_NEG => 0)
                                                                    : array(self::CONTEXT_POS => 0, self::CONTEXT_NEG => 1);
                }

                $wordsInMsg[] = $word;
            }

            $wordsInMsg = null;
        }
    }

    public function attachLogger(TSE_Log_Logger $logger)
    {
        $this->log = $log;
    }

    public function loggerAttached()
    {
        return (isset($this->log));
    }
}

