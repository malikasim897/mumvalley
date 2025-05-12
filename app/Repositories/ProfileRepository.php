<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\DocumentRepository;

class ProfileRepository
{
    protected $documnentRepository;

    public function __construct(DocumentRepository $documnentRepository)
    {
        $this->documnentRepository = $documnentRepository;
    }

    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    public function update($request)
    {
        // $request->user()->fill($request->validated());
        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        // }
        $user = User::findOrFail($request->userId);
        $user->update([
            'name' => $request->first_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);


        $user->setting()->updateOrCreate([
            'user_id'   => $request->userId,
        ],[
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'address'    => $request->address,
            'country_id' => $request->country_id,
            'state'   => $request->state,
            'city'       => $request->city,
            'zipcode'    => $request->zipcode
        ]);

        if ($request->hasFile('image')) {
            $storagePath = 'public/images/users';
            $this->documnentRepository->uploadImage($request->user(), $request, $storagePath);
        }else{
            return true;
        }
        return true;
    }
}

