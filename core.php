<?php

// Configuración depuración
\sowerphp\core\Configure::write('debug', true);
\sowerphp\core\Configure::write('error.level', E_ALL);

// Tiempo máximo de ejecución del script a 10 minutos
ini_set('max_execution_time', 600);

// Tiempo de duración de la sesión en minutos
\sowerphp\core\Configure::write('session.expires', 600);

// Delimitador en archivos CSV
\sowerphp\core\Configure::write('spreadsheet.csv.delimiter', ';');

// Tema de la página (diseño)
\sowerphp\core\Configure::write('page.layout', 'LibreDTE');

// Textos de la página
\sowerphp\core\Configure::write('page.header.title', 'Libredte');
\sowerphp\core\Configure::write('page.body.title', 'Libredte');
\sowerphp\core\Configure::write('page.footer', [
    // los créditos de LibreDTE: autor original y enlaces, se deben mantener visibles en el footer de cada página de la aplicación
    // más información en los términos y condiciones de uso en https://wiki.libredte.cl/doku.php/terminos
    'left' => '&copy; 2019 '.\sowerphp\core\Configure::read('page.header.title').' - <a href="/consultar" title="Consultar documentos (incluyendo boletas)">Consultar DTE</a><br/><span class="small">Aplicación de facturación basada en <a>LibreDTE</a>, el cual es un proyecto de <a">SASCO SpA</a> que tiene como misión proveer facturación electrónica libre para Chile</span>',
    'right' => '',
]);

// Menú principal del sitio web
\sowerphp\core\Configure::write('nav.website', [
    '/dte' => ['name'=>'Facturación', 'desc'=>'Accede al módulo de facturación electrónica', 'icon'=>'fa fa-file-invoice'],
    '/utilidades' => ['name'=>'Utilidades', 'desc'=>'Utilidades y herramientas para generar documentos asociados a la facturación electrónica', 'icon'=>'fa fa-wrench'],
/*
   '/soporte' => ['name'=>'Soporte', 'desc'=>'¿Necesitas ayuda o tienes alguna consulta?', 'icon'=>'far fa-life-ring', 'nav'=>[
        'https://wiki.libredte.cl/doku.php/faq'=>'Preguntas y respuestas frecuentes',
        'https://groups.google.com/forum/#!forum/libredte' => 'Lista de correo en Google Groups',
        'https://wiki.libredte.cl'=>'Wiki de documentación',
        'https://sasco.cl/servicios/facturacion' => 'Soporte y asesoría entregado por SASCO SpA',
        'https://libredte.cl' => 'Aplicación oficial en libredte.cl',
    ]], */
]);

// Menú principal de la aplicación web
\sowerphp\core\Configure::write('nav.app', [
    '/dte' => '<span class="fa fa-file-invoice fa-fw"></span> Facturación',
    '/utilidades' => '<span class="fa fa-cog fa-fw"></span> Utilidades',
    '/certificacion' => '<span class="fa fa-certificate fa-fw"></span> Certificación',
    '/dte/contribuyentes/seleccionar' => '<span class="fa fa-mouse-pointer fa-fw"></span> Seleccionar empresa',
    '/sistema' => '<span class="fa fa-cogs fa-fw"></span> Sistema',
]);

// Menú por defecto de la empresa si no tiene definido uno personalizado
\sowerphp\core\Configure::write('nav.contribuyente', [
    '/dte/documentos/emitir' => '<span class="fas fa-file-invoice"></span> Emitir documento',
    '/dte/dte_tmps' => '<span class="far fa-file"></span> Documentos temporales',
    '/dte/dte_emitidos/listar' => '<span class="fas fa-sign-out-alt"></span> Documentos emitidos',
    '/dte/dte_recibidos/listar' => '<span class="fas fa-sign-in-alt"></span> Documentos recibidos',
    '/dte/dte_intercambios/listar' => '<span class="fas fa-exchange-alt"></span> Bandeja intercambio',
    '/dte/informes' => '<span class="fa fa-file"></span> Informes facturación',
]);

// Configuración para la base de datos
\sowerphp\core\Configure::write('database.default', array(
    'type' => 'PostgreSQL',
    'user' => 'user_libredte',
    'pass' => 'libredte*',
    'name' => 'libredte',
));

// Configuración para el correo electrónico
\sowerphp\core\Configure::write('email.default', array(
    'type' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 465,
    'user' => 'micorrreo',
    'pass' => 'mipasss',
    'from' => array('email'=>'', 'name'=>'Libredte'),
    'to' => '',
));

// Módulos que utiliza la aplicación
\sowerphp\core\Module::uses([
    'Dev',
    'Dte',
    'Dte.Cobranzas',
    'Dte.Informes',
    'Dte.Admin',
    'Dte.Admin.Informes',
    'Dte.Admin.Mantenedores',
    'Utilidades',
    'Sistema.General',
    'Sistema.General.DivisionGeopolitica',
    'Sistema.Servidor',
]);

// módulos principales (extras a Dte) que sólo funcionan con una empresa registrada
//\sowerphp\core\Configure::write('app.modulos_empresa', []);

// Configuración para autorización secundaria (extensión: sowerphp/app)
/*\sowerphp\core\Configure::write('auth2', [
    'name' => 'Latch',
    'url' => 'https://latch.elevenpaths.com',
    'app_id' => '',
    'app_key' => '',
    'default' => false,
]);*/

// Configuración para reCAPTCHA (extensión: sowerphp/app)
/*\sowerphp\core\Configure::write('recaptcha', [
    'public_key' => '',
    'private_key' => '',
]);*/

// Configuración para auto registro de usuarios (extensión: sowerphp/app)
\sowerphp\core\Configure::write('app.self_register', [
    'groups' => ['usuarios', 'dte_plus'],
    'terms' => '#',
]);

// configuración de permisos de la empresa en la aplicación
\sowerphp\core\Configure::write('empresa.permisos', [
    'admin' => [
        'nombre' => 'Administrador',
        'descripcion' => 'Incluye editar empresa y otros usuarios, respaldos, descargar CAF, corregir Track ID',
        'grupos' => ['dte_plus'],
    ],
    'dte' => [
        'nombre' => 'Módulo facturación electrónica',
        'descripcion' => 'Emisión de DTE, recepción, informes y libros de compra/venta',
        'grupos' => ['dte_plus'],
    ],
]);

// configuración general del módulo DTE
\sowerphp\core\Configure::write('dte', [
    // contraseña que se usará para encriptar datos sensibles en la BD
    'pkey' => 'zVSy^QZGl*wDJ^r1RdGudv@*ff%%0YUR', // DEBE ser de 32 chars
    // configuración de logos de las empresas
    'logos' => [
        'width' => 150,
        'height' => 100,
    ],
    // DTEs autorizados por defecto para ser usados por las nuevas empresas
    'dtes' => [33, 39, 56, 61],
    // opciones para los PDF
    'pdf' => [
        // =true se asignará texto por defecto. String al lado izquiero o bien arreglo con índices left y right con sus textos
        'footer' => False,
    ],
    // validar SSL de sitios del SII
    'verificar_ssl' => true,
    // web verificacion boletas (debe ser la ruta completa, incluyendo /boletas)
    'web_verificacion' => null,
]);

// configuración para API de contribuyentes
\sowerphp\core\Configure::write('api_contribuyentes', [
    'dte_items' => [
        'name' => 'Listado de items',
        'desc' => 'Consultar los items a través de su código',
        'link' => 'https://wiki.libredte.cl/doku.php/sowerphp/integracion/url_items',
    ],
    'dte_pdf' => [
        'name' => 'PDF de DTE',
        'desc' => 'Servicio que genera el PDF a partir del XML del DTE',
        'link' => 'http://blog.libredte.cl/index.php/2017/10/06/personaliza-el-pdf-de-tus-dte-como-quieras'
    ],
    'dte_intercambio_responder' => [
        'name' => 'Procesar intercambio',
        'desc' => 'Servicio que procesa un intercambio de DTE e indica si se debe aceptar o reclamar',
        'link' => 'https://wiki.libredte.cl/doku.php/sowerphp/integracion/procesar_intercambio',
    ],
]);

// configuración para firma electrónica
/*\sowerphp\core\Configure::write('firma_electronica.default', [
    'file' => DIR_PROJECT.'/data/firma_electronica/default.p12',
    'pass' => '',
]);*/

// configuración para autenticación en API a usuarios no logueados
\sowerphp\core\Configure::write('api.default', [
    'token' => 't7dr5B1ujphds043WMMEFWwFLeyWYqMU',
]);

// configuración para preautenticación
/*\sowerphp\core\Configure::write('preauth', [
    'enabled' => false,
]);*/

// configuración autenticación servicios externos
/*\sowerphp\core\Configure::write('proveedores.api', [
    // Desbloquea las funcionalidades Extra de LibreDTE
    // Regístrate Gratis en https://api.libredte.cl
    'libredte' => '',
]);*/

// configuración módulo Apps
/*\sowerphp\core\Configure::write('module.Apps', [
    'Dropbox' => [
        'key' => '',
        'secret' => '',
    ],
]);*/

// configuración para las aplicaciones de terceros que se pueden usar en LibreDTE
/*\sowerphp\core\Configure::write('apps_3rd_party', [
    'apps' => [
        'directory' => __DIR__.'/../../website/Module/Apps/Utility/Apps',
        'namespace' => '\website\Apps',
    ],
]);*/

// handler para triggers de la app
//\sowerphp\core\Configure::write('app.trigger_handler', '');

// directorio para datos estáticos (debe tener permisos de escritura)
define('DIR_STATIC', DIR_PROJECT.'/data/static');
