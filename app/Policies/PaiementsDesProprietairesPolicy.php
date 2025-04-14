<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PaiementsDesProprietaires;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaiementsDesProprietairesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaiementsDesProprietaires $paiementsDesProprietaires): bool
    {
        return $user->can('view_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaiementsDesProprietaires $paiementsDesProprietaires): bool
    {
        return $user->can('update_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaiementsDesProprietaires $paiementsDesProprietaires): bool
    {
        return $user->can('delete_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PaiementsDesProprietaires $paiementsDesProprietaires): bool
    {
        return $user->can('force_delete_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PaiementsDesProprietaires $paiementsDesProprietaires): bool
    {
        return $user->can('restore_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PaiementsDesProprietaires $paiementsDesProprietaires): bool
    {
        return $user->can('replicate_paiements::des::proprietaires');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_paiements::des::proprietaires');
    }
}
