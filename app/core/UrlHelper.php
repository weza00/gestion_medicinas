<?php
/**
 * Helper para generar URLs de manera consistente
 * Funciona tanto con Apache como con nginx
 */

/**
 * Genera una URL completa basada en la ruta proporcionada
 * @param string $path - Ruta relativa (ej: "auth/login", "catalogo", "carrito")
 * @param array $params - Parámetros GET opcionales
 * @return string - URL completa
 */
function url($path = '', $params = []) {
    $base = rtrim(BASE_URL, '/');
    
    // Limpiar la ruta
    $path = trim($path, '/');
    
    // Construir URL
    $url = $base;
    if (!empty($path)) {
        $url .= '/' . $path;
    }
    
    // Agregar parámetros GET si los hay
    if (!empty($params)) {
        $query = http_build_query($params);
        $url .= '?' . $query;
    }
    
    return $url;
}

/**
 * Genera URL para recursos estáticos (CSS, JS, imágenes)
 * @param string $asset - Ruta del recurso (ej: "css/style.css", "js/app.js")
 * @return string - URL completa del recurso
 */
function asset($asset) {
    $base = rtrim(BASE_URL, '/');
    $asset = ltrim($asset, '/');
    return $base . '/' . $asset;
}

/**
 * Redirección utilizando el helper de URL
 * @param string $path - Ruta a la que redirigir
 * @param int $code - Código de estado HTTP (por defecto 302)
 */
function redirect($path = '', $code = 302) {
    header('Location: ' . url($path), true, $code);
    exit();
}

/**
 * Verifica si la URL actual coincide con la ruta proporcionada
 * Útil para marcar enlaces activos en navegación
 * @param string $path - Ruta a verificar
 * @return bool
 */
function is_current_url($path) {
    $current = $_GET['url'] ?? '';
    $current = trim($current, '/');
    $path = trim($path, '/');
    
    return $current === $path || strpos($current, $path) === 0;
}

/**
 * Obtiene la URL base sin trailing slash
 * @return string
 */
function base_url() {
    return rtrim(BASE_URL, '/');
}

/**
 * Genera meta tags para compartir en redes sociales
 * @param array $meta - Array con título, descripción, imagen, etc.
 * @return string - HTML de meta tags
 */
function social_meta($meta = []) {
    $defaults = [
        'title' => 'MediPlus - Farmacia Hospitalaria',
        'description' => 'Sistema de gestión de medicamentos hospitalarios',
        'image' => asset('images/logo-og.png'),
        'url' => url()
    ];
    
    $meta = array_merge($defaults, $meta);
    
    $html = '';
    $html .= '<meta property="og:title" content="' . htmlspecialchars($meta['title']) . '">' . "\n";
    $html .= '<meta property="og:description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
    $html .= '<meta property="og:image" content="' . $meta['image'] . '">' . "\n";
    $html .= '<meta property="og:url" content="' . $meta['url'] . '">' . "\n";
    $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    
    return $html;
}
?>