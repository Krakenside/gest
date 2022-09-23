<?php


// (A) HELPER FUNCTION - SERVER RESPONSE
function verbose($ok = 1, $info = "")
{
  if ($ok == 0) {
    http_response_code(400);
  }
  exit(json_encode(["ok" => $ok, "info" => $info]));
}

// (B) INVALID UPLOAD
if (empty($_FILES) || $_FILES["file"]["error"]) {
  verbose(0, "Failed to move uploaded file.");
}

// (C) UPLOAD DESTINATION - CHANGE FOLDER IF REQUIRED!
$target_path = '../../file/' ;

$target_path_2 = '../site/file/';

$filePath = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
$filePath2 = __DIR__ . DIRECTORY_SEPARATOR ." ../site/file/";
$filePath3 = __DIR__ . DIRECTORY_SEPARATOR . "../../file/";
if (!file_exists($filePath)) {
  if (!mkdir($filePath, 0777, true)) {
    verbose(0, "Failed to create $filePath");
  }
}

if (!file_exists($filePath2)) {
  if (!mkdir($filePath2, 0777, true)) {
    verbose(0, "Failed to create $filePath2");
  }
}if (!file_exists($filePath3)) {
  if (!mkdir($filePath3, 0777, true)) {
    verbose(0, "Failed to create $filePath3");
  }
}
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
$filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;
$filePath2 = $filePath2 . DIRECTORY_SEPARATOR . $fileName;
$filePath3 = $filePath3 . DIRECTORY_SEPARATOR . $fileName;

// (D) DEAL WITH CHUNKS
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
$out = @fopen("{$filePath2}.part", $chunk == 0 ? "wb" : "ab");
$out = @fopen("{$filePath3}.part", $chunk == 0 ? "wb" : "ab");
if ($out) {
  $in = @fopen($_FILES["file"]["tmp_name"], "rb");
  if ($in) {
    while ($buff = fread($in, 4096)) {
      fwrite($out, $buff);  
    }
  } else {
    verbose(0, "Failed to open input stream");
  }
  @fclose($in);
  @fclose($out);
  @unlink($_FILES["file"]["tmp_name"]);
} else {
  verbose(0, "Failed to open output stream");
}

// (E) CHECK IF FILE HAS BEEN UPLOADED
if (!$chunks || $chunk == $chunks - 1) {
  rename("{$filePath}.part", $filePath);
  rename("{$filePath}.part", $filePath2);
  rename("{$filePath}.part", $filePath3);
}
verbose(1, "Upload OK");
