<?php

namespace App\Services;

use App\Models\Employee;

class BannerImageService
{
    private string $fontPath;
    private string $imgDir;

    public function __construct()
    {
        $this->fontPath = base_path('resources/fonts/arialbd.ttf');
        $this->imgDir   = public_path('images');
    }

    /**
     * Generate banner image as raw PNG bytes for CID inline embedding.
     */
    public function generateBytes(string $eventType, Employee $employee): string
    {
        return match ($eventType) {
            'birthday'     => $this->birthdayBytes($employee->employee_name),
            'anniversary'  => $this->anniversaryBytes($employee->employee_name),
            'founding_day' => $this->foundingDayBytes(),
            default        => '',
        };
    }

    /** @deprecated use generateBytes() — kept for internal compat */
    public function generate(string $eventType, Employee $employee): string
    {
        return 'data:image/png;base64,' . base64_encode($this->generateBytes($eventType, $employee));
    }

    // Birthday: overlay first name at (265, 495), gold, font 140, max width 430
    private function birthdayBytes(string $fullName): string
    {
        $firstName  = explode(' ', trim($fullName))[0];
        $masterPath = "{$this->imgDir}/pivot_birthday_greeting_template.png";

        $img = imagecreatefrompng($masterPath);
        imagealphablending($img, true);
        imagesavealpha($img, true);

        $gold = $this->hexColor($img, '#F5C66D');
        $size = 140;
        $maxW = 430;

        while ($size > 20) {
            $bbox  = imagettfbbox($size, 0, $this->fontPath, $firstName);
            $textW = $bbox[2] - $bbox[0];
            if ($textW <= $maxW) break;
            $size -= 5;
        }

        $bbox      = imagettfbbox($size, 0, $this->fontPath, $firstName);
        $ascent    = -$bbox[7];
        $baselineY = 495 + $ascent;

        imagettftext($img, $size, 0, 265, $baselineY, $gold, $this->fontPath, $firstName);

        return $this->toPngBytes($img);
    }

    // Anniversary: overlay full name uppercase at (170, 410), gold, font 90
    private function anniversaryBytes(string $fullName): string
    {
        $name       = strtoupper($fullName);
        $masterPath = "{$this->imgDir}/pivot_work_anniversary_template.png";

        $img = imagecreatefrompng($masterPath);
        imagealphablending($img, true);
        imagesavealpha($img, true);

        $gold = $this->hexColor($img, '#F5C66D');
        $size = 90;

        $bbox      = imagettfbbox($size, 0, $this->fontPath, $name);
        $ascent    = -$bbox[7];
        $baselineY = 410 + $ascent;

        imagettftext($img, $size, 0, 170, $baselineY, $gold, $this->fontPath, $name);

        return $this->toPngBytes($img);
    }

    // Founding Day: static image
    private function foundingDayBytes(): string
    {
        $path = "{$this->imgDir}/pivot_foundation_day_template.png";
        $img  = imagecreatefrompng($path);
        return $this->toPngBytes($img);
    }

    private function hexColor($img, string $hex): int
    {
        $hex = ltrim($hex, '#');
        return imagecolorallocate($img,
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    }

    private function toPngBytes($img): string
    {
        // Resize to max 1200px wide to keep email size reasonable
        $srcW = imagesx($img);
        $srcH = imagesy($img);
        $maxW = 1200;

        if ($srcW > $maxW) {
            $ratio  = $maxW / $srcW;
            $newW   = $maxW;
            $newH   = (int) round($srcH * $ratio);
            $scaled = imagecreatetruecolor($newW, $newH);
            imagealphablending($scaled, false);
            imagesavealpha($scaled, true);
            imagecopyresampled($scaled, $img, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
            imagedestroy($img);
            $img = $scaled;
        }

        ob_start();
        imagepng($img, null, 7);
        $bytes = ob_get_clean();
        imagedestroy($img);
        return $bytes;
    }
}
