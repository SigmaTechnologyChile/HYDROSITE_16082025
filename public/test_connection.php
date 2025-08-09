<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Modal - HydroSite</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .test-container { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            max-width: 600px; 
            margin: 0 auto; 
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            padding: 15px; 
            border-radius: 5px; 
            border-left: 4px solid #28a745; 
            margin-bottom: 20px; 
        }
        .test-btn { 
            background: #007bff; 
            color: white; 
            padding: 12px 24px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px; 
        }
        .test-btn:hover { 
            background: #0056b3; 
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="success">
            <h2>✅ ¡Conexión Exitosa!</h2>
            <p>Si puedes ver esta página, significa que tu servidor Apache está funcionando correctamente.</p>
        </div>
        
        <h1>🧪 Prueba de Conectividad - HydroSite</h1>
        
        <p><strong>Estado del servidor:</strong> ✅ Funcionando</p>
        <p><strong>Directorio:</strong> /hydrosite/public_html/public/</p>
        <p><strong>Fecha:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        
        <br>
        
        <h3>Próximos pasos:</h3>
        <ol>
            <li>✅ Servidor Apache confirmado funcionando</li>
            <li>🔄 Ahora vamos a probar el modal real en Laravel</li>
            <li>🎯 Verificar las rutas de Laravel</li>
        </ol>
        
        <button class="test-btn" onclick="window.close()">Cerrar Prueba</button>
    </div>
</body>
</html>
