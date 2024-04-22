<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;

interface UserInterface
{
    public function addUser(Request $request);
    public function getUsers(Request $request);
    public function updateUser(Request $request,$user_id);
    public function myContracts(Request $request, $user_id);
    public function getGroups();
    public function assignUserGroups(Request $request);
    public function getGroupUsers(Request $request);
    public function getUsersList(Request $request);
    public function addUsersToIndividualGroup(Request $request);
    public function deleteUserFromGroup(Request $request);  
    public function deleteGroup(Request $request) ;




}
