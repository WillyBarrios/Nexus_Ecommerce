@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <div class="greeting">
        <h1>Hola! Jos</h1>
        <p>Repaso rápido de lo que sucede en tu tienda.</p>
    </div>
    <div class="date-filters">
        <button class="date-filter">2025.10.14</button>
        <button class="date-filter">2025.11.14</button>
    </div>
</div>

<div class="card">
    <h2 class="card-header">Detalles generales</h2>
    <div class="stat-cards-container">
        <div class="stat-card">
            <div class="info">
                <h3>Q2,790.49</h3>
                <p>Ventas totales</p>
                <span class="percentage">↑ 1760.33%</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="info">
                <h3>14</h3>
                <p>Órdenes totales</p>
                <span class="percentage">↑ 180%</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="info">
                <h3>2</h3>
                <p>Clientes totales</p>
                <span class="percentage">↑ 100%</span>
            </div>
        </div>
        <div class="stat-card">
             <div class="info">
                <h3>Q199.49</h3>
                <p>Promedio de ventas</p>
                <span class="percentage">↑ 564.40%</span>
            </div>
        </div>
        <div class="stat-card">
             <div class="info">
                <h3>Q0.00</h3>
                <p>Total de facturas no pagadas</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card">
        <h2 class="card-header">Detalles de hoy</h2>
         <div class="stat-cards-container" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="info">
                    <h3>Q1317.49</h3><p>Ventas totales</p><span class="percentage">↑ 1760.33%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="info">
                    <h3>2</h3><p>Pedidos hoy</p><span class="percentage">↑ 180%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="info">
                    <h3>0</h3><p>Clientes de hoy</p><span class="percentage negative">↓ 100%</span>
                </div>
            </div>
        </div>
        <div class="data-list">
             <div class="item">
                <div class="item-details">
                    <div>
                        <strong>#53</strong>
                        <span>14 nov 2025, 16:52:36</span>
                        <span class="status-completed">Completado</span>
                    </div>
                </div>
                <div class="item-info">
                    <strong>Q1317.49</strong>
                    <span>Transferencia de dinero</span>
                </div>
                <div class="item-info">
                    <strong>Jimmy Doe</strong>
                    <span>john@example.com</span>
                    <span>Ciudad de Guatemala</span>
                </div>
                <div class="item-details image-placeholder-group">
                     <div class="placeholder"></div>
                     <div class="placeholder"></div>
                </div>
                <span>></span>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="card-header">Umbral de existencias</h2>
        <div class="data-list">
            @for ($i = 0; $i < 4; $i++)
            <div class="item">
                <div class="item-details">
                    <div class="placeholder"></div>
                    <div class="item-info">
                        <strong>Producto increíble</strong>
                        <span>Lorem ipsum dolor sit amet...</span>
                    </div>
                </div>
                <div class="item-info">
                    <strong>Q25.00</strong>
                    <span>{{ rand(0, 100) }} stock</span>
                </div>
                <span>></span>
            </div>
            @endfor
        </div>
    </div>

    <div class="card" style="grid-column: span 2;">
        <h2 class="card-header">Estadísticas</h2>
        <div class="stats-container">
            <div>
                <p><strong>Total Sales: $2,790.49</strong> (14 Orders)</p>
                <div class="graph-placeholder">Graph Placeholder</div>
            </div>
            <div>
                <p><strong>Visitors: 0</strong> (0 unique)</p>
                <div class="graph-placeholder">Graph Placeholder</div>
            </div>
        </div>
    </div>

    <div class="card" style="grid-column: span 2;">
        <h2 class="card-header">Productos más vendidos</h2>
        <div class="product-grid">
             @for ($i = 0; $i < 4; $i++)
            <div class="product-card">
                <div class="placeholder"></div>
                <div class="item-info">
                    <strong>Producto increíble</strong>
                    <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <div class="card" style="grid-column: span 2;">
        <h2 class="card-header">Cliente con mayor volumen de ventas</h2>
        <div class="data-list">
             @for ($i = 0; $i < 3; $i++)
            <div class="item">
                <div class="item-info">
                    <strong>Jimmy Doe</strong>
                    <span>john@example.com</span>
                </div>
                <div class="item-info">
                    <strong>Q2,790.49</strong>
                    <span>9 órdenes</span>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
@endsection
