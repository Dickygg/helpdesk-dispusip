<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketModels> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationModels whereUpdatedAt($value)
 */
	class ApplicationModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $ticket_id
 * @property string $uploaded_by
 * @property string|null $file_path
 * @property string|null $file_name
 * @property string|null $file_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\TicketModels $ticket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttachmentModels whereUploadedBy($value)
 */
	class AttachmentModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel query()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $roles_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels whereRolesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModels whereUpdatedAt($value)
 */
	class RoleModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketModels> $tickets
 * @property-read int|null $tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUnitsModels whereUpdatedAt($value)
 */
	class ServiceUnitsModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $ticket_id
 * @property string $user_id
 * @property string $assigned_by
 * @property string $assigned_at
 * @property string|null $started_at
 * @property string|null $finished_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $admin
 * @property-read \App\Models\User $technician
 * @property-read \App\Models\TicketModels $ticket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereAssignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketAssignmentModels whereUserId($value)
 */
	class TicketAssignmentModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $ticket_id
 * @property string $user_id
 * @property string $action
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\TicketModels $ticket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketLogModels whereUserId($value)
 */
	class TicketLogModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $ticket_code
 * @property string $user_id
 * @property string $application_id
 * @property string|null $priority_id
 * @property \App\Models\TicketStatusModels|null $status
 * @property string $title
 * @property string|null $description
 * @property string|null $user_confirmed_at
 * @property string|null $admin_verified_at
 * @property string|null $verification_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $resolved_at
 * @property string|null $service_unit_id
 * @property string|null $due_date
 * @property string|null $note
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\ApplicationModels $application
 * @property-read \App\Models\TicketAssignmentModels|null $assignment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttachmentModels> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketLogModels> $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\TicketPriorityModels|null $priority
 * @property-read \App\Models\ServiceUnitsModels|null $serviceUnit
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereAdminVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereServiceUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereTicketCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereUserConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketModels whereVerificationStatus($value)
 */
	class TicketModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $created_by
 * @property int|null $estimated_hours Estimasi pengerjaan dalam jam
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketModels> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels whereEstimatedHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPriorityModels whereUpdatedAt($value)
 */
	class TicketPriorityModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketModels> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketStatusModels whereUpdatedAt($value)
 */
	class TicketStatusModels extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string|null $nrk
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $service_unit_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketAssignmentModels> $assignedBy
 * @property-read int|null $assigned_by_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketAssignmentModels> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\ServiceUnitsModels|null $serviceUnit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketModels> $tickets
 * @property-read int|null $tickets_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNrk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereServiceUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

