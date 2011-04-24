<?php

/**
 * Description of FilterGenerator
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Filter
{

    private $probArray;

    private $tokenizer;

    const CONTEXT_POS = 'p';

    const CONTEXT_NEG = 'n';

    public function __construct($probArray, $tokenizer)
    {
        $this->probArray = $probArray;
        $this->tokenizer = $tokenizer;
    }

    public function classify($string)
    {
        $tokens = $this->tokenizer->tokenizse($string);

        $result = $this->applyClassification($tokens);

    }

    protected function applyClassification($tokens)
    {
        // Get individual posibilities for words
        $posProbs = array();
        $negProbs = array();

        foreach($tokens as $token)
        {
            if(array_key_exists($token, $this->probArray))
            {
                if($this->probArray[$token][self::CONTEXT_POS] > 0)
                {
                    $posProbs[] = $this->probArray[$token][self::CONTEXT_POS];
                }

                if($this->probArray[$token][self::CONTEXT_NEG] > 0)
                {
                    $posProbs[] = $this->probArray[$token][self::CONTEXT_NEG];
                }
            }
        }

        // Prob of positive
        if(!empty($posProbs))
        {
            $posMult = array_product($posProbs);
            
            $posMinusAr = array();

            array_walk($posProbs, function($value, $key) use(&$posMinusAr){
               $posMinusAr[] = 1 - $value;
            });

            $posMinusArProd = array_product($posMinusAr);

            $probOfPos = $posMult / $posMult + $posMinusArProd;
        }
        else
        {
            $probOfPos = 0;
        }

        // Prob of negative
        if(!empty($negProbs))
        {
            $negMult = array_product($negProbs);

            $negMinusAr = array();

            array_walk($negProbs, function($value, $key) use(&$negMinusAr){
               $negMinusAr[] = 1 - $value;
            });

            $negMinusArProd = array_product($negMinusAr);

            $probOfNeg = $negMult / $negMult + $negMinusArProd;
        }
        else
        {
            $probOfNeg = 0;
        }

       
        // prob of Negative

        return array(self::CONTEXT_POS => $probOfPos, self::CONTEXT_NEG);
    }


}

