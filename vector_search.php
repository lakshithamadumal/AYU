<?php
class VectorSearch {
    private $embeddings = [];

    // Constructor එක
    public function __construct($filePath) {
        $this->loadEmbeddings($filePath);
    }

    // JSON embeddings load කිරීම
    private function loadEmbeddings($filePath) {
        if (!file_exists($filePath)) {
            die("Embedding file not found!");
        }

        $json = file_get_contents($filePath);
        $this->embeddings = json_decode($json, true);
    }

    // පරිශීලක query එක vector එකකට හැරවීම
    private function getVector($sentence) {
        $words = explode(" ", $sentence);
        $vector = array_fill(0, 300, 0.0);
        $count = 0;

        foreach ($words as $word) {
            if (isset($this->embeddings[$word])) {
                for ($i = 0; $i < 300; $i++) {
                    $vector[$i] += $this->embeddings[$word][$i];
                }
                $count++;
            }
        }

        if ($count > 0) {
            for ($i = 0; $i < 300; $i++) {
                $vector[$i] /= $count;
            }
        }

        return $vector;
    }

    // Cosine Similarity ගණනය කිරීම
    private function cosineSimilarity($vec1, $vec2) {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < 300; $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $normA += pow($vec1[$i], 2);
            $normB += pow($vec2[$i], 2);
        }

        return ($normA == 0 || $normB == 0) ? 0 : $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    // `$qaDatabase` තුළ හොඳම ගැලපෙන පිළිතුරක් සෙවීම
    public function findBestMatch($query, $database) {
        $queryVector = $this->getVector($query);
        $bestMatch = "";
        $bestScore = -1;

        foreach ($database as $question => $answer) {
            $questionVector = $this->getVector($question);
            $similarity = $this->cosineSimilarity($queryVector, $questionVector);
            if ($similarity > $bestScore) {
                $bestScore = $similarity;
                $bestMatch = $answer;
            }
        }

        return ($bestScore > 0.5) ? $bestMatch : ""; // Threshold එකට වඩා similarity score එකක් තිබුණොත් return කරන්න
    }

    // `$qaDatabase` හි නැති විට, `health_vectors.json` එකෙන් සෙවීම
    public function findVectorMatch($query) {
        $queryVector = $this->getVector($query);
        $bestMatch = "";
        $bestScore = -1;

        foreach ($this->embeddings as $word => $vector) {
            $similarity = $this->cosineSimilarity($queryVector, $vector);
            if ($similarity > $bestScore) {
                $bestScore = $similarity;
                $bestMatch = $word;
            }
        }

        return ($bestScore > 0.4) ? "මෙයට අදාල වචනය: $bestMatch" : ""; // Threshold එකක් භාවිත කරන්න
    }
}
?>
