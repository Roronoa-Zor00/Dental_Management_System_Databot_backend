<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            'super_admin',
            'client',
            'sub_client',
            'case_submission',
            'quality_check',
            'post_processing',
            'treatment_planner',
        ];
        if (!empty($roles)) {
            foreach ($roles as $value) {
                // Check if the role already exists
                if (!Role::where('name', $value)->exists()) {
                    $role = new Role();
                    $role->name = $value;
                    $role->save();
                }
            }
        }

        $permissions = [
            'users-list',
            'users-store',
            'users-detail',
            'users-update',
            'users-delete',
            'roles-list',
            'roles-store',
            'roles-detail',
            'roles-update',
            'roles-delete',
            'permissions-list',
            'permissions-store',
            'permissions-detail',
            'permissions-update',
            'permissions-delete',
            'patient-cases-list',
            'patient-cases-store',
            'patient-cases-detail',
            'patient-cases-update',
            'patient-cases-delete',
            'patient-cases-case-assign-to',
            'pending-approvals-list',
            'pending-approvals-store',
            'pending-approvals-detail',
            'pending-approvals-update',
            'pending-approvals-delete',
            'modification-receiveds-list',
            'modification-receiveds-store',
            'modification-receiveds-detail',
            'modification-receiveds-update',
            'modification-receiveds-delete',
            'need-more-info-list',
            'need-more-info-store',
            'need-more-info-detail',
            'need-more-info-update',
            'need-more-info-delete',
            'step-file-ready-list',
            'step-file-ready-store',
            'step-file-ready-detail',
            'step-file-ready-update',
            'step-file-ready-delete',
            'teams-list',
            'teams-store',
            'teams-detail',
            'teams-update',
            'teams-delete',
            'teams-assings-to-users',
            'softwares-list',
            'softwares-store',
            'softwares-detail',
            'softwares-update',
            'softwares-delete',
            'external-cases-list',
            'external-cases-store',
            'external-cases-detail',
            'external-cases-update',
            'external-cases-delete',
        ];
        if (!empty($permissions)) {
            foreach ($permissions as $key => $value) {

                if (!Permission::where('name', $value)->exists()) {
                    // Check if the permission already exists
                    $permission = new Permission();
                    $permission->name = $value;
                    $permission->save();
                }
            }
        }

        $allPermissions = Permission::all();
        $adminRole = Role::where('name', 'super_admin')->first();

        if ($adminRole) {
            foreach ($allPermissions as $permission) {
                if (!$adminRole->hasPermissionTo($permission)) {
                    $adminRole->givePermissionTo($permission);
                }
            }

            // Create the admin user
            $email = 'admin@admin.com';
            $find_user = User::where('email', $email)->first();
            if (!$find_user) {
                $user = new User();
                $user->email = $email;
                $user->password = bcrypt('1111');
                $user->first_name = 'Super';
                $user->last_name = 'Admin';
                $user->username = 'super_admin';
                $user->mobile_number = '3412341234';
                $user->profile_pic = 'no-image.png';
                $user->save();
                if ($user && isset($adminRole)) {
                    $user->assignRole($adminRole);
                }
            }
        }

        // Create the client user
        $role = Role::where('name', 'client')->first();
        $email = 'client@client.com';
        $find_client_user = User::where('email', 'client@client.com')->first();
        if ($role && !$find_client_user) {
            $client_user = new User();
            $client_user->email = $email;
            $client_user->password = bcrypt('1111');
            $client_user->first_name = 'client';
            $client_user->last_name = 'client';
            $client_user->username = 'client';
            $client_user->mobile_number = '3412341234';
            $client_user->profile_pic = 'no-image.png';
            $client_user->save();

            if ($client_user) {
                $client_user->assignRole($role);
                $client_user_id = $client_user->id;

                // Create the sub-client user
                $subrole = Role::where('name', 'sub_client')->first();
                $subemail = 'subclient@subclient.com';
                $find_sub_client_user = User::where('email', $subemail)->first();
                if ($subrole && !$find_sub_client_user) {
                    $sub_client_user = new User();
                    $sub_client_user->email = $subemail;
                    $sub_client_user->password = bcrypt('1111');
                    $sub_client_user->first_name = 'subclient';
                    $sub_client_user->last_name = 'subclient';
                    $sub_client_user->username = 'subclient';
                    $sub_client_user->mobile_number = '3412341234';
                    $sub_client_user->profile_pic = 'no-image.png';
                    $sub_client_user->client_id = $client_user_id;
                    $sub_client_user->save();
                    if ($sub_client_user) {
                        $sub_client_user->assignRole($subrole);
                    }
                }
            }
        }


        // Create the case submission user
        $role = Role::where('name', 'case_submission')->first();
        $email = 'casesubmission@casesubmission.com';
        $find_user = User::where('email', $email)->first();
        if ($role && !$find_user) {
            $user = new User();
            $user->email = $email;
            $user->password = bcrypt('1111');
            $user->first_name = 'casesubmission';
            $user->last_name = 'casesubmission';
            $user->username = 'casesubmission';
            $user->mobile_number = '3412341234';
            $user->profile_pic = 'no-image.png';
            $user->save();
            if ($user) {
                $user->assignRole($role);
            }
        }

        // Create the quality check user
        $email = 'qualitycheck@qualitycheck.com';
        $role = Role::where('name', 'quality_check')->first();
        $find_user = User::where('email', $email)->first();
        if ($role && !$find_user) {
            $user = new User();
            $user->email = $email;
            $user->password = bcrypt('1111');
            $user->first_name = 'qualitycheck';
            $user->last_name = 'qualitycheck';
            $user->username = 'qualitycheck';
            $user->mobile_number = '3412341234';
            $user->profile_pic = 'no-image.png';
            $user->save();
            if ($user) {
                $user->assignRole($role);
            }
        }

        // Create the post processing user
        $email = 'postprocessing@postprocessing.com';
        $role = Role::where('name', 'post_processing')->first();
        $find_user = User::where('email', $email)->first();
        if ($role && !$find_user) {
            $user = new User();
            $user->email = $email;
            $user->password = bcrypt('1111');
            $user->first_name = 'postprocessing';
            $user->last_name = 'postprocessing';
            $user->username = 'postprocessing';
            $user->mobile_number = '3412341234';
            $user->profile_pic = 'no-image.png';
            $user->save();
            if ($user) {
                $user->assignRole($role);
            }
        }

        // Create the treatment planner user
        $email = 'treatmentplanner@treatmentplanner.com';
        $role = Role::where('name', 'treatment_planner')->first();
        $find_user = User::where('email', $email)->first();
        if ($role && !$find_user) {
            $user = new User();
            $user->email = $email;
            $user->password = bcrypt('1111');
            $user->first_name = 'treatmentplanner';
            $user->last_name = 'treatmentplanner';
            $user->username = 'treatmentplanner';
            $user->mobile_number = '3412341234';
            $user->profile_pic = 'no-image.png';
            $user->save();
            if ($user) {
                $user->assignRole($role);
            }
        }

    }
}
