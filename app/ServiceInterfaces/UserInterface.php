<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;

interface UserInterface
{
    public function addUser(Request $request);
    public function addGroup(Request $request);
    public function getUsers(Request $request);
    public function updateUser(Request $request,$user_id);
    public function myContracts(Request $request, $user_id);

}
