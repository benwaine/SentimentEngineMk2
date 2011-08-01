<?php

/**
 * Description of FilterMapper
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Debug_FilterMapper extends TSE_Mapper_Abstract
{

    static $hash;

    static $db;

    public static function filterToDB($probArray)
    {

        if(!isset($hash))
        {
            self::$hash = md5(time());
        }

        $hash = md5(time());

        $sql = "INSERT INTO debug_filter (hash, keyword, positive, negative)
                       VALUE (:hash, :word, :positive, :negative)";

        $meta = array_shift(($probArray));

        foreach($probArray as $term => $probs)
        {
            $stmt = self::$db->prepare($sql);
            $stmt->bindParam(':hash', self::$hash);
            $stmt->bindParam(':word', $term);
            $stmt->bindParam(':positive', $probs['p']);
            $stmt->bindParam(':negative', $probs['n']);

            $stmt->execute();
        }

        echo "\n RUN ID: $hash \n";
    }

    public static function samplerToDb($wordCounts)
    {
        if(!isset($hash))
        {
            self::$hash = md5(time());
        }

        $sql = 'INSERT INTO debug_sampling (hash, word, pcount, ncount)
                VALUES (:hash, :word, :pcount, :ncount)';

        foreach($wordCounts as $key => $value)
        {
            $stmt = self::$db->prepare($sql);
            $stmt->bindParam(':hash', self::$hash);
            $stmt->bindParam(':word', $key);
            $stmt->bindParam(':pcount', $value['p']);
            $stmt->bindParam(':ncount', $value['n']);

            $stmt->execute();
        }
    }

}

