<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "faq.php";

// ==============================================
// ADD ANY NEW PAGES HERE - JUST ADD FILENAMES!
// ==============================================
$knowledgePages = [
    'documentation.php'
    // Just add more files here like: 'services.php', 'about.php', 'pricing.php' etc.
];
// ==============================================

// Unified knowledge base
$allFaq = $faq;

// Automatically process ALL pages from the list
foreach ($knowledgePages as $pageFile) {
    $pagePath = __DIR__ . "/" . $pageFile;
    
    if (file_exists($pagePath)) {
        $html = file_get_contents($pagePath);
        
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        
        $xpath = new DOMXPath($dom);
        
        // First try to find FAQ items (standard format)
        $faqItems = $xpath->query('//div[contains(@class, "faq-item")]');
        foreach ($faqItems as $item) {
            $questionNode = $xpath->query('.//h4', $item)->item(0);
            $answerNode = $xpath->query('.//div[contains(@class, "content-inner")]', $item)->item(0);
            
            if ($questionNode && $answerNode) {
                $allFaq[] = [
                    'question' => trim($questionNode->textContent),
                    'answer' => trim(preg_replace('/\s+/', ' ', $answerNode->textContent))
                ];
            }
        }
        
        // Fallback: extract all main content text from page
        $mainContent = $xpath->query('//main')->item(0);
        if ($mainContent) {
            $pageText = trim(preg_replace('/\s+/', ' ', $mainContent->textContent));
            if (!empty($pageText)) {
                $allFaq[] = [
                    'question' => "Information from $pageFile",
                    'answer' => $pageText
                ];
            }
        }
    }
}

$input = json_decode(
    file_get_contents("php://input"),
    true
);

$prompt = trim($input["prompt"] ?? "");

if(empty($prompt)){

    echo json_encode([
        "error" => "Prompt is required"
    ]);

    exit;
}


// Convert FAQ into AI knowledge
$faqText = "";

foreach($allFaq as $item){

    $faqText .=
        "Question: {$item['question']}\n" .
        "Answer: {$item['answer']}\n\n";
}


// Improved AI instructions
$systemPrompt = "

You are a friendly, intelligent, professional AI virtual assistant named Mira for Eduhive.

Your job is to help users using the documentation information provided below.

IMPORTANT RULES:

1. NEVER copy documentation answers word-for-word every time.
2. Make your responses natural, conversational, friendly, and dynamic.
3. Expand answers professionally when appropriate.
4. Use the documentation information as factual truth.
5. If the user greets you, greet them warmly.
6. If the answer exists in the documentation, provide a helpful detailed response.
7. If the answer is not in the documentation, say you don't know but advise them to contact Dinolabs support team for further assistance.
8. Keep responses accurate and helpful.
9. Avoid sounding robotic or repetitive.

FAQ INFORMATION:

$faqText

";


$url = "https://blockrun.ai/api/v1/chat/completions";

$postData = [

    "model" => "nvidia/llama-4-maverick",

    "messages" => [

        [
            "role" => "system",
            "content" => $systemPrompt
        ],

        [
            "role" => "user",
            "content" => $prompt
        ]
    ]
];


$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);

if(curl_errno($ch)){

    echo json_encode([
        "error" => curl_error($ch)
    ]);

    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if($httpCode !== 200){

    echo json_encode([
        "error" => "API request failed",
        "response" => $response
    ]);

    exit;
}

// Process response for human escalation
$responseData = json_decode($response, true);
if(isset($responseData['choices'][0]['message']['content'])) {
    $aiResponse = trim($responseData['choices'][0]['message']['content']);
    
    // Check if AI requested human escalation
    if(strpos($aiResponse, 'ESCALATE_TO_HUMAN_AGENT') !== false) {
        echo json_encode([
            'escalate' => true,
            'message' => "I'll connect you with one of our customer care agents right away. Please hold while I transfer you to live chat support...",
            'agent_available' => true,
            'wait_time' => "~2 minutes",
            'transfer_status' => "INITIATED"
        ]);
        exit;
    }
}

echo $response;
