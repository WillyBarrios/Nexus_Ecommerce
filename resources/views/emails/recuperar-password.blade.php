<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #F44336;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #F44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Recuperar Contraseña</h1>
    </div>
    
    <div class="content">
        <h2>Hola {{ $usuario->nombre_completo }},</h2>
        
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en Nexus E-commerce.</p>
        
        <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
        
        <div style="text-align: center;">
            <a href="{{ $url }}" class="button">Restablecer Contraseña</a>
        </div>
        
        <div class="warning">
            <strong>Importante:</strong> Este enlace expirará en 60 minutos por seguridad.
        </div>
        
        <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo. Tu contraseña no será cambiada.</p>
        
        <p style="color: #666; font-size: 12px; margin-top: 30px;">
            Si tienes problemas con el botón, copia y pega este enlace en tu navegador:<br>
            {{ $url }}
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nexus E-commerce. Todos los derechos reservados.</p>
    </div>
</body>
</html>
