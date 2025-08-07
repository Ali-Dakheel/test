<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounting API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #1f2937;
            padding: 10px 0;
        }
        .swagger-ui .topbar .download-url-wrapper {
            display: none;
        }
        .swagger-ui .topbar-wrapper {
            max-width: none;
            padding: 0 20px;
        }
        .swagger-ui .topbar-wrapper .link {
            content: "Accounting API Documentation";
        }
        .custom-header {
            background: linear-gradient(90deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .custom-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }
        .custom-header p {
            margin: 5px 0 0 0;
            opacity: 0.8;
            font-size: 1rem;
        }
        .api-info {
            background: white;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            margin: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .api-info h3 {
            margin-top: 0;
            color: #1f2937;
        }
        .api-info ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .api-info li {
            margin: 5px 0;
        }
        .endpoint-badge {
            background: #10b981;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .endpoint-badge.post {
            background: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="custom-header">
        <h1>🏦 Accounting API Documentation</h1>
        <p>Interactive API documentation for the Financial Transaction System</p>
    </div>
    
    <div class="api-info">
        <h3>📋 API Overview</h3>
        <p>This API allows you to record and view financial transactions with automatic balance tracking.</p>
        <ul>
            <li><span class="endpoint-badge">GET</span> <code>/api/transactions</code> - Get all transactions and current balance</li>
            <li><span class="endpoint-badge post">POST</span> <code>/api/transactions</code> - Create a new transaction</li>
        </ul>
        <p><strong>Base URL:</strong> <code>{{ url('/api') }}</code></p>
        <p><strong>Format:</strong> JSON | <strong>Authentication:</strong> None required for this demo</p>
    </div>

    <div id="swagger-ui"></div>
    
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Configuration for Swagger UI
            const ui = SwaggerUIBundle({
                url: '{{ url("/api-docs/accounting-api.yaml") }}',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                validatorUrl: null, // Disable online validation
                tryItOutEnabled: true, // Enable "Try it out" buttons
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function() {
                    console.log('✅ API Documentation loaded successfully!');
                    
                    // Add some custom styling after load
                    const style = document.createElement('style');
                    style.innerHTML = `
                        .swagger-ui .scheme-container {
                            background: #f8f9fa;
                            border-radius: 5px;
                        }
                        .swagger-ui .info {
                            margin: 20px 0;
                        }
                    `;
                    document.head.appendChild(style);
                },
                onFailure: function(error) {
                    console.error('❌ Failed to load API documentation:', error);
                    document.getElementById('swagger-ui').innerHTML = `
                        <div style="padding: 40px; text-align: center; color: #dc3545;">
                            <h2>⚠️ Documentation Loading Error</h2>
                            <p>Could not load the OpenAPI specification file.</p>
                            <p>Please ensure the file exists at: <code>/api-docs/accounting-api.yaml</code></p>
                            <p>Error: ${error.message || 'Unknown error'}</p>
                        </div>
                    `;
                },
                requestInterceptor: function(request) {
                    // Add custom headers or modify requests if needed
                    console.log('📤 API Request:', request.method, request.url);
                    return request;
                },
                responseInterceptor: function(response) {
                    // Log responses for debugging
                    console.log('📥 API Response:', response.status, response.url);
                    return response;
                }
            });
            
            // Make UI instance available globally for debugging
            window.ui = ui;
        };
    </script>
</body>
</html>