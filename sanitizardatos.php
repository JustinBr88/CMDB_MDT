<?php
/**
 * Clase para sanitización y validación de datos
 * Implementa métodos básicos para prevenir vulnerabilidades comunes
 */
class SanitizarDatos {
    
    /**
     * Sanitiza datos para prevenir XSS en salida HTML
     * @param string $data - Datos a sanitizar
     * @return string - Datos sanitizados
     */
    public static function sanitizarHTML($data) {
        if ($data === null) return '';
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitiza entrada de texto básica
     * @param string $data - Datos a sanitizar
     * @return string - Datos sanitizados
     */
    public static function sanitizarTexto($data) {
        if ($data === null) return '';
        return trim(strip_tags($data));
    }
    
    /**
     * Valida y sanitiza números enteros
     * @param mixed $data - Datos a validar
     * @return int|false - Número entero válido o false
     */
    public static function sanitizarEntero($data) {
        $sanitized = filter_var($data, FILTER_VALIDATE_INT);
        return $sanitized !== false ? $sanitized : false;
    }
    
    /**
     * Valida y sanitiza email
     * @param string $email - Email a validar
     * @return string|false - Email válido o false
     */
    public static function sanitizarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Sanitiza texto para SQL (usa prepared statements cuando sea posible)
     * @param string $data - Datos a sanitizar
     * @param mysqli $conexion - Conexión a la base de datos
     * @return string - Datos sanitizados
     */
    public static function sanitizarSQL($data, $conexion) {
        if ($data === null) return '';
        return mysqli_real_escape_string($conexion, trim($data));
    }
    
    /**
     * Valida URL
     * @param string $url - URL a validar
     * @return string|false - URL válida o false
     */
    public static function sanitizarURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
    
    /**
     * Sanitiza nombre de archivo
     * @param string $filename - Nombre de archivo
     * @return string - Nombre sanitizado
     */
    public static function sanitizarNombreArchivo($filename) {
        // Remover caracteres peligrosos
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        // Prevenir directory traversal
        $filename = str_replace(['../', '../', '..\\', '..\\\\'], '', $filename);
        return $filename;
    }
    
    /**
     * Sanitiza datos de formulario completos
     * @param array $data - Array de datos del formulario
     * @param array $rules - Reglas de sanitización por campo
     * @return array - Datos sanitizados
     */
    public static function sanitizarFormulario($data, $rules = []) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            $rule = $rules[$key] ?? 'texto';
            
            switch ($rule) {
                case 'html':
                    $sanitized[$key] = self::sanitizarHTML($value);
                    break;
                case 'entero':
                    $sanitized[$key] = self::sanitizarEntero($value);
                    break;
                case 'email':
                    $sanitized[$key] = self::sanitizarEmail($value);
                    break;
                case 'url':
                    $sanitized[$key] = self::sanitizarURL($value);
                    break;
                case 'archivo':
                    $sanitized[$key] = self::sanitizarNombreArchivo($value);
                    break;
                case 'texto':
                default:
                    $sanitized[$key] = self::sanitizarTexto($value);
                    break;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Valida longitud de texto
     * @param string $text - Texto a validar
     * @param int $min - Longitud mínima
     * @param int $max - Longitud máxima
     * @return bool - Es válido
     */
    public static function validarLongitud($text, $min = 1, $max = 255) {
        $length = strlen($text);
        return $length >= $min && $length <= $max;
    }
    
    /**
     * Genera token CSRF
     * @return string - Token CSRF
     */
    public static function generarTokenCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Valida token CSRF
     * @param string $token - Token a validar
     * @return bool - Token válido
     */
    public static function validarTokenCSRF($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Sanitiza array de datos para JSON
     * @param mixed $data - Datos a sanitizar
     * @return mixed - Datos sanitizados
     */
    public static function sanitizarParaJSON($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizarParaJSON'], $data);
        } else {
            return self::sanitizarHTML($data);
        }
    }
    
    /**
     * Valida formato de teléfono básico
     * @param string $phone - Teléfono a validar
     * @return bool - Es válido
     */
    public static function validarTelefono($phone) {
        return preg_match('/^[\d\-\+\(\)\s]+$/', $phone) && strlen($phone) >= 7;
    }
    
    /**
     * Sanitiza datos según tipo de campo de base de datos
     * @param mixed $value - Valor a sanitizar
     * @param string $type - Tipo de campo (varchar, int, text, etc.)
     * @param int $maxLength - Longitud máxima
     * @return mixed - Valor sanitizado
     */
    public static function sanitizarPorTipo($value, $type, $maxLength = null) {
        switch (strtolower($type)) {
            case 'int':
            case 'integer':
                return self::sanitizarEntero($value);
                
            case 'varchar':
            case 'char':
                $sanitized = self::sanitizarTexto($value);
                return $maxLength ? substr($sanitized, 0, $maxLength) : $sanitized;
                
            case 'text':
            case 'longtext':
                return self::sanitizarTexto($value);
                
            case 'email':
                return self::sanitizarEmail($value);
                
            case 'url':
                return self::sanitizarURL($value);
                
            default:
                return self::sanitizarTexto($value);
        }
    }
}

/**
 * Funciones helper para uso rápido
 */

/**
 * Shortcut para sanitizar HTML
 */
function h($data) {
    return SanitizarDatos::sanitizarHTML($data);
}

/**
 * Shortcut para sanitizar texto
 */
function clean($data) {
    return SanitizarDatos::sanitizarTexto($data);
}

/**
 * Shortcut para sanitizar entero
 */
function int_clean($data) {
    return SanitizarDatos::sanitizarEntero($data);
}
