<?php
namespace App\ServiceInterfaces;
use Illuminate\Http\Request;
interface MsaInterface
{
public function addMsa(Request $request,$user_id);
public function editMsa(Request $request,$user_id);
public function renewMsa(Request $request,$user_id);

public function MSAList(Request $request);
public function msaCount(Request $request);
}