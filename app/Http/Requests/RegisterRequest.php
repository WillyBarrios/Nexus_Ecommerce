<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validar el registro de nuevos usuarios
 * 
 * Este Form Request valida los datos de entrada cuando un usuario
 * intenta registrarse en el sistema. Asegura que todos los campos
 * cumplan con los requisitos de seguridad y formato.
 * 
 * Campos validados:
 * - name: Nombre completo del usuario (2-255 caracteres)
 * - email: Email único y válido (máximo 255 caracteres)
 * - password: Contraseña segura (mínimo 8 caracteres, con confirmación)
 * 
 * @package App\Http\Requests
 * @version Laravel 12.39.0
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta petición
     * 
     * Para el registro, cualquier usuario (incluso no autenticado) puede
     * hacer esta petición, por lo que siempre retornamos true.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Cualquier usuario puede registrarse
    }

    /**
     * Obtener las reglas de validación que aplican a la petición
     * 
     * Reglas de validación:
     * - name: Requerido, string, mínimo 2 caracteres, máximo 255
     * - email: Requerido, formato email válido, único en tabla users, máximo 255
     * - password: Requerido, string, mínimo 8 caracteres, debe coincidir con password_confirmation
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Nombre del usuario
            // - required: Campo obligatorio
            // - string: Debe ser una cadena de texto
            // - min:2: Mínimo 2 caracteres (evita nombres muy cortos)
            // - max:255: Máximo 255 caracteres (límite de la base de datos)
            'name' => 'required|string|min:2|max:255',

            // Email del usuario
            // - required: Campo obligatorio
            // - email: Debe tener formato de email válido (ej: usuario@dominio.com)
            // - unique:usuarios,correo_electronico: Debe ser único en la tabla usuarios, columna correo_electronico
            // - max:255: Máximo 255 caracteres (límite de la base de datos)
            'email' => 'required|email|unique:usuarios,correo_electronico|max:255',

            // Contraseña del usuario
            // - required: Campo obligatorio
            // - string: Debe ser una cadena de texto
            // - min:8: Mínimo 8 caracteres (seguridad básica)
            // - confirmed: Debe coincidir con el campo password_confirmation
            //   (Laravel busca automáticamente un campo llamado password_confirmation)
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Obtener los mensajes de error personalizados para las reglas de validación
     * 
     * Estos mensajes se muestran al usuario cuando la validación falla.
     * Todos los mensajes están en español para mejor comprensión.
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Mensajes para el campo 'name'
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',

            // Mensajes para el campo 'email'
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado en el sistema.',
            'email.max' => 'El email no puede tener más de :max caracteres.',

            // Mensajes para el campo 'password'
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }

    /**
     * Obtener los nombres de atributos personalizados para mensajes de validación
     * 
     * Esto permite que Laravel use nombres más amigables en los mensajes de error.
     * 
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ];
    }
}
