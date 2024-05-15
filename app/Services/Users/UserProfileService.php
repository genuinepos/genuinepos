<?php

namespace App\Services\Users;

use App\Models\User;
use App\Utils\FileUploader;

class UserProfileService
{
    public function updateUserProfile(object $request): object
    {
        $updateProfile = User::where('id', auth()->user()->id)->first();
        $updateProfile->prefix = $request->prefix;
        $updateProfile->name = $request->first_name;
        $updateProfile->last_name = $request->last_name;
        $updateProfile->email = $request->email;
        $updateProfile->date_of_birth = $request->date_of_birth;
        $updateProfile->gender = $request->gender;
        $updateProfile->marital_status = $request->marital_status;
        $updateProfile->blood_group = $request->blood_group;
        $updateProfile->phone = $request->phone;
        $updateProfile->facebook_link = $request->facebook_link;
        $updateProfile->twitter_link = $request->twitter_link;
        $updateProfile->instagram_link = $request->instagram_link;
        $updateProfile->guardian_name = $request->guardian_name;
        $updateProfile->id_proof_name = $request->id_proof_name;
        $updateProfile->id_proof_number = $request->id_proof_number;
        $updateProfile->permanent_address = $request->permanent_address;
        $updateProfile->current_address = $request->current_address;
        $updateProfile->bank_ac_holder_name = $request->bank_ac_holder_name;
        $updateProfile->bank_ac_no = $request->bank_ac_no;
        $updateProfile->bank_name = $request->bank_name;
        $updateProfile->bank_identifier_code = $request->bank_identifier_code;
        $updateProfile->bank_branch = $request->bank_branch;
        $updateProfile->tax_payer_id = $request->tax_payer_id;
        $updateProfile->language = $request->language;

        if ($request->hasFile('photo')) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'user_photo/');

            $newFile = FileUploader::upload($request->file('photo'), $dir);
            if (
                isset($updateProfile->photo) &&
                file_exists($dir . $updateProfile->photo)
            ) {
                try {
                    unlink($dir . $updateProfile->photo);
                } catch (Exception $e) {
                }
            }

            $updateProfile->photo = $newFile;
        }

        $updateProfile->save();

        return $updateProfile;
    }
}
