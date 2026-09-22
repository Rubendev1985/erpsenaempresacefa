<?php

namespace Modules\Apicola\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\Role;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\EPS;
use Modules\SICA\Entities\PopulationGroup;
use Modules\SICA\Entities\PensionEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios del módulo Apícola.
     */
    public function index(Request $request)
    {
        // Asegurar que existan los roles básicos de Apícola
        $this->ensureApicolaRolesExist();

        // Query base para usuarios vinculados al módulo Apícola
        $query = User::withTrashed()
            ->with(['person', 'roles'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('slug', ['apicola.admin', 'apicola.aprendiz']);
            });

        // Filtro por texto (Nombre, Apellido, Documento, Nickname, Correo)
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nickname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('person', function ($qp) use ($search) {
                      $qp->where('first_name', 'like', "%{$search}%")
                         ->orWhere('first_last_name', 'like', "%{$search}%")
                         ->orWhere('second_last_name', 'like', "%{$search}%")
                         ->orWhere('document_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por rol
        if ($request->filled('role') && $request->role !== 'all') {
            $roleSlug = $request->role;
            $query->whereHas('roles', function ($q) use ($roleSlug) {
                $q->where('slug', $roleSlug);
            });
        }

        // Filtro por estado
        if ($request->filled('estado') && $request->estado !== 'all') {
            if ($request->estado === 'activo') {
                $query->whereNull('deleted_at');
            } elseif ($request->estado === 'inactivo') {
                $query->whereNotNull('deleted_at');
            }
        }

        $users = $query->orderBy('id', 'desc')->get();

        // Cálculo de métricas
        $baseCountQuery = User::withTrashed()->whereHas('roles', function ($q) {
            $q->whereIn('slug', ['apicola.admin', 'apicola.aprendiz']);
        });

        $totalUsuarios = (clone $baseCountQuery)->count();
        $totalAdmins = (clone $baseCountQuery)->whereHas('roles', function ($q) {
            $q->where('slug', 'apicola.admin');
        })->count();
        $totalAprendices = (clone $baseCountQuery)->whereHas('roles', function ($q) {
            $q->where('slug', 'apicola.aprendiz');
        })->count();
        $totalActivos = (clone $baseCountQuery)->whereNull('deleted_at')->count();

        $metrics = [
            'total' => $totalUsuarios,
            'admins' => $totalAdmins,
            'aprendices' => $totalAprendices,
            'activos' => $totalActivos,
        ];

        // Roles disponibles para asignación
        $rolesDisponibles = Role::whereIn('slug', ['apicola.admin', 'apicola.aprendiz'])->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'users' => $users,
                'metrics' => $metrics
            ]);
        }

        return view('apicola::Admin.Usuarios.index', compact('users', 'metrics', 'rolesDisponibles'));
    }

    /**
     * Consulta si una persona ya existe en el sistema SICA por su número de documento.
     */
    public function searchPerson(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor ingrese un número de documento válido.'
            ], 422);
        }

        $documentNumber = $request->input('document_number');
        $person = Person::withTrashed()->where('document_number', $documentNumber)->first();

        if ($person) {
            // Verificar si la persona ya tiene un usuario registrado
            $existingUser = User::withTrashed()->where('person_id', $person->id)->with('roles')->first();

            return response()->json([
                'success' => true,
                'found' => true,
                'person' => [
                    'id' => $person->id,
                    'document_type' => $person->document_type ?? 'Cédula de ciudadanía',
                    'document_number' => $person->document_number,
                    'first_name' => $person->first_name,
                    'first_last_name' => $person->first_last_name,
                    'second_last_name' => $person->second_last_name,
                    'personal_email' => $person->personal_email,
                ],
                'has_user' => (bool) $existingUser,
                'user' => $existingUser ? [
                    'id' => $existingUser->id,
                    'nickname' => $existingUser->nickname,
                    'email' => $existingUser->email,
                    'is_active' => !$existingUser->trashed(),
                    'roles' => $existingUser->roles->pluck('name', 'slug'),
                ] : null,
            ]);
        }

        return response()->json([
            'success' => true,
            'found' => false,
            'message' => 'La persona no se encuentra registrada en el sistema SICA. Se registrarán sus datos junto con el nuevo usuario.'
        ]);
    }

    /**
     * Almacena un nuevo usuario y su persona asociada en el módulo Apícola.
     */
    public function store(Request $request)
    {
        $rules = [
            'document_type' => 'required|string|max:50',
            'document_number' => 'required|numeric',
            'first_name' => 'required|string|max:60',
            'first_last_name' => 'required|string|max:60',
            'second_last_name' => 'nullable|string|max:60',
            'nickname' => 'required|string|max:50|unique:users,nickname',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role_slug' => 'required|in:apicola.admin,apicola.aprendiz',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Buscar o crear la persona
            $person = Person::withTrashed()->where('document_number', $request->document_number)->first();

            if ($person) {
                if (method_exists($person, 'trashed') && $person->trashed()) {
                    $person->restore();
                }
                // Si la persona ya tiene un usuario vinculado
                $existingUser = User::withTrashed()->where('person_id', $person->id)->first();
                if ($existingUser) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Esta persona ya cuenta con el usuario '{$existingUser->nickname}' en el sistema. Puedes editarlo para asignarle el rol de Apícola."
                    ], 422);
                }

                // Actualizar datos básicos de la persona
                $person->update([
                    'document_type' => $request->document_type,
                    'first_name' => mb_strtoupper(trim($request->first_name)),
                    'first_last_name' => mb_strtoupper(trim($request->first_last_name)),
                    'second_last_name' => $request->second_last_name ? mb_strtoupper(trim($request->second_last_name)) : null,
                    'personal_email' => $request->email,
                ]);
            } else {
                // Crear nueva persona con defaults para SICA
                $eps = EPS::firstOrCreate(['name' => 'NO REGISTRA']);
                $populationGroup = PopulationGroup::firstOrCreate(['name' => 'NINGUNA']);
                $pensionEntity = PensionEntity::firstOrCreate(['name' => 'NO REGISTRA']);

                $person = Person::create([
                    'document_type' => $request->document_type,
                    'document_number' => $request->document_number,
                    'first_name' => mb_strtoupper(trim($request->first_name)),
                    'first_last_name' => mb_strtoupper(trim($request->first_last_name)),
                    'second_last_name' => $request->second_last_name ? mb_strtoupper(trim($request->second_last_name)) : null,
                    'personal_email' => $request->email,
                    'eps_id' => $eps->id,
                    'population_group_id' => $populationGroup->id,
                    'pension_entity_id' => $pensionEntity->id,
                ]);
            }

            // 2. Crear el usuario
            $user = User::create([
                'nickname' => trim($request->nickname),
                'email' => trim($request->email),
                'person_id' => $person->id,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // 3. Asignar rol seleccionado para Apícola
            $role = Role::where('slug', $request->role_slug)->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente con acceso a Apícola.',
                'user' => $user->load(['person', 'roles'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra la información de un usuario para edición.
     */
    public function show($id)
    {
        $user = User::withTrashed()->with(['person', 'roles'])->findOrFail($id);

        $apicolaRole = $user->roles->first(function ($r) {
            return in_array($r->slug, ['apicola.admin', 'apicola.aprendiz']);
        });

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'is_active' => !$user->trashed(),
                'role_slug' => $apicolaRole ? $apicolaRole->slug : 'apicola.aprendiz',
                'person' => [
                    'id' => $user->person?->id,
                    'document_type' => $user->person?->document_type ?? 'Cédula de ciudadanía',
                    'document_number' => $user->person?->document_number ?? '',
                    'first_name' => $user->person?->first_name ?? '',
                    'first_last_name' => $user->person?->first_last_name ?? '',
                    'second_last_name' => $user->person?->second_last_name ?? '',
                ]
            ]
        ]);
    }

    /**
     * Actualiza los datos y rol de un usuario existente.
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $rules = [
            'document_type' => 'required|string|max:50',
            'first_name' => 'required|string|max:60',
            'first_last_name' => 'required|string|max:60',
            'second_last_name' => 'nullable|string|max:60',
            'nickname' => 'required|string|max:50|unique:users,nickname,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_slug' => 'required|in:apicola.admin,apicola.aprendiz',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Actualizar persona
            if ($user->person) {
                $user->person->update([
                    'document_type' => $request->document_type,
                    'first_name' => mb_strtoupper(trim($request->first_name)),
                    'first_last_name' => mb_strtoupper(trim($request->first_last_name)),
                    'second_last_name' => $request->second_last_name ? mb_strtoupper(trim($request->second_last_name)) : null,
                    'personal_email' => $request->email,
                ]);
            }

            // 2. Actualizar usuario
            $user->nickname = trim($request->nickname);
            $user->email = trim($request->email);

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // 3. Sincronizar rol de Apícola (desasociar los otros roles apícola y poner el nuevo)
            $allApicolaRoles = Role::whereIn('slug', ['apicola.admin', 'apicola.aprendiz'])->pluck('id')->toArray();
            $newRole = Role::where('slug', $request->role_slug)->first();

            if ($newRole) {
                // Quitar roles viejos de apicola y agregar el nuevo sin tocar roles de otros módulos
                $user->roles()->detach($allApicolaRoles);
                $user->roles()->attach($newRole->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado con éxito.',
                'user' => $user->load(['person', 'roles'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alterna el estado activo / inactivo de un usuario (SoftDeletes).
     */
    public function toggleStatus($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (auth()->check() && auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes desactivar tu propia cuenta en sesión.'
            ], 403);
        }

        if ($user->trashed()) {
            $user->restore();
            $nuevoEstado = 'Activo';
            $message = "El usuario '{$user->nickname}' ha sido activado correctamente.";
        } else {
            $user->delete();
            $nuevoEstado = 'Inactivo';
            $message = "El usuario '{$user->nickname}' ha sido desactivado.";
        }

        return response()->json([
            'success' => true,
            'nuevo_estado' => $nuevoEstado,
            'message' => $message
        ]);
    }

    /**
     * Garantiza que los roles de Apícola existan en la base de datos vinculados a la App Apícola.
     */
    private function ensureApicolaRolesExist()
    {
        $app = App::where('name', 'like', '%apicol%')
                  ->orWhere('url', 'like', '%apicol%')
                  ->first();

        $appId = $app ? $app->id : 1;

        Role::firstOrCreate(
            ['slug' => 'apicola.admin'],
            [
                'name' => 'Administrador Apícola',
                'description' => 'Administrador exclusivo del módulo Apícola.',
                'description_english' => 'Exclusive Administrator for the Beekeeping module.',
                'app_id' => $appId,
            ]
        );

        Role::firstOrCreate(
            ['slug' => 'apicola.aprendiz'],
            [
                'name' => 'Aprendiz Apícola',
                'description' => 'Rol de Aprendiz para el módulo Apícola.',
                'description_english' => 'Apprentice Role for the Beekeeping module.',
                'app_id' => $appId,
            ]
        );
    }
}
