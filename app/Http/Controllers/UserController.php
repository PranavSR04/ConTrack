<?php

namespace App\Http\Controllers;

use App\Models\ExperionEmployees;
use App\ServiceInterfaces\UserInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserInterface $userService)
    {
        $this->userService = $userService;
    }


    public function addUser(Request $request)
    {
        return $this->userService->addUser($request);

    }

    public function getUsers(Request $request)
    {
        return $this->userService->getUsers($request);

    }

    public function updateUser(Request $request, $user_id)
    {
        return $this->userService->updateUser($request,$user_id);
    }

    public function myContracts(Request $request, $user_id)
    {
        return $this->userService->myContracts($request,$user_id);
    }

    public function getGroups()
    {
        return $this->userService->getGroups();
    }

    public function assignUserGroups(Request $request)
    {
        return $this->userService->assignUserGroups($request);
    }

    public function getGroupUsers(Request $request)
    {
        return $this->userService->getGroupUsers($request);

    }

    public function getUsersList(Request $request)
    {
        return $this->userService->getUsersList($request);
    }

    public function addUsersToIndividualGroup(Request $request)
    {
        return $this->userService->addUsersToIndividualGroup($request);
    }

    public function deleteUserFromGroup(Request $request)
    {
        return $this->userService->deleteUserFromGroup($request);
    }
    
    public function deleteGroup(Request $request)
    {
        return $this->userService->deleteGroup($request);
    }

    public function create()
    {
        //
        $usersData = [
            [
                'experion_id' => 1,
                'role_id' => 1,
                'user_name' => 'Gokul Surendran',
                'user_mail' => 'gokul.surendran@experionglobal.com',
                'user_designation' => 'Project Manager',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 2,
                'role_id' => 2,
                'user_name' => 'Athul Nair',
                'user_mail' => 'athul.nair@experionglobal.com',
                'user_designation' => 'UX Designer',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 4,
                'role_id' => 2,
                'user_name' => 'Aneeka Geo',
                'user_mail' => 'aneeka.geo@experionglobal.com',
                'user_designation' => 'UX Designer',
                'group_name' => '',
                'is_active' => true
            ]
        ];

        foreach ($usersData as $userData) {
            $user = new User($userData);
            $user->save();
        }

        return "5 meaningful users created successfully!";
    }


}
