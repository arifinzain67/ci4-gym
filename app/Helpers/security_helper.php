<?php

/**
 * Helper untuk keamanan aplikasi
 */

if (!function_exists('sanitize_output')) {
    /**
     * Membersihkan output dari potensi XSS
     * 
     * @param string|array $data Data yang akan dibersihkan
     * @param bool $stripTags Apakah akan menghapus semua tag HTML
     * @return string|array Data yang telah dibersihkan
     */
    function sanitize_output($data, $stripTags = false)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitize_output($value, $stripTags);
            }
            return $data;
        }
        
        if ($stripTags) {
            $data = strip_tags($data);
        }
        
        return esc($data, 'html');
    }
}

if (!function_exists('is_valid_input')) {
    /**
     * Memeriksa apakah input tidak mengandung karakter berbahaya
     * 
     * @param string $input Input yang akan diperiksa
     * @return bool True jika input aman, false jika tidak
     */
    function is_valid_input($input)
    {
        // Periksa input untuk karakter berbahaya atau pola serangan
        $dangerous = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/\bon\w+\s*=\s*"[^"]*"/is',
            '/\bon\w+\s*=\s*\'[^\']*\'/is',
            '/\bon\w+\s*=[^"\'\s>]+/is',
        ];
        
        foreach ($dangerous as $pattern) {
            if (preg_match($pattern, $input)) {
                return false;
            }
        }
        
        return true;
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Membersihkan nama file dari karakter berbahaya
     * 
     * @param string $filename Nama file yang akan dibersihkan
     * @return string Nama file yang telah dibersihkan
     */
    function sanitize_filename($filename)
    {
        // Hapus karakter yang berbahaya dari nama file
        $filename = preg_replace('/[^\w\.-]/i', '', $filename);
        
        // Hapus spasi
        $filename = str_replace(' ', '_', $filename);
        
        // Hindari serangan traversal direktori
        $filename = str_replace(['../', '..\\', '..'], '', $filename);
        
        return $filename;
    }
}
