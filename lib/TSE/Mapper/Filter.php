<?php
/**
 * Description of Filter
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Mapper_Filter extends TSE_Mapper_Abstract
{
    private $tokenizerConfig;

    public function __construct($pdo, $tokenizerConfig)
    {
        parent::__construct($pdo);

        $this->tokenizerConfig = $tokenizerConfig;
    }

    public function getFilterByKeyword($keyword)
    {
        $sql = 'SELECT f.id, f.keyword_id, f.filter_probs, f.sample_size_pos, f.sample_size_neg
                FROM filter f
                JOIN keyword k ON k.id = f.keyword_id
                WHERE k.keyword = :keyword';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':keyword', $keyword);

        $result = $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $tokenizer = new TSE_Tokenizer($this->tokenizerConfig);

        $probArray = unserialize($data['filter_probs']);

        $filter = new TSE_Filter($probArray, $tokenizer);

        return $filter;
    }

    public function insertFilterForKeyword($id, array $filterProbs)
    {

        $sql = "INSERT INTO filter (keyword_id, filter_probs, sample_size_pos, sample_size_neg)
                VALUE (:id, :filterProbs, :pos, :neg)";

        $stmt = $this->pdo->prepare($sql);

        $meta = array_shift($filterProbs);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':filterProbs', serialize($filterProbs));
        $stmt->bindParam(':pos', $meta['sample_size_pos']);
        $stmt->bindParam(':neg', $meta['sample_size_neg']);

        $result = $stmt->execute();

        return $result;
    }

}

