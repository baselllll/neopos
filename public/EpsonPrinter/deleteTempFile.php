<?php
$data = json_decode(file_get_contents("php://input"));

if (isset($data->pdfFileUrl)) {
    $filePath = './temp/' . basename($data->pdfFileUrl);
    if (file_exists($filePath)) {
        unlink($filePath);
        echo json_encode(['success' => true, 'message' => 'File deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'File does not exist.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}
?>
