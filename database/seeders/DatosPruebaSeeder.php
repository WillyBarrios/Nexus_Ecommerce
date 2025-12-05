<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatosPruebaSeeder extends Seeder
{
    /**
     * Seeder para agregar datos de prueba:
     * - 3 Administradores
     * - 2 Vendedores
     * - 5 Clientes
     * - Pedidos con diferentes estados
     */
    public function run(): void
    {
        $this->command->info('Iniciando seeder de datos de prueba...');
        
        // Crear Administradores
        $admins = [
            [
                'nombre_completo' => 'Carlos Administrador',
                'correo_electronico' => 'carlos.admin@nexus.com',
                'contrasena' => Hash::make('admin123'),
                'telefono' => '555-0001',
                'direccion' => 'Av. Principal 123, Ciudad',
                'id_rol' => 1,
            ],
            [
                'nombre_completo' => 'María Administradora',
                'correo_electronico' => 'maria.admin@nexus.com',
                'contrasena' => Hash::make('admin123'),
                'telefono' => '555-0002',
                'direccion' => 'Calle Secundaria 456, Ciudad',
                'id_rol' => 1,
            ],
            [
                'nombre_completo' => 'Jorge Administrador',
                'correo_electronico' => 'jorge.admin@nexus.com',
                'contrasena' => Hash::make('admin123'),
                'telefono' => '555-0003',
                'direccion' => 'Av. Central 789, Ciudad',
                'id_rol' => 1,
            ],
        ];

        // Crear Vendedores
        $vendedores = [
            [
                'nombre_completo' => 'Ana Vendedora',
                'correo_electronico' => 'ana.vendedor@nexus.com',
                'contrasena' => Hash::make('vendedor123'),
                'telefono' => '555-0004',
                'direccion' => 'Calle Comercio 111, Ciudad',
                'id_rol' => 2,
            ],
            [
                'nombre_completo' => 'Luis Vendedor',
                'correo_electronico' => 'luis.vendedor@nexus.com',
                'contrasena' => Hash::make('vendedor123'),
                'telefono' => '555-0005',
                'direccion' => 'Av. Ventas 222, Ciudad',
                'id_rol' => 2,
            ],
        ];

        // Crear Clientes
        $clientes = [
            [
                'nombre_completo' => 'Pedro Cliente',
                'correo_electronico' => 'pedro.cliente@example.com',
                'contrasena' => Hash::make('cliente123'),
                'telefono' => '555-0006',
                'direccion' => 'Calle Residencial 333, Ciudad',
                'id_rol' => 3,
            ],
            [
                'nombre_completo' => 'Laura Cliente',
                'correo_electronico' => 'laura.cliente@example.com',
                'contrasena' => Hash::make('cliente123'),
                'telefono' => '555-0007',
                'direccion' => 'Av. Hogar 444, Ciudad',
                'id_rol' => 3,
            ],
            [
                'nombre_completo' => 'Roberto Cliente',
                'correo_electronico' => 'roberto.cliente@example.com',
                'contrasena' => Hash::make('cliente123'),
                'telefono' => '555-0008',
                'direccion' => 'Calle Norte 555, Ciudad',
                'id_rol' => 3,
            ],
            [
                'nombre_completo' => 'Sofia Cliente',
                'correo_electronico' => 'sofia.cliente@example.com',
                'contrasena' => Hash::make('cliente123'),
                'telefono' => '555-0009',
                'direccion' => 'Av. Sur 666, Ciudad',
                'id_rol' => 3,
            ],
            [
                'nombre_completo' => 'Diego Cliente',
                'correo_electronico' => 'diego.cliente@example.com',
                'contrasena' => Hash::make('cliente123'),
                'telefono' => '555-0010',
                'direccion' => 'Calle Este 777, Ciudad',
                'id_rol' => 3,
            ],
        ];

        // Insertar usuarios (solo si no existen)
        $usuariosIds = [];
        $usuariosCreados = 0;
        foreach (array_merge($admins, $vendedores, $clientes) as $usuario) {
            $existe = DB::table('usuarios')
                ->where('correo_electronico', $usuario['correo_electronico'])
                ->first();
            
            if (!$existe) {
                $id = DB::table('usuarios')->insertGetId($usuario);
                $usuariosIds[] = $id;
                $usuariosCreados++;
            } else {
                $usuariosIds[] = $existe->id_usuario;
            }
        }

        $this->command->info("✓ Usuarios: {$usuariosCreados} nuevos creados, " . (10 - $usuariosCreados) . " ya existían");

        // Verificar si existe al menos un pago, si no, crear uno
        $pago = DB::table('pagos')->first();
        if (!$pago) {
            // Usar el primer usuario como referencia
            $primerUsuario = DB::table('usuarios')->first();
            $pagoId = DB::table('pagos')->insertGetId([
                'id_usuario' => $primerUsuario->id_usuario,
                'metodo_pago' => 'efectivo',
                'estado' => 'completado',
                'monto' => 0,
                'referencia_transaccion' => 'PAGO-DEFAULT-001',
            ]);
            $this->command->info('✓ Pago por defecto creado');
        } else {
            $pagoId = $pago->id_pago;
        }

        // Obtener productos existentes
        $productos = DB::table('productos')->limit(5)->get();
        
        if ($productos->isEmpty()) {
            $this->command->warn('⚠ No hay productos en la BD. Crea productos primero.');
            return;
        }

        // Crear pedidos con diferentes estados
        $estados = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
        $pedidosCreados = 0;

        // Crear 2 pedidos por cada cliente (10 pedidos total)
        $clientesIds = array_slice($usuariosIds, -5); // Últimos 5 usuarios son clientes
        
        foreach ($clientesIds as $index => $clienteId) {
            for ($i = 0; $i < 2; $i++) {
                $estado = $estados[($pedidosCreados) % 5]; // Rotar entre estados
                $numeroPedido = 'PED-' . date('Ymd') . '-' . str_pad($pedidosCreados + 1, 6, '0', STR_PAD_LEFT);
                
                // Calcular total (2 productos aleatorios)
                $producto1 = $productos->random();
                $producto2 = $productos->random();
                $total = ($producto1->precio * 1) + ($producto2->precio * 2);

                // Crear pedido
                $pedidoId = DB::table('pedidos')->insertGetId([
                    'id_usuario' => $clienteId,
                    'numero_pedido' => $numeroPedido,
                    'monto_total' => $total,
                    'estado' => $estado,
                    'id_pago' => $pagoId, // ID de pago existente
                    'fecha_creacion' => now()->subDays(rand(0, 30)),
                    'fecha_actualizacion' => now(),
                ]);

                // Crear detalles del pedido
                DB::table('detalle_pedido')->insert([
                    [
                        'id_pedido' => $pedidoId,
                        'id_producto' => $producto1->id_producto,
                        'cantidad' => 1,
                        'precio_unitario' => $producto1->precio,
                        'subtotal' => $producto1->precio * 1,
                    ],
                    [
                        'id_pedido' => $pedidoId,
                        'id_producto' => $producto2->id_producto,
                        'cantidad' => 2,
                        'precio_unitario' => $producto2->precio,
                        'subtotal' => $producto2->precio * 2,
                    ],
                ]);

                $pedidosCreados++;
            }
        }

        $this->command->info("✓ Pedidos creados: {$pedidosCreados} con estados variados");
        $this->command->info('');
        $this->command->info('=== CREDENCIALES DE PRUEBA ===');
        $this->command->info('Administradores:');
        $this->command->info('  - carlos.admin@nexus.com / admin123');
        $this->command->info('  - maria.admin@nexus.com / admin123');
        $this->command->info('  - jorge.admin@nexus.com / admin123');
        $this->command->info('');
        $this->command->info('Vendedores:');
        $this->command->info('  - ana.vendedor@nexus.com / vendedor123');
        $this->command->info('  - luis.vendedor@nexus.com / vendedor123');
        $this->command->info('');
        $this->command->info('Clientes:');
        $this->command->info('  - pedro.cliente@example.com / cliente123');
        $this->command->info('  - laura.cliente@example.com / cliente123');
        $this->command->info('  (y 3 clientes más)');
    }
}
