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

    // Cosine Similarity ගණනය කිරීම
    private function cosineSimilarity($vec1, $vec2) {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $normA += pow($vec1[$i], 2);
            $normB += pow($vec2[$i], 2);
        }

        return ($normA == 0 || $normB == 0) ? 0 : $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    // `$qaDatabase` තුළ හොඳම ගැලපෙන පිළිතුරක් සෙවීම
    public function findBestMatch($query, $database) {
        $queryWords = explode(" ", $query);
        $bestMatch = "";
        $bestScore = -1;

        foreach ($database as $question => $answer) {
            $questionWords = explode(" ", $question);
            $commonWords = array_intersect($queryWords, $questionWords);

            $similarity = count($commonWords) / max(count($queryWords), count($questionWords));
            if ($similarity > $bestScore) {
                $bestScore = $similarity;
                $bestMatch = $answer;
            }
        }

        // Threshold එකට වඩා similarity score එකක් තිබුණොත් return කරන්න
        return ($bestScore > 0.5) ? $bestMatch : "";
    }
}
?>
