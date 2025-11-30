<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Nombre de la tabla en la base de datos
    protected $table = 'usuarios';

    // Clave primaria
    protected $primaryKey = 'id_usuario';

    // Nombres de las columnas de timestamps
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_completo',
        'correo_electronico',
        'contrasena',
        'telefono',
        'direccion',
        'id_rol',
    ];

    // Campos ocultos en JSON
    protected $hidden = [
        'contrasena',
    ];

    // Casts de atributos
    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed',
        ];
    }

    // Personalizar serialización a JSON
    public function toArray()
    {
        return [
            'id' => $this->id_usuario,
            'name' => $this->nombre_completo,
            'email' => $this->correo_electronico,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'id_rol' => $this->id_rol,
            'created_at' => $this->fecha_creacion,
            'updated_at' => $this->fecha_actualizacion,
        ];
    }
}
