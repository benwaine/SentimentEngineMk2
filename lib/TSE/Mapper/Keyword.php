<?php
/**
 * Description of Keyword
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Mapper_keyword extends TSE_Mapper_Abstract
{

    public function getKeywordById($id)
    {

        $sql = 'SELECT k.id, k.keyword, k.date_tracked
                FROM keyword k
                WHERE k.id = :id';


        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $keyword);

        $result = $stmt->execute();

        $keyword = $stmt->fetch();

        return $keyword;
    }

    public function getKeywordByKeyword($keyword)
    {

        $sql = 'SELECT k.id, k.keyword, k.date_tracked
                FROM keyword k
                WHERE k.keyword = :keyword';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':keyword', $keyword);
      
        $result = $stmt->execute();

        $keyword = $stmt->fetch();

        return $keyword;
    
        
    }

    public function insertKeyword($keyword)
    {
        $sql = 'INSERT INTO keyword (keyword, date_tracked)
                VALUE(:keyword, :date)';

        $stmt = $this->pdo->prepare($sql);

        $date = new DateTime();

        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':date', $date->format('Y-m-d H:i:s'));

        $result = $stmt->execute();

        return $result;

    }

    public function getKeywordsAsText()
    {
        $sql = "SELECT keyword FROM keyword";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $result;
    }


    


}

