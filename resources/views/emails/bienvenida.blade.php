<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Nexus</title>
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
            background-color: #4CAF50;
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
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
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
        <h1>Bienvenido a Nexus E-commerce</h1>
    </div>
    
    <div class="content">
        <h2>Hola {{ $usuario->nombre_completo }},</h2>
        
        <p>Gracias por registrarte en Nexus E-commerce. Estamos emocionados de tenerte con nosotros.</p>
        
        <p>Tu cuenta ha sido creada exitosamente y ya puedes comenzar a explorar nuestro catálogo de productos.</p>
        
        <p><strong>Datos de tu cuenta:</strong></p>
        <ul>
            <li>Email: {{ $usuario->correo_electronico }}</li>
            <li>Nombre: {{ $usuario->nombre_completo }}</li>
        </ul>
        
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        
        <a href="{{ url('/') }}" class="button">Ir a la tienda</a>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nexus E-commerce. Todos los derechos reservados.</p>
    </div>
</body>
</html>
