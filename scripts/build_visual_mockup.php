<?php

$capturesDir = 'C:/Users/samue/.gemini/antigravity/brain/e42e9735-f386-4222-a7ed-0430452f8d58/mockup_captures';
$dokumenDir = __DIR__ . '/../dokumen';
$slidesOutDir = $dokumenDir . '/mockup_slides';
$hdCapturesDir = 'C:/Users/samue/.gemini/antigravity/brain/e42e9735-f386-4222-a7ed-0430452f8d58/mockup_captures_4k';

foreach ([$dokumenDir, $slidesOutDir, $hdCapturesDir] as $d) {
    if (!is_dir($d)) {
        mkdir($d, 0755, true);
    }
}

$files = [
    '01_hero.png',
    '02_ringkasan.png',
    '03_peta_etnografis.png',
    '04_galeri_digital.png',
    '05_galeri_museum_china.png',
    '06_video_dokumenter.png',
    '07_profil_tim.png',
    '08_katalog.png',
    '09_footer.png',
];

function upscaleSharp($srcIm, int $targetW, int $targetH) {
    imagesetinterpolation($srcIm, IMG_CATMULLROM);
    $scaled = imagescale($srcIm, $targetW, $targetH, IMG_CATMULLROM);
    // Gentle crisp edge enhancement without halo artifacts
    $matrix = [
        [ 0.0,  -0.18,  0.0 ],
        [-0.18,  1.72, -0.18],
        [ 0.0,  -0.18,  0.0 ],
    ];
    imageconvolution($scaled, $matrix, 1.0, 0);
    return $scaled;
}

$slideTargetW = 3840;
$slideTargetH = 2160;

$fullPageW = 2560;
$fullPageImages = [];
$fullPageTotalH = 0;
$pdfSlides = [];

foreach ($files as $idx => $file) {
    $srcPath = $capturesDir . '/' . $file;
    if (!file_exists($srcPath)) {
        fwrite(STDERR, "Missing: $srcPath\n");
        exit(1);
    }

    $rawIm = imagecreatefrompng($srcPath);
    $w = imagesx($rawIm);
    $h = imagesy($rawIm);

    // 1. 4K UHD Slide (3840x2160) for Canva & slides folder
    $uhdIm = upscaleSharp($rawIm, $slideTargetW, $slideTargetH);
    imagepng($uhdIm, $hdCapturesDir . '/' . $file, 6);
    imagepng($uhdIm, $slidesOutDir . '/' . $file, 6);

    // Prepare JPEG stream for high-res PDF (3840x2160)
    ob_start();
    imagejpeg($uhdIm, null, 94);
    $jpgData = ob_get_clean();
    $pdfSlides[] = [
        'w' => $slideTargetW,
        'h' => $slideTargetH,
        'jpg' => $jpgData,
    ];
    imagedestroy($uhdIm);

    // 2. 2560px wide slice for seamless full-page PNG mockup
    $fpSlideH = (int) round($fullPageW * ($h / $w));
    $fpIm = upscaleSharp($rawIm, $fullPageW, $fpSlideH);
    $cropTop = ($idx === 0) ? 0 : (int) round(65 * ($fpSlideH / 900.0));
    $cropH = $fpSlideH - $cropTop;

    $fullPageImages[] = [
        'im' => $fpIm,
        'cropTop' => $cropTop,
        'cropH' => $cropH,
    ];
    $fullPageTotalH += $cropH;

    imagedestroy($rawIm);
    echo "Processed $file -> 4K (3840x2160) & FullPage slice ({$fullPageW}x{$cropH})\n";
}

// Build Seamless Full-Page 2.5K PNG Mockup (2560 px wide)
$canvas = imagecreatetruecolor($fullPageW, $fullPageTotalH);
$yOffset = 0;
foreach ($fullPageImages as $item) {
    imagecopy($canvas, $item['im'], 0, $yOffset, 0, $item['cropTop'], $fullPageW, $item['cropH']);
    $yOffset += $item['cropH'];
    imagedestroy($item['im']);
}

$fullPngPath = $dokumenDir . '/Mockup_Website_Lengkap_Arsip_Budaya.png';
imagepng($canvas, $fullPngPath, 6);
imagedestroy($canvas);
echo "Generated Full-Page PNG (2.5K): $fullPngPath ({$fullPageW}x{$fullPageTotalH})\n";

// Build 9-Page 4K Landscape PDF Mockup Portfolio (pure PHP PDF writer)
$objCount = 2;
$slideMeta = [];
$pageObjNums = [];

foreach ($pdfSlides as $s) {
    $imgObj = ++$objCount;
    $contentObj = ++$objCount;
    $pageObj = ++$objCount;

    $slideMeta[] = [
        'w' => $s['w'],
        'h' => $s['h'],
        'jpg' => $s['jpg'],
        'imgObj' => $imgObj,
        'contentObj' => $contentObj,
        'pageObj' => $pageObj,
    ];
    $pageObjNums[] = $pageObj;
}

$kidsStr = implode(' ', array_map(fn ($n) => "$n 0 R", $pageObjNums));
$objects = [];
$objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";
$objects[2] = "<< /Type /Pages /Kids [ $kidsStr ] /Count " . count($slideMeta) . " >>";

// Standard 16:9 widescreen PDF page points (1920x1080 pt)
$pageW = 1920;
$pageH = 1080;

foreach ($slideMeta as $s) {
    $imgLen = strlen($s['jpg']);
    $objects[$s['imgObj']] = "<< /Type /XObject /Subtype /Image /Width {$s['w']} /Height {$s['h']} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length $imgLen >>\nstream\n{$s['jpg']}\nendstream";

    $stream = "q $pageW 0 0 $pageH 0 0 cm /Im0 Do Q\n";
    $streamLen = strlen($stream);
    $objects[$s['contentObj']] = "<< /Length $streamLen >>\nstream\n{$stream}endstream";

    $objects[$s['pageObj']] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 $pageW $pageH] /Resources << /XObject << /Im0 {$s['imgObj']} 0 R >> >> /Contents {$s['contentObj']} 0 R >>";
}

ksort($objects);
$pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
$offsets = [0];
foreach ($objects as $num => $body) {
    $offsets[$num] = strlen($pdf);
    $pdf .= "$num 0 obj\n$body\nendobj\n";
}

$xrefOffset = strlen($pdf);
$totalObjs = count($objects) + 1;
$pdf .= "xref\n0 $totalObjs\n";
$pdf .= "0000000000 65535 f \n";
for ($i = 1; $i <= count($objects); $i++) {
    $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
}
$pdf .= "trailer\n<< /Size $totalObjs /Root 1 0 R >>\nstartxref\n$xrefOffset\n%%EOF";

$pdfPath = $dokumenDir . '/Mockup_Website_Lengkap_Arsip_Budaya.pdf';
file_put_contents($pdfPath, $pdf);
echo "Generated 9-Page 4K PDF Mockup: $pdfPath (" . strlen($pdf) . " bytes)\n";
